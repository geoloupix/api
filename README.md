# Geoloupix API

## Presentation

Geoloupix is an app to share and save your favorites locations with a click. The project is devided into two parts, the [app itself](https://github.com/geoloupix/app) and the api on witch the app is pulling its ressources from (what you have here)
This repository show everything that is on [our webserver](https://geoloupix). Each commit is instantly sent to our server.

## Files

As we know, not everyone is familliar with laravel nor the way I (Marc) decided to use it for this project so I'll create a file tree
If you see a file in the project not listed here, it means we don't really created it nor use it. Everything needed to understand what we've done so far is documented bellow

    /github
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
