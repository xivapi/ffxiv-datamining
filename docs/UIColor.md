# UI Color

### Update 3rd October, 2018

It is believed that:
- 72 = Foreground
- 73 = Glow/background


In Patch 4.4, SE changed how colours work in tooltips in preperation of new UI "Skins". The logic for displaying colours changed quite drastically but was figured out quickly, here is the process and how it works now:

## (ID: 174) Bane tooltip

![](https://cdn.discordapp.com/attachments/474519195963490305/491551208989917187/unknown.png)

**Old tooltip logic**
```
Spread a target's <Color(-154)>Bio</Color> and <Color(-154)>Miasma</Color> 
effects (except <Color(-154)>Miasma II</Color>) to nearby enemies.
```

This is now:
```
Spread a target's <72>F201FA</72><73>F201FB</73>Bio<73>01</73><72>01</72> 
and <72>F201FA</72><73>F201FB</73>Miasma<73>01</73><72>01</72> effects 
(except <72>F201FA</72><73>F201FB</73>Miasma II<73>01</73><72>01</72>) to nearby enemies.
```

And this is how it should be when converted:
```
Spread a target's <span style="color:#ffff66;">Bio</span> and 
<span style="color:#ffff66;">Miasma</span> effects (except 
<span style="color:#ffff66;">Miasma II</span>) to nearby enemies. 
```

Taking a small snippet:

- Before: `<span style="color:#ffff66;">Bio</span>`
- After: `<72>F201FA</72><73>F201FB</73>Bio<73>01</73><72>01</72>`

> At this time we're not sure what 73 is for.

Focusing on these values, **we need to remove the first byte: F2**

- `<72>01FA</72><73>01FB</73>Bio<73>01</73><72>01</72>`

**After that, convert the HEX values to DEC:**

- `<72>506</72><73>507</73>Bio<73>01</73><72>01</72>`

In Patch 4.4 a new file was added: [UIColor.csv](https://github.com/viion/ffxiv-datamining/blob/master/csv/UIColor.csv)

If our guess of Foreground/Background is correct, then in `UIColor`: Col 1 = Foreground and Col 2 = Background, grabbing the rows related to our decimal values and the correct column we get:

- `<72>4294928127</72><73>4294174719</73>Bio<73>01</73><72>01</72>`

**And we can convert these values from DEC to HEX:**

- `<72>FFFF66FF</72><73>FFF3E7FF</73>Bio<73>01</73><72>01</72>`

The hex can contain the alpha transparency, so if the value is a 8 character hex, **we only care about the first 6.**

- `<72>FFFF66</72><73>FFF3E7</73>Bio<73>01</73><72>01</72>`

The 01 are always closing colours (resetting back to default text colors) so we can just replace those with generic closing tags and replace the opening tags to be HTML-like:

- `<72 = FFFF66><73 = FFF3E7>Bio</73></72>`

And now it's an easy convert to html:

- `<span style="color:#FFFF66;"><span style="color:#FFF3E7;">Bio</span></span>`

Remove 73 as it doesn't seem to do much right now:

- Before: `<span style="color:#ffff66;">Bio</span>`
- After: `<span style="color:#FFFF66;">Bio</span>`

*And there we have it!*

---

Some testing html:

```
<main>
  <div>
    default <span style="color:#FFFF66;"><span style="color:#FFF3E7;">Bio</span></span>
  </div>

  <div>
    No 73 (correct look) <span style="color:#FFFF66;">Bio</span>
  </div>

  <div>
    No 72 <span style="color:#FFF3E7;">Bio</span>
  </div>
  
  <div>
    72 = fore, 73 = back <span style="color:#FFFF66;"><span style="background-color:#FFF3E7;">Bio</span></span>
  </div>
  
  <div>
    72 = fore, 73 = glow <span style="color:#FFFF66;"><span style="text-shadow: 0 0 3px #FFF3E7;">Bio</span></span>
  </div>
</main>
```

---

Test Data

```
Spreads a target's <72>F201FA</72>Bio<72>01</72> and <72>F201FA</72>Miasma<72>01</72> effects to nearby enemies.</If><Else/>Spreads a target's <72>F201FA</72>Bio<72>01</72> and <72>F201FA</72>Miasma<72>01</72> effects to nearby enemies.</If> Potency is reduced by 20% for the second enemy, 40% for the third, 60% for the fourth, and 80% for all remaining enemies. <72>F201F8</72>Duration:<72>01</72> Time remaining on original effect <72>F201F8</72>Additional Effect:<72>01</72> 15% chance that <72>F201FA</72>Bio<72>01</72> and <72>F201FA</72>Miasma<72>01</72> duration resets if shorter than original effect duration <If(GreaterThanOrEqualTo(PlayerParameter(72),58))><If(Equal(PlayerParameter(68),27))><72>F201F8</72>Additional Effect:<72>01</72> <72>F201FA</72>Aethertrail Attunement<72>01</72> <Else/></If><Else/></If><If(GreaterThanOrEqualTo(PlayerParameter(72),70))><If(Equal(PlayerParameter(68),28))><72>F201F8</72>Additional Effect:<72>01</72> Increases <72>F201F4</72>Faerie Gauge<72>01</72> by 10 <Else/></If><Else/></If><72>F201F8</72>Aetherflow Gauge Cost:<72>01</72> 1

Spread a target's <Color(-154)>Bio</Color> and <Color(-154)>Miasma</Color> effects (except <Color(-154)>Miasma II</Color>) to nearby enemies.<Else/>Spreads a target's <Color(-154)>Bio</Color> and <Color(-154)>Miasma</Color> effects to nearby enemies.</If><Else/>Spreads a target's <Color(-154)>Bio</Color> and <Color(-154)>Miasma</Color> effects to nearby enemies.</If>
Potency is reduced by 20% for the second enemy, 40% for the third, 60% for the fourth, and 80% for all remaining enemies.
<Color(52258)>Duration:</Color> Time remaining on original effect
<Color(52258)>Additional Effect:</Color> 15% chance that <Color(-154)>Bio</Color> and <Color(-154)>Miasma</Color> duration resets if shorter than original effect duration
<If(GreaterThanOrEqualTo(PlayerParameter(72),58))><If(Equal(PlayerParameter(68),27))><Color(52258)>Additional Effect:</Color> <Color(-154)>Aethertrail Attunement</Color>
<Else/></If><Else/></If><If(GreaterThanOrEqualTo(PlayerParameter(72),70))><If(Equal(PlayerParameter(68),28))><Color(52258)>Additional Effect:</Color> Increases <Color(-34022)>Faerie Gauge</Color> by 10
<Else/></If><Else/></If><Color(52258)>Aetherflow Gauge Cost:</Color> 1

Spread a target's <span style="color:#ffff66;">Bio</span> and <span style="color:#ffff66;">Miasma</span> effects (except <span style="color:#ffff66;">Miasma II</span>) to nearby enemies. Potency is reduced by 20% for the second enemy, 40% for the third, 60% for the fourth, and 80% for all remaining enemies.n<span style="color:#00cc22;">Duration:</span> Time remaining on original effectn<span style="color:#00cc22;">Additional Effect:</span> 15% chance that <span style="color:#ffff66;">Bio</span> and <span style="color:#ffff66;">Miasma</span> duration resets if shorter than original effect duration <span style="color:#00cc22;">Additional Effect:</span> <span style="color:#ffff66;">Aethertrail Attunement</span> <span style="color:#00cc22;">Additional Effect:</span> Increases <span style="color:#ff7b1a;">Faerie Gauge</span> by 10 <span style="color:#00cc22;">Aetherflow Gauge Cost:</span> 1
```
