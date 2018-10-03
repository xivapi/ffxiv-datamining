# Item Stats

Most of these are kind of obvious, but will add here for safe keeping!

# Attack speed

Equation: `(attack speed / 1000 `

# Auto Attack

Equation: `(damage / 3 * attack speed)`

Remember to use HQ damage when determining Auto-Attack for HQ items.

# DPS

Equation: `(damage / 3)`

# Finding HQ values

HQ values are just the difference, the "additional number", if an item is Accuracy: 32, and HQ Accuracy: 35, the HQ value is listed as 3.

An example can be found at offsets: [34, 50, 75 = value, param, hq](https://github.com/viion/XIV-Datamining/blob/master/offsets/Items/items_csv_xivdb_unverified.txt#L15-L17)

# Max meld values

```csharp
class ItemLevel {
    public int GetMaximum(BaseParam baseParam) {
        const int Offset = -1;
        return baseParam.Key == 0 ? 0 : Convert.ToInt32(this[Offset + baseParam.Key]);
    }
}

class BaseParam {
    public int GetMaximum(EquipSlotCategory category) {
        const int Offset = 2;
        return category.Key == 0 ? 0 : Convert.ToInt32(this[Offset + category.Key]);
    }

    public int GetModifier(int role) {
        const int Offset = 24;
        const int Maximum = 12;
        if (role < 0 || role > Maximum)
            return 0;
        return Convert.ToInt32(this[Offset + role]);
    }
}

class Equipment {
    public int GetMaximumParamValue(BaseParam baseParam)
        // Base value for the param based on the item's level
        var maxBase = ItemLevel.GetMaximum(baseParam);
        // Factor, in percent, for the param when applied to the item's equip slot
        var slotFactor = baseParam.GetMaximum(EquipSlotCategory);
        // Factor, in percent, for the param when used for the item's role
        var roleModifier = baseParam.GetModifier(BaseParamModifier);
    
        // Rounding appears to use AwayFromZero.  Tested with:
        // Velveteen Work Gloves (#3601) for gathering (34.5 -> 35)
        // Gryphonskin Ring (#4526) for wind resistance (4.5 -> 5)
        // Fingerless Goatskin Gloves of Gathering (#3578) for GP (2.5 -> 3)
        return (int)Math.Round(maxBase * slotFactor * roleModifier / 10000.0, MidpointRounding.AwayFromZero);
    }
}
```

# Overall Item Level

This one is really for gearset applications. 

Item level is `*2` when involving two handed weapons, this is to compensate for off hand. You can test this by equipping only a Sword in game, then equip your Shield of the same item level and record the two values. Then equip a two handed weapon, your character will be the same overal item level as a Sword+Shield.

# Category to Slot ID
```
// slot id => cat id
[
    // main hand
    738 => [
        1,2,3,4,5,6,7,8,9,10,84,87,88,89,
        12,14,16,18,20,22,24,26,28,30,32,
    ],

    // off hand
    739 => [
        13,15,17,10,21,23,25,27,29,31,11
    ],

    // head, body, hands, waist, legs, feet, ears, neck, wrists, ring
    740 => [34],
    741 => [35],
    742 => [37],
    743 => [39],
    744 => [36],
    745 => [38],
    746 => [41],
    747 => [40],
    748 => [42],
    748 => [43],
];
```
