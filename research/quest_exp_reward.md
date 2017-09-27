Quest experience points are generated using a small equation. Why this is done I am not sure as the values always seem static.

### New findings as of 27 September 2017:

```
(BASE is also known as EXP_FACTOR in godbert)


LEVEL 1-49 use "Core Formula"
	BASE * PARAMGROW * (45 + (5 * LEVEL)) / 100

LEVEL 50-51 use "Core Formula" + "Secondary Formula with a 800 modifier  and a starting EXP of 800"
	CORE + ((800 * (BASE / 100)) + ((LEVEL-52) * (800 * (BASE/100))))

LEVEL 52-59 use "Core Formula" + "Secondary Formula with a 2000 modifier  and a starting EXP of 2000"
	CORE + ((2000 * (BASE / 100)) + ((LEVEL-52) * (2000 * (BASE/100))))

LEVEL 60-69 use "Core Formula" + "Secondary Formula with a 2000 modifier and a starting EXP of 37125"
	CORE + ((37125 * (BASE / 100)) + ((LEVEL-60) * (3375 * (BASE/100))))
```


### Note:

For Quests level 50 or above, an additional formula is done, findings:

https://docs.google.com/spreadsheets/d/15h13UbfqhxkD9BoQJkLvR66fnZRq6Wn6YxEMRXO9VE4/edit#gid=0

```js
// pre level 50
(exp_factor * param_grow * (45 + 5 * quest_level)) / 100;

// If quest is 50 or above
(exp_factor * param_grow * (45 + 5 * quest_level)) / 100 + (additive * (base / 100))
```

- The `exp_factor` value can be found in the **Quest** itself, offset: [found here](https://github.com/viion/XIV-Datamining/blob/master/offsets/3.1_list.txt#L756)
- The `param_grow` value is found in the **ParamGrow** file! offset: `quest_exp_modifier` [found here](https://github.com/viion/XIV-Datamining/blob/master/offsets/3.1_list.txt#L725)
- The `quest_level` is the level of the quest, found in the **Quest** itself, offset `class_level_1` (main class) [found here](https://github.com/viion/XIV-Datamining/blob/master/offsets/3.1_list.txt#L737)

If a quest is a bove 50, it has an additional 10k exp per 100th exp_factor value.

If the `exp_factor` is 100, you add 10000 exp, if its 200, you add 20000. etc.

So after the question, you would do:

```js
exp = (exp_factor * quest_exp_modifier * (45 + 5 * class_level_1)) / 100;

exp = exp + (exp_factor / 100) * 10000;
```

Not fully confirmed this, but it has worked for a few 50+ quests I tested against.
