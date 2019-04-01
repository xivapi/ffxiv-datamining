# Companion App API

- https://eu.finalfantasyxiv.com/companion/

The FINAL FANTASY XIV companion app has the ability to query the game servers for a specific resource to pull information (market info, retainer items, player items, chat messages, etc). This document will list research and provide logic for obtaining data out of the app as well as quering the companion API.

___

# Ban Notice

> **IMPORTANT: If you spam the Sight API with a lot of requests in a short period. SE will ban you. This is not automated but is final with no warning. Be very careful how you access the API and if possoble use a dummy account, Twitch Prime starter keys will work. You only need to make a character and enter the game, you do not even need to step forward. Once the character has been logged in it will have Companion Accesss**

___

What we know:

- The Companion App Queries a PHP 7 server. 
- Queries are not performed in real-time but instead a request adds to a queue and then the servers process the request and feed the result back to the same request ID when queried again. This means you have to query several times to the same url (using the same request-id) in order to finally get a result on the nth try. This is usually around 2-3 seconds.
- Tokens last 24 hours before being expired. Unknown if using the companion app will "increase" the duration a token however using the API does not extend the token duration.
- You can download the APK anywhere and rename it to `.zip` to extract the contents, there are some nice icons and a very basic `sqlite` file, unfortunately this does not contain any useful info like Libra did (eg no dungeon loot tables anymore...)

## Current tools

Minoost has done some fantastic work reverse engineering the auth of the app, you can find his work here: https://github.com/Minoost/libpompom-sharp

PHP Library: https://github.com/xivapi/companion-php

## Getting data

This segment will provide a small amount of PHP as that is what I am familiar with, however because I've not translated the auth logic into PHP you will need a valid token

### Getting a token

This is quite easy if you setup some software. I use: https://www.telerik.com/fiddler
- I use my iPhone X, you do not need to jail break: https://docs.telerik.com/fiddler/Configure-Fiddler/Tasks/ConfigureForiOS
- If you need help with setting up Fiddle to talk to your iphone, just ping Vekien in xivapi.com Discord, it is very easy.
- If you have an android, it is probably even simplier, there will be lots of guides.

Once you have a proxy to fiddler, go to filters and add the url `companion-eu.finalfantasyxiv.com`, change `-eu` to your data center, or remove it completely to see login/auth queries. If you use the app from here you can begin to see the different endpoints and how they work.

Once you have a valid token, you can provide it to any endpoint and everything will work. The following script in PHP requires you to install guzzle `composer req guzzlehttp/guzzle` (you could just use curl internally if you prefer)

**Some header info:**
- `token` - Your token from the app, find via Fiddler
- `request-id` - Any kind of string can be here, the app uses UUID however it can be anything, a string, text, numbers, whatever, a poem...
> **Important** if you make the `request-id` unique PER REQUEST you will have a huge delay, around 2 seconds per call, if you use the same string for each request, you will get responses instantly from the API (often first query) however it will be a cached response until the response is updated via a "new" request-id, this includes someone else using the app and queries the same endpoint your data will update using the same request, I've no idea what bonkers system SE are using.

- `Content-Type` - `application/json;charset=utf-8`
- `Accept` - `*/*'`
- `User-Agent` - `ffxivcomapp-e/1.0.1.0 CFNetwork/974.2.1 Darwin/18.0.0`

**PHP code to query the API**

```php
<?php
/**
 * composer req ramsey/uuid guzzlehttp/guzzle
 */
require __DIR__ .'/vendor/autoload.php';

use GuzzleHttp\Client;

$headers = [
    // Your token, you would need to watch your traffic (eg I use Fiddler)
    // for this or use: https://github.com/Minoost/libpompom-sharp
    'token'             => '<YOUR TOKEN>',
    
    // this is your request-id and will affect SE's cache, you will not get up to date
    // information until the same request has been made with a different request-id
    // so this example just generates a random one each time
    'request-id'        => time(),
    
    // other random blab - This isn't needed but is nice to have
    'Content-Type'      => 'application/json;charset=utf-8',
    'User-Agent'        => 'ffxivcomapp-e/1.0.1.0 CFNetwork/974.2.1 Darwin/18.0.0',
];

$client = new Client([
    'base_uri' => 'https://companion-eu.finalfantasyxiv.com/', //'https://companion.finalfantasyxiv.com',
    'timeout'  => 10,
]);

/**
 * base_uri = https://companion.finalfantasyxiv.com
 *   /points/status
 *   /login/character
 *   /login/characters
 *   /login/region
 *
 * base_uri = https://companion-eu.finalfantasyxiv.com/
 *                              ^^ can be na or ja (i think data center?)
 *
 * These require the correct endpoint, eg:
 *  - login/characters/{character_id}, you need to query /login/character or /login/characters to
 *    get your character id.
 *
 * It returns a region, which will be the new 'base_uri'
 *
 *      /character/login-status
 *      /items/character
 *
 *      /market/items/catalog/18189 (<-- item id , aka "catalogId")
 */

// You have to keep hitting the API until you get a 200, 
// as it queues requests and process them in the background
foreach (range(0, 15) as $second) {
	usleep(200000);

    $response = $client->get('/sight-v060/sight/market/items/catalog/5', [
        \GuzzleHttp\RequestOptions::HEADERS => $headers,
    ]);

    if ($response->getStatusCode() == 202) {
    	continue;
    }
    
    if ($response->getStatusCode() == 200) {
		// this is the API JSON data
        print_r((string)$response->getBody());
        break;
    }

    print_r($response);
}
```

