# XIV-Datamining

This repository is to serve as a place to share data mining information related to Final Fantasy XIV. All findings and knowledge that each member discovers will be provided here for all in the FFXIV Community to read, learn and use.

### Documents of Knowledge

- [Click here to view Documents on various FFXIV systems](docs/README.md)

### Research and misc files

- [Click here to view various research and resource files](research/README.md)

### CSVs

This repository keeps a record of CSV's extracted via SaintCoinach using the command `rawexd`, this is so they can be easily linked and referenced when datamining, so we have a git history of changes and just to make life easier in some cases :)

- [Click here to view CSV files](csv/)

### Updating CSV files:

- Download and open **SaintCoinach.cmd**
- Run: `rawexd`
- Copy all the contents in the folder: `<date>/rawexd/**` to the `/csv` folder
- Push a PR

The idea is to maintain an easy diffing view of what has changed during a patch

It would also be very useful to keep a history of `ex.json` files from SaintCoinach for each patch as the Korean and Chinese versions are on different patches than the Official live client.

### whats the "ex" folder?

Ex contains an archive list of the [SaintCoinach ex.json](https://github.com/ufx/SaintCoinach/blob/master/SaintCoinach/ex.json) file, ths main point was to keep a `ex.json` file per Patch, then if you wanted to extract data from an older patch (such as Korean or Chinese clients) you can hot-swap the `ex.json` file and extract data in the correct format.

This kind of hot swap does not always work however as new structures are detected that are implemented into SaintCoinach, or you may have systems that expect data in a specific way (eg the great "Tooltip Descriptions Changes" of Patch 4.4)** ...

You can just grab an old version from git history, which I may switch to linking to instead of keeping copies.


### whats the "uld" folder?

It's an extract of FFXIV UI Elements (images) extracted from SaintCoinach.

---

### Becoming a dataminer

- Step 1. Extract CSV data
- Step 2. Find where bluemage is
- Step 3. Profit!

Getting started in FFXIV data mining is quite easy. If you enjoy digging around CSV database files then you've come to the right place! The best place to start would be:

- Download this repository and start looking at the files in the `/csv` folder
- Download **SaintCoinach** and use either the `SaintCoinach.Cmd` or `Godbert` tools.
	- Saint provides releases: https://github.com/ufx/SaintCoinach/releases

If you find a connection between files or an identification of data, throw up an issue on the repository and it will be sorted :)

- Connection between files could be an number in 1 column that matches the numbers in another column or even another file
- Identification of data could be values in a column that have a meaning and a pattern can be observed (eg ClassJob ID, or Craft Level)

### Huge thanks to:

Notable members that have contributed information in some way, If you're part of the datamining and FFXIV discovery community be sure to add your credit!

- Clorifex (GarlandTools)
- Hezkezl (Gamer Escape)
- Icarus Twine (Gamer Escape & Blue Mage overlord)
- Miu (TeamCraft)
- Ioncannon (FFXIV Explorer)
- Vekien (xivapi)
