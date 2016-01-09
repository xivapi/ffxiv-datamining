# Conversions to 2D Map

*Code samples in C#*

Conversion of a Level coordinate into a 2D map coordinate:
```csharp
private double ToMapCoordinate(double val) {
	var c = Map.SizeFactor / 100.0;

	val *= c;
	return ((41.0 / c) * ((val + 1024.0) / 2048.0)) + 1;
}
```

Conversion of a FishingSpot coordinate into a 2D map coordinate:
```csharp
private double ToMapCoordinate(double val) {
	var c = TerritoryType.Map.SizeFactor / 100.0;

	return (41.0 / c) * ((val) / 2048.0) + 1;
}
```

# Conversions to Long/Lat (eg Leaflets/Google Maps)

*Code samples in PHP*

You can translate the `levels.exd` values to Long/Lat using the `x`, `y` and **scale** would be the maps [size_factor](https://github.com/viion/XIV-Datamining/blob/master/offsets/3.1_list.txt#L679)

```php
//
// Used due to spherical maps, world is round irl :P
//
function mapsRadiansToDegrees($rad)
{
    return $rad / (pi() / 180);
}

//
// This code will take the X, Y and map scale from the levels CSV and convert it into a LAT/LONG 
// which can be used on leaflet or Google maps or just about any map tool
//
public function mapsTranslateXYZtoLatLong($x, $y, $scale = 100)
{
    if ($x == 0 || $y == 0 || $scale == 0) {
        return false;
    }
    
    $scale = $scale / 100;
    
    $tilesize = 2048 / $scale;
    
    $pixelsPerLonDegree = $tilesize / 360;
    $pixelsPerLonRadian = $tilesize / (2 * pi());
    
    $lng = $x / $pixelsPerLonDegree;
    $latRadians = $y / -$pixelsPerLonRadian;
    $lat = $this->mapsRadiansToDegrees(2 * atan(exp($latRadians)) - pi() / 2);
    
    $coords = [
        'x' => $lng,
        'y' => $lat,
    ];
    
    return $coords;
}
```

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
