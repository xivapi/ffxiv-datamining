Quest experience points are generated using a small equation. Why this is done I am not sure as the values always seem static.

```js
(base * paramgrow * (50 + 5 * level)) / 100;
```

The `base` value can be found in the quest itself, offset: `reward_exp_bonus` [https://github.com/viion/XIV-Datamining/blob/master/exd-xivdb-offsets/Quest/quest_csv_xivdb.txt#L3](found here)

The `paramgrow` value is found in the ParamGrow file! offset: `exp_mod` [https://github.com/viion/XIV-Datamining/blob/master/exd-xivdb-offsets/ParamGrow/param_grow_csv_xivdb.txt#L4](found here)

The `level` is the level of the quest, offset `class_level` [https://github.com/viion/XIV-Datamining/blob/master/exd-xivdb-offsets/Quest/quest_csv_xivdb.txt#L10](found here)