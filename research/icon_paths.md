# FFXIV Icon Paths

The path for icons are very funky, depending on the icon number you need to format the path very specifically, here is a function that does it in PHP which should be easy to translate into other languages:

```php
// Is the number 6 or more characters?
$extended = (strlen($number) >= 6);

// get icon based on number size, prepend 0 if its below 6 characters.
$icon = $extended 
  ? str_pad($number, 5, "0", STR_PAD_LEFT) 
  : '0' . str_pad($number, 5, "0", STR_PAD_LEFT);
  
// build path, take values [0, 1, 2] from the number, add 000 for extended
// for non extended start with 0 and add [1,2] + 000 for path.
$path = $extended 
  ? $icon[0] . $icon[1] . $icon[2] .'000' 
  : '0'. $icon[1] . $icon[2] .'000';
```

