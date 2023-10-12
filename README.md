# Geoloupix API

## Presentation

Geoloupix is an app to share and save your favorites locations with a click. The project is devided into two parts, the [app itself](https://github.com/geoloupix/app) and the api on witch the app is pulling its ressources from (what you have here). This repository shows everything that is on [our webserver](https://geoloupix.fr). Each commit is instantly sent to our server using Github Actions.
Feel free to join our discord server : [https://discord.gg/37qccrP9eX](https://discord.gg/37qccrP9eX)

## Technologies

This side of the project (API) is using PHP ``v8.0.2`` and the [Laravel framework](https://laravel.com/) ``v9.6.0``. Check out this [repository](https://github.com/geoloupix/app) for the frontend (App)

## Use the API

The API is public and can be accessed here : [https://geoloupix.fr/api](https://geoloupix.fr/api)

But you can also just clone the source code, setup the ``.env`` file

Documentation is available here : [https://developers.geoloupix.fr](https://developers.geoloupix.fr)

## Files explanation

### Packages 

You can find a bit of my code everywhere, if you want to know where is what, you can take a look to the file tree

To check all packages, a list is available under ``./composer.json``

### File tree

As we know, not everyone is familiar with laravel nor the way I (Marc) decided to use it for this project so I'll create a file tree
If you see a file in the project not listed here, it means we don't really created it nor use it. Everything needed to understand what we've done so far is documented bellow

    /.github
        /workflows/ => Where we store the action that upload modifications to the webserver
    /app
        /Console
        /Exceptions
        /Http
            /Controllers => Folder where all the logic is
                /API => Folder where all API controllers are
                    /FolderController.php => All logic responsable of Folders
                    /LocationController.php => All logic reponsable of Locations
                    /UserController.php => All logic responsable of Users
            /Middleware => Folder where store the modules that intercept the request and verify some parameters
                /EnsureAllRequiredParams.php => Middleware used to reject requests if it doesn't have all parameters required
                /EnsureTokenIsValid.php => Middleware used to reject requests if there is no token or if it's invalid (when we need it)
        /Models => Where we store our objects class
            /Folder.php => Folder class
            /Location.php => Locaiton class
            /Share.php => Share class
            /Token.php => Token class
            /User.php => User class
        /Providers
    /bootstrap
    /config => All config files
    /database
        /factories => Folder where we stored all "fatories" (is a file to create a "fake" item)
            /FolderFactory.php => Example: In this file we wrote what's needed to create a "fake" folder
        /migrations => Files specifying how each database table should be created
        /seeders => File executed to seed the database with all the factories
    /lang
    /public
    /ressources => Folder storing all public ressources (css/js/html)
        /css
        /js
        /views
    /routes => Where all URL is defined
        /api.php => All URLs for the API (https://geoloupix.fr/api/**)
        /web.php => All "public" URLs (https://geoloupix/**)
    /storage
    /tests
    .env.example => Examble of the file where we have all our environement variables (passwords/usernames/sensitive stuff)

### Roadmap

As you might know, for this API I made a roadmap to let others see where I was and what as working

So here is a list off all the routes arable and what they (should) do

- [x] /register [POST] - Create a new user and gives a token
- [x] /login [POST] - Login an existing user and gives a token
- [ ] /password-reset [POST] - Send a link to reset an existing user's password
- [ ] /user
  - [x] [GET] - Get information back about an existing user from a token
  - [x] [PATCH] - Modify an existing user data
  - [ ] [DELETE] - Delete an existing user
- [ ] /location
  - [x] [GET] - Get information about all or one specific location
  - [x] [POST] - Create a new location
  - [ ] [PATCH] - Modify an existing location
  - [x] [DELETE] - Delete an existing location
- [ ] /folder
  - [x] [GET] - Get all content from a folder
  - [x] [POST] - Create a new folder
  - [ ] [PATCH] - Modify folder name ?
  - [ ] [DELETE] - Delete a folder
- [ ] /share
  - [ ] [GET] - Get all shared locations
  - [ ] [POST] - Share a location with an user
  - [ ] [DELETE] - An user to a shared lccation
- [ ] /tokens
  - [ ] [GET] - Get all INFORMATIONS about all the tokens of an user 
  - [ ] [DELETE] - Revoke ALL tokens 
