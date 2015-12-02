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

You can translate the `levels.exd` values to Long/Lat using the `x`, `y` and **scale** would be the maps ` [size_factor](https://github.com/viion/XIV-Datamining/blob/master/offsets/3.1_list.txt#L679)`

```
function mapsRadiansToDegrees($rad)
{
    return $rad / (pi() / 180);
}

function mapsTranslateXYZtoLatLong($x, $y, $scale = 100)
{
    $scale = $scale / 100;
    $tilesize = 2048 / $scale;

    $pixelsPerLonDegree = $tilesize / 360;
    $pixelsPerLonRadian = $tilesize / (2 * pi());

    $lng = $x / $pixelsPerLonDegree;
    $latRadians = $y / -$pixelsPerLonRadian;
    $lat = mapsRadiansToDegrees(2 * atan(exp($latRadians)) - pi() / 2);

    return [
        'x' => $lng,
        'y' => $lat,
    ];
}
```

To translate a `levels.exd` position to an in-game X/Y:

```
function mapsTranslateXYZToGame($x, $y, $scale, $tilescale = 50)
{
    $map = 2048 / ($scale / 100);
    
    $TileCount = $map / $tilescale;
    
    $x = ceil(round(($x / $tilescale) + ($TileCount / 2),1));
    $y = ceil(round(($y / $tilescale) + ($TileCount / 2),1));
    
    return [
        'x' => $x,
        'y' => $y,
    ];
}
```

And finally, to translate an in game X/Y to a `levels.exd` position which can then be converted to a 2D or Long/Lat:

```
function mapsTranslateGameToXYZ($x, $y)
{
	$x = ($x*50)-25-1024;
	$y = ($y*50)-25-1024;
	
	return [
	    'x' => $x,
	    'y' => $y,
	];
}
```