To run this code:
- Update the `token`
- install dependencies using composer: `composer req guzzlehttp/guzzle`
- Copy the code to a file, eg: `companion.php`
- Call: `php companion.php`
  - the variable `$data` will be json, you could do `json_decode($data, true)` to get the response as an array of data.

### Market Response info
- `stack` Quantity for sale
- `catalogId` ItemID you see in the game files
- `signatureName` the person who crafted the item
- `isCrafted` obvious
- `hq` hq or not, (see how SE don't do `isHq`, consistency!)
- `stain` the stain values, can't remember how this is formatted
- `sellPrice` obvious the gil price
- `sellRetainerName` the name of the retainer
- `town` the id of the town, this matches identically to Town.csv you get from the data files
- `materia` this is an array of materia:
  - `key` = ID of the materia in the materia.csv file
  - `grade` = the grade of the materia, which if you see in the materia.csv is just a numeric column number, starts from 0
  
### Minor notes

- Market endpoint ids match ItemSearchCategory.csv
- Items that are not sellable can be queried, you will get no listings however you will get lodestone ID's which can be useful (HQ Icons!)
- There doesn't seem to be any restrictions on spam or concurrent queries, I've been able to setup 4 terminals and query every item on the market board within 2 hours. I am going to test higher concurrent queries and see if there is a rate-limit, or I get banned lol.
- You can log into multiple accounts and characters, generate tokens and those will last for 24 hours. It does not matter if you use the same account for multiple characters. This means to cover all 66 servers you would need 2 accounts (40 characters per account), costing about $30/month, very little.... Trail accounts do not work.
- No known way to query Korean or Chinese market boards at this time.

### All endpoints:

##### Payments

- POST("points/kupo-nuts")
- PUT("points/mog-coins/android")
- GET("purchase/charge")
- GET("purchase/cesa-limit")
- GET("purchase/user-birth")
- GET("points/history")
- GET("points/status")
- GET("points/products")
- POST("purchase/user-birth")
- POST("points/interrupted-process")
- POST("purchase/transaction")

##### Market

- DELETE("market/retainers/{cid}/rack/{itemId}")
- PATCH("market/retainers/{cid}/rack/{itemId}")
- GET("market/items/catalog/{catalogId}/hq")
  - `catalogId` = ItemID in game files
- GET("market/items/catalog/{catalogId}")
- GET("market/items/category/{categoryId}")
  - `categoryId` = ItemSearchCategory.csv in game files
- GET("market/retainers/{cid}")
- GET("market/items/history/catalog/{catalogId}")
- POST("market/item")
- POST("market/retainers/{cid}/rack")
- POST("market/retainers/{cid}")
- POST("market/payment/transaction")
- DELETE("market/retainers/{cid}")

##### Login

- DELETE("login/auth") - Headers({"domain-type: global"})
- GET("login/character")
- GET("login/characters") - Headers({"domain-type: global"})
- GET("login/region") - Headers({"domain-type: global"})
- POST("login/auth") - Headers({"domain-type: global"})
- POST("login/characters/{cid}") - Headers({"domain-type: global"})
	- `{cid}` is an internal id and not what you see on lodestone
- POST("login/token") - Headers({"domain-type: global"})
- POST("login/advertising-id")
- POST("login/fcm-token")

##### Item

- GET("items/character")
- GET("character/login-status")
- GET("items/retainers/{retainerCid}")
- GET("retainers")
- PUT("items/{type}/{cid}/gil")
- PUT("items/{type}/{cid}/{storage}")
- DELETE("items/{type}/{cid}/{storage}/{itemId}")
- PUT("items/recycle/{itemId}")

##### ChatRoom

- POST("chatrooms/{rid}/members")
- DELETE("chatrooms/{rid}")
- DELETE("chatrooms/{rid}/messages/{seqNum}")
- GET("chatrooms/{rid}")
- GET("chatrooms")
- GET("chatrooms/{rid}/messages")
- POST("chatrooms")
- POST("chatrooms/{rid}/messages")
- POST("chatrooms/{rid}/push-notification")
- PATCH("chatrooms/{rid}/messages/last-chat")
- PATCH("chatrooms/{rid}/setting")

##### Address Book

- HTTP(hasBody=true, method="DELETE", path="address-book/blocklist")
- GET("address-book")
- GET("address-book/{cid}/profile")
- POST("address-book/blocklist")

##### Schedule

- PATCH("schedules/{sid}/cancel")
- PATCH("schedules/{sid}/close")
- DELETE("schedules/{sid}")
- PATCH("schedules/{sid}")
- GET("schedules/chatrooms/{rid}")
- GET("schedules/{sid}")
- GET("schedules/history")
- GET("schedules")
- POST("schedules")
- POST("schedules/{sid}/push-notification")
- PATCH("schedules/{sid}/role")

##### Report

- POST("report/chatrooms/{rid}/message")
- POST("report/chatrooms/{rid}")
- POST("report/schedules/{sid}")
- POST("report/schedules/{sid}/comment")
