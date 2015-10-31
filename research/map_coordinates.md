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