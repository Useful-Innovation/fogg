# Fogg

## Setup
Add the [GoBrave repository](http://satis.goingbrave.se) to your `composer.json` file and add `gobrave/fogg` you the `require` section and run `composer update`.

Add to `config.json` in the `wp_cli` block

    "fogg" : "GoBrave\\Fogg\\Util\\WPCLI"

Run `$ wp fogg config` and copy the output to `app/functions.php` **above** the `new App\App(...)` part

Run `$ wp fogg setup` to setup folders in your root path (from the config)


## Commands

Look in `GoBrave/Servant/Util/WPCLI` for more info


## Routes

### Admin

Admin routes are defined as resources. Example:

    {
      "resource" : "fogg",
      "plural"   : "Fogg",
      "singular" : "Fogg",
      "prefix"   : "Ny"
    }

This will look for a controller named Admin/FoggsController and use the other values to print a basic template of CRUD operations.

### Public

Public routes are defined by a path and then specifying the details of that path. Example:

    "/foggs/{some_id}" : {
      "name"       : "foggs",
      "type"       : "GET",
      "controller" : "FoggssController",
      "method"     : "foggs"
    }

When the user visits domain.com/foggs Fogg will look for a `FoggsController`, run the method `foggs` and look for a template called `templates/fogg/foggs.html.php`. The value of `some_id` will be passed to the method. 
