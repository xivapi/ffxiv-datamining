# Quest Exp Reward

Quest experience points are generated using a small equation.

Why this is done I am not sure as the values always seem static.

### Formula updated: 28th May, 2018


**CORE FORMULA**
> `EXP = Quest.ExpFactor * ParamGrow.QuestExpModifier * (45 + (5 * Quest.ClassJobLevel_0)) / 100`

All formula's use this as their starting point and ADD onto it.

**QUEST LEVEL 50**
> `EXP = CORE + ((400 * (Quest.ExpFactor / 100)) + ((Quest.ClassJobLevel_0-52) * (400 * (Quest.ExpFactor/100))))`

**QUEST LEVEL 51**
> `EXP = CORE + ((800 * (Quest.ExpFactor / 100)) + ((Quest.ClassJobLevel_0-52) * (800 * (Quest.ExpFactor/100))))`

**QUEST LEVEL 52-59**
> `EXP = CORE + ((2000 * (Quest.ExpFactor / 100)) + ((Quest.ClassJobLevel_0-52) * (2000 * (Quest.ExpFactor/100))))`

**QUEST LEVEL 60-69**
> `EXP =CORE + ((37125 * (Quest.ExpFactor / 100)) + ((Quest.ClassJobLevel_0-60) * (3375 * (Quest.ExpFactor/100))))`


-----

#### Formula in PHP:

This is example code, it requires getting the `quest` and `paramGrow` prior

```php
$quest = $service->get("Quest_<id>");
$paramGrow  = $service->get("xiv_ParamGrow_{$quest->ClassJobLevel_0}");

// CORE = Quest.ExpFactor * ParamGrow.QuestExpModifier * (45 + (5 * Quest.ClassJobLevel_0)) / 100
$EXP = $quest->ExpFactor * $paramGrow->QuestExpModifier * (45 + (5 * $quest->ClassJobLevel_0)) / 100;

// CORE + ((400 * (Quest.ExpFactor / 100)) + ((Quest.ClassJobLevel_0-52) * (400 * (Quest.ExpFactor/100))))
if (in_array($quest->ClassJobLevel_0, [50])) {
    $EXP = $EXP + ((400 * ($quest->ExpFactor / 100)) + (($quest->ClassJobLevel_0 - 50) * (400 * ($quest->ExpFactor / 100))));
}

// CORE + ((800 * (Quest.ExpFactor / 100)) + ((Quest.ClassJobLevel_0-52) * (800 * (Quest.ExpFactor/100))))
else if (in_array($quest->ClassJobLevel_0, [51])) {
    $EXP = $EXP + ((800 * ($quest->ExpFactor / 100)) + (($quest->ClassJobLevel_0 - 50) * (400 * ($quest->ExpFactor / 100))));
}

// CORE + ((2000 * (Quest.ExpFactor / 100)) + ((Quest.ClassJobLevel_0-52) * (2000 * (Quest.ExpFactor/100))))
else if (in_array($quest->ClassJobLevel_0, [52,53,54,55,56,57,58,59])) {
    $EXP = $EXP + ((2000  * ($quest->ExpFactor / 100)) + (($quest->ClassJobLevel_0 - 52) * (2000  * ($quest->ExpFactor / 100))));
}

// CORE + ((37125 * (Quest.ExpFactor / 100)) + ((Quest.ClassJobLevel_0-60) * (3375 * (Quest.ExpFactor/100))))
else if (in_array($quest->ClassJobLevel_0, [60,61,62,63,64,65,66,67,68,69])) {
    $EXP = $EXP + ((37125  * ($quest->ExpFactor / 100)) + (($quest->ClassJobLevel_0 - 60) * (3375  * ($quest->ExpFactor / 100))));
}

// Set
$quest->ExperiencePoints = $EXP;
```
