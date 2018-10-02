# FFXIV Companion App
- https://eu.finalfantasyxiv.com/companion/

The FINAL FANTASY XIV companion app has the ability to query the game servers for a specific resource to pull information (market info, retainer items, player items, chat messages, etc). This document will list research and provide logic for obtaining data out of the app as well as quering the companion API.

What we know:

- The Companion App Queries a PHP 7 server. 
- Queries are not performed in real-time but instead a request adds to a queue and then the servers process the request and feed the result back to the same request ID when queried again. This means you have to query several times to the same url (using the same request-id) in order to finally get a result on the nth try. This is usually around 2-3 seconds.
- Tokens last 24 hours before being expired. Unknown if using the companion app will "increase" the duration a token however using the API does not extend the token duration.
- You can download the APK anywhere and rename it to `.zip` to extract the contents, there are some nice icons and a very basic `sqlite` file, unfortunately this does not contain any useful info like Libra did (eg no dungeon loot tables anymore...)

## Current tools

Minoost has done some fantastic work reverse engineering the auth of the app, you can find his work here: https://github.com/Minoost/libpompom-sharp

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
> **Important** if you make the `request-id` unique PER REQUEST you will have a huge delay, around 2 seconds per call, if you use the same string for each request, you will get responses instantly from the API (often first query). It is more beneficial to hard code your request id, but this is not intentional by SE i believe, this is not what the app does...
> **Important update: 27th August 2018** - This "exploit" seems to have been patched, there looks to be `request-id` rate limiting, a very basic NGINX with bursting. Need more testing. The request-id can still be anything but if you use the same one you'll get rate-limited by the app.

- `Content-Type` - `application/json;charset=utf-8`
- `Accept` - `*/*'`
- `domain-type` - `global`
- `User-Agent` - `ffxivcomapp-e/1.0.1.0 CFNetwork/974.2.1 Darwin/18.0.0`

**PHP code to query the API**

```php
<?php
require __DIR__ .'/vendor/autoload.php';
use GuzzleHttp\Client;

$headers = [
    // Your token, you would need to watch your traffic (eg I use Fiddler)
    // for this or use: https://github.com/Minoost/libpompom-sharp
    'token'             => '<put your token here>',
    'request-id'        => "lol whatever you want here",
    
    // other random blab
    'Content-Type'      => 'application/json;charset=utf-8',
    'Accept'            => '*/*',
    'domain-type'       => 'global',
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
 * base_uri = https://companion-eu.finalfantasyxiv.com
 *                              ^^ can be na or ja (i think data center?)
 *
 * These require the correct endpoint, eg:
 *  - login/characters/{character_id}, you need to query /login/character or /login/characters to
 *    get your character id.
 *
 * It returns a region, which will be the new 'base_uri'
 *
 *   /character/login-status
 *   /items/character
 *   /market/items/catalog/18189 (<-- item id , aka "catalogId")
 */

// You have to keep hitting the API until you get a 200, 
// as it queues requests and processes them in the background
// This will attempt 15 times delaying for 250ms
foreach (range(0, 15) as $i) {
    // get market info for item id 18189
    $response = $client->get('/sight-v060/sight/market/items/catalog/18189', [
        \GuzzleHttp\RequestOptions::HEADERS => $headers,
    ]);
    
    if ($response->getStatusCode() == 200) {
	// this is the API JSON data
        $data = (string)$response->getBody();
		
	// this is the json response from the server, do something with it.
	print_r($data);
        break;
    }
    
    // delay for 250 milliseconds
    usleep(250000);
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
- At this time if you are looking to query the market board for every server you would need to have an account per server this is due to how the app locks you into 1 character for an account, if you try to switch characters it would reset your token. You could in theory use 1 account for many servers however you could not do this in real time and due to the nature of the API taking 2-3 seconds a response, you're looking at about 8 hours to query every possible item for 1 server. I'll let someone else handle the logistics of this (*note: Not tested if a token is reset when switching character, just assuming by how its designed*)
   - There are 66 servers, cheapest acc: $12.99 (trail accounts do not work), so looking at $858/month for 1 char per server :D
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
