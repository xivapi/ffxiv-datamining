Achievements link to a "pre-requisite type" that defines it as completeed. The list of Types are:

```
0 legacy
1 kill stuff
2 achievement
3 class
4 materia
5 various adventure activities, must complete all the requirements in data_0-7
6 pre quest // all quests must be complete
7 hunting log
8 discover
9 pre quest // any 1 quest must be complete
10 companion
11 grand company
12 pvp
13 pvp
14 trial
15 beast tribe
16 ???
17 frontline
18 frontline
19 frontline
20 aether attune
```

Up to date mapping by: Tequila Mockingbird

```js
switch (achievement.type) {
	case 4: // affix n materia to the same piece of gear
	case 5: // complete all n of these requirement_2 - 9 things
	case 10: // level your companion chocobo to rank n
	case 12: // participate in n matches in The Fold
	case 13: // triumph in n matches in The Fold
	case 17: // participate in n Frontline matches
	case 19: // triumph in n Frontline matches
		return achievement.requirement_1;
	case 1: // do n things
	case 3: // achieve n levels as class
	case 11: // achieve PVP rank n with a specific Grand Company
	case 15: // achieve rank n with a specific Beast Tribe
	case 18: // guide a specific Grand Company to n Frontline victories
	case 21: // obtain n minions
		return achievement.requirement_2;
	case 0: // complete specific Legacy thing (some stil relevant)
	case 6: // complete a specific quest
	case 7: // complete all specific hunting log entries
	case 8: // discover every location within...
	case 9: // complete any of these requirement_2 - 9 quests (?)
	case 14: // complete a specific Trial
	case 20: // attune to all aether currents in a specific area
	case 23: // complete all Verminion challenges
	case 24: // obtain a variety of anima weapon
		return 1;
	case 2: // complete n other achievements
		return 0; // the other achievements have their own weights and this is automatic
	case 16: // there is no type 16
	case 22: // there is no type 22
	default:
		return null;
}
 ```

