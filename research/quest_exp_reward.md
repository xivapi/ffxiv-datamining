Quest experience points are generated using a small equation. Why this is done I am not sure as the values always seem static.

### Note:

For Quests level 50 or above, an additional formula is done, findings:

https://docs.google.com/spreadsheets/d/15h13UbfqhxkD9BoQJkLvR66fnZRq6Wn6YxEMRXO9VE4/edit#gid=0

```js
// pre level 50
(exp_factor * quest_exp_modifier * (45 + 5 * class_level_1)) / 100;

// If quest is 50 or above
(exp_factor * quest_exp_modifier * (45 + 5 * class_level_1)) / 100 + (additive * (base / 100))
```

- The `exp_factor` value can be found in the **Quest** itself, offset: [found here](https://github.com/viion/XIV-Datamining/blob/master/offsets/3.1_list.txt#L756)
- The `quest_exp_modifier` value is found in the **ParamGrow** file! offset: `quest_exp_modifier` [found here](https://github.com/viion/XIV-Datamining/blob/master/offsets/3.1_list.txt#L725)
- The `class_level_1` is the level of the quest, found in the **Quest** itself, offset `class_level_1` (main class) [found here](https://github.com/viion/XIV-Datamining/blob/master/offsets/3.1_list.txt#L737)

If a quest is a bove 50, it has an additional 10k exp per 100th exp_factor value.

If the `exp_factor` is 100, you add 10000 exp, if its 200, you add 20000. etc.

So after the question, you would do:

```js
exp = (exp_factor * quest_exp_modifier * (45 + 5 * class_level_1)) / 100;

exp = exp + (exp_factor / 100) * 10000;
```

Not fully confirmed this, but it has worked for a few 50+ quests I tested against.
