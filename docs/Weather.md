# Weather

The current weather of a zone is based on the current unix epoch, and a zone weather table (WeatherRate).

A forecast target integer is first calculated.  This value to the frequency of the zone's weather.

When the number is less than the cumulative rate at which a weather pattern occurs, that weather will appear in the zone.

For example, in Gridania it's raining 20% of the time, and foggy 10%. It rains when the number is < 20, and foggy when it's >= 20 and < 30, and so on.

````js
calculateForecastTarget: function(lDate) { 
    // Thanks to Rogueadyn's SaintCoinach library for this calculation.
    // lDate is the current local time.

    var unixSeconds = parseInt(lDate.getTime() / 1000);
    // Get Eorzea hour for weather start
    var bell = unixSeconds / 175;

    // Do the magic 'cause for calculations 16:00 is 0, 00:00 is 8 and 08:00 is 16
    var increment = (bell + 8 - (bell % 8)) % 24;

    // Take Eorzea days since unix epoch
    var totalDays = unixSeconds / 4200;
    totalDays = (totalDays << 32) >>> 0; // Convert to uint

    // 0x64 = 100
    var calcBase = totalDays * 100 + increment;

    // 0xB = 11
    var step1 = (calcBase << 11) ^ calcBase;
    var step2 = (step1 >>> 8) ^ step1;

    // 0x64 = 100
    return step2 % 100;
}
````
