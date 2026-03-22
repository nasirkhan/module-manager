{
    "name": "{{composerVendor}}/{{moduleNameLower}}",
    "description": "",
    "type": "laravel-module",
    "authors": [
        {
            "name": "{{composerAuthor}}",
            "email": "{{composerAuthorEmail}}"
        }
    ],
    "require": {
        "php": "^8.3",
        "laravel/framework": "^12.0 || ^13.0"
    },
    "extra": {
        "laravel": {
            "providers": [],
            "aliases": {

            }
        }
    },
    "autoload": {
        "psr-4": {
            "{{namespace}}\\{{moduleName}}\\": ""
        }
    }
}
