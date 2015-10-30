# FFXIV Libra
Libra is SE's official mobile application, it can be found here: http://eu.finalfantasyxiv.com/pr/special/libraeorzea/

# Downloading the App
The easiest way to download the app is through the android store. https://play.google.com/store/apps/details?id=com.square_enix.libra_eorzeaE

You can Google this easily "Download APK" etc. Or you can get browser extensions to do it if you have an Android phone.

Note that even if you download the APK when SE "update it", it may not have the latest database version, the application itself when downloaded from the store only contains the last SQLite database from when the application was fully updated. If SE update the SQLite database but not the actual applications code, then it does not get updated on the store.

So in order to always have the most up to date SQLite file, you need a rooted android where you can view the installation of Libra and you can get the SQLite file from there.

# Extracting

- Once you download the APK, just unzip it. It's just a zip file, any program can do it!
- Database File: `assets/database/app_data.sqlite.zip`, extract to get an `app_data.sqlite` file.
- Images: `res/drawable-xhdpi-v4/`, nice big icons!
- Translations: `assets/strings/strings.zip` great if you want to translate your website. Some useful termonology.

# Viewing the code

There is a `classes.dex` file which can be decompiled. https://github.com/pxb1988/dex2jar worked for me, have fun with the Source Code, its not pretty =X

# Viewing the data

You can use a tool such as SQLLiteBrowser to view the data, but it will not output the SQLite file correctly. I've included some PHP code which can be used to connect to the SQLite file and you can then do normal SQL queries on the file.