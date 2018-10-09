# Conversions to 2D Map


### Version of an in-game coordinate to a 2D map coordinate

**By: Clorifex, slightly modified by: Vekien - In C#**
https://github.com/xivapi/xivapi-mappy/blob/master/Mappy/Helpers/MapHelper.cs

```csharp
public static double ConvertCoordinatesIntoMapPosition(double scale, double offset, double val)
{
    val = Math.Round(val, 3);
    val = (val + offset) * scale;
    return ((41.0 / scale) * ((val + 1024.0) / 2048.0)) + 1;
}
```

### Conversion of a Level coordinate into a 2D map coordinate:

**By: Clorifex - In C#**

```csharp
private double ToMapCoordinate(double val) {
	var c = Map.SizeFactor / 100.0;

	val *= c;
	return ((41.0 / c) * ((val + 1024.0) / 2048.0)) + 1;
}
```

### Conversion of a FishingSpot coordinate into a 2D map coordinate:

**By: Clorifex - In C#**

```csharp
private double ToMapCoordinate(double val) {
	var c = TerritoryType.Map.SizeFactor / 100.0;

	return (41.0 / c) * ((val) / 2048.0) + 1;
}
```

### Conversion of MapPosition to Pixels

**By: Vekien - In C#**
https://github.com/xivapi/xivapi-mappy/blob/master/Mappy/Helpers/MapHelper.cs

```csharp
public static int ConvertMapPositionToPixels(double value, double scale)
{
    return Convert.ToInt32((value - 1) * 50 * scale);
}
```

----

To translate a `levels.exd` position to an in-game X/Y:

```php
//
// This takes the X/Y, scale and tilescale from levels and converts it to
// and in-game X/Y position, I have not found tile scale in the files
// and it's never not been 50 (50px per tile), its not 100% accurate, 
// tilescale is actually something like 49.951219... (2048/41)
//
public function mapsTranslateXYZToGame($x, $y, $scale, $tilescale = 50)
{
    if ($x == 0 || $y == 0 || $scale == 0) {
        return false;
    }

    $map = 2048 / ($scale / 100);

    $tilecount = $map / $tilescale;

    $x = ceil(round(($x / $tilescale) + ($tilecount / 2),1));
    $y = ceil(round(($y / $tilescale) + ($tilecount / 2),1));

    return [
        'x' => $x,
        'y' => $y,
    ];
}
```

And finally, to translate an in game X/Y to a `levels.exd` position which can then be converted to a 2D or Long/Lat:

```php
//
// Translates a X/Y from in-game to a levels output (which can then be translate dto lat/long
// this is quite accurate, not found it to be off at all. Requires map scale
//
public function mapsTranslateGameToXYZ($x, $y, $scale)
{
    $scale = $scale / 100;

    $x = ($x*50)-25-(1024 / $scale);
    $y = ($y*50)-25-(1024 / $scale);

    return [
        'x' => $x,
        'y' => $y,
    ];
}
```
