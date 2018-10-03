# Collectible Reward Info

MasterpieceSupplyDuty is where all of the Collectable information is kept. Unknown how scrips are calculated for base value,
but Bonus / High Bonus is calculated very easily, for both EXP and Scrips.

(rounded down) Base * 1.1 = Bonus
(rounded down) Base * 1.2 = High Bonus

Items which are 'in demand' (a yellow star next to their name in the turn-in window) are multipled by 20% as well (*1.2),
so if an item is in demand then its base value is what the 'High Bonus' is normally, while Bonus/High Bonus are multiplied by
1.1 and 1.2!

Example:

Base Item: Level 57 Hardsilver Hatchet. 46 Blue Scrips, 119808 EXP.
Bonus Scrips Value (46*1.1) =  50.6    (round down to 50).
High Scrips Value  (46*1.2) =  55.2   (round down to 55).
Bonus EXP Value (119808*1.1)= 131788.8 (rounded down to 131788).
High EXP Value  (119808*1.2)= 143769.6 (rounded down to 143769).

If Hardsilver Hatchet were in demand, then the base value would change:
Scrips: 55
EXP   : 143769
Bonus Scrips: (55*1.1 round down) = 60
High Scrips:  (55*1.2 round down) = 66
Bonus EXP   : (143769*1.1 round down) = 158145
High EXP    : (143769*1.2 round down) = 172522

Collectability ranges are in the MasterpieceSupplyDuty file, so all that's needed is to figure out how scrips and exp are
actually calculated. EXP might be set in stone somewhere, as I've noticed all of the CRAFTING Collectables have the same EXP
rewards across all classes for the exact same level (assuming an item isn't in demand, of course. But if an item is in demand,
you can divide it by 1.2 and round up to get the info!) Gathering collectables I'd presume share a similar EXP systme, but I
haven't mapped out those values yet.

Level    = EXP
------------------
Level 51 = 52920
Level 52 = 63360
Level 53 = 77760
Level 54 = 93600
Level 55 = 110880
Level 56 = 103680
Level 57 = 119808
Level 58 = 164505
Level 59 = 155520
Level 60 = 175564
