Quest experience points are generated using a small equation. Why this is done I am not sure as the values always seem static.

```js
(base * paramgrow * (45 + 5 * level)) / 100;
```

The `base` value can be found in the quest itself, offset: `reward_exp_bonus` [https://github.com/viion/XIV-Datamining/blob/master/exd-xivdb-offsets/Quest/quest_csv_xivdb.txt](found here)

The `paramgrow` value is found in the ParamGrow file! offset: `exp_mod` [https://github.com/viion/XIV-Datamining/blob/master/exd-xivdb-offsets/ParamGrow/param_grow_csv_xivdb.txt](found here)

The `level` is the level of the quest, offset `class_level_1` (main class) [https://github.com/viion/XIV-Datamining/blob/master/exd-xivdb-offsets/Quest/quest_csv_xivdb.txt#L10](found here)

If a quest is a bove 50, it has an additional 10k exp per 100th base param.

So after the question, you would do:

```js
exp + (base / 100) * 10000;
```

Not fully confirmed this, but it has worked for a few 50+ quests I tested against.
