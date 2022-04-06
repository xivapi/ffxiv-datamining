# Conversions to 2D Map

### Conversion of world coordinates to a 2D map texture coordinate (in pixels):

```csharp
public static Vector2 GetPixelCoordinates( Vector2 worldXZCoordinates, Vector2 mapOffset, UInt16 mapSizeFactor )
{
    return ( worldXZCoordinates + mapOffset ) / 100f * mapSizeFactor + new Vector2( 1024f );
}
```
This assumes using the normal map textures that are 2048x2048.  If you're using the small (1024x1024) map textures, divide the result by two.

### Conversion of map texture pixel coordinates (such as FishingSpot coordinates) to in-game 2D map coordinates:
```csharp
public static Vector2 GetGameMapCoordinates( Vector2 mapPixelCoordinates, UInt16 mapSizeFactor )
{
    return mapPixelCoordinates / mapSizeFactor * 2f + Vector2.One;
}
```

### Conversion of world coordinates to in-game 2D map coordinates (using the above functions):
```csharp
public static Vector2 WorldToMapCoordinates( Vector2 worldXZCoordinates, Vector2 mapOffset, UInt16 mapSizeFactor )
{
    return GetGameMapCoordinates( GetPixelCoordinates( worldXZCoordinates, mapOffset, mapSizeFactor ), mapSizeFactor );
}
```
The game *truncates* the result of this to the first decimal place.

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
