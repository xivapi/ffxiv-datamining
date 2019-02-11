# Emote Log Messages

Attempting to debug the emote LogMessages

---

100	Standard Emotes	1	0	False

```
<Clickable(
	<If(Equal(ObjectParameter(1),ObjectParameter(2)))>
		you
	<Else/>
		<If(PlayerParameter(7))>
			<SheetEn(ObjStr,2,PlayerParameter(7),1,1)/>
		<Else/>
			ObjectParameter(2)
		</If>
	</If>)/>

	<If(Equal(ObjectParameter(1),ObjectParameter(2)))>
		look
	<Else/>
		looks
	</If>

	at

	<If(Equal(ObjectParameter(1),ObjectParameter(3)))>
		<If(PlayerParameter(8))>
			<SheetEn(ObjStr,2,PlayerParameter(8),1,1)/>
		<Else/>
			you
		</If>
	<Else/>
		<If(PlayerParameter(8))>
			<SheetEn(ObjStr,2,PlayerParameter(8),1,1)/>
		<Else/>
			ObjectParameter(3)
		</If>
	</If>

	in surprise.
```

```
Un-targeted
	Solo: [You] look surprised!
	Someone else: [Siyukan Virtue] looks surprised!

Targeted
	targeting other: [You] [look] at [the housing enthusiast] in surprise.
	player targeting you: [Siyukan Virtue] [looks] at [you] in surprise.
	player targeting other: [Siyukan Virtue] [looks] at [the housing enthusiast] in surprise.
```



JSON:

```json
"en": [
	"?? missing character choice ",
	{
		"condition": {
			"left_operand": "target",
			"operator": "==",
			"right_operand": "not_self"
		},
		"no": "looks",
		"yes": "look"
	},
	" at ",
	{
		"condition": {
			"left_operand": "target",
			"operator": "==",
			"right_operand": "self"
		},
		"no": [
			{
				"condition": "is_player",
				"no": "self",
				"yes": "<SheetEn(ObjStr,2,PlayerParameter(8),1,1)/>"
			}
		],
		"yes": [
			{
				"condition": "is_player",
				"no": "you",
				"yes": "<SheetEn(ObjStr,2,PlayerParameter(8),1,1)/>"
			}
		]
	},
	" in surprise."
],
```
