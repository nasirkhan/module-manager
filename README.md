# Module Manager & Generator for [Laravel Starter](https://github.com/nasirkhan/laravel-starter)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/nasirkhan/module-manager.svg?style=flat-square)](https://packagist.org/packages/nasirkhan/module-manager)


This is where your description should go. Try and limit it to a paragraph or two, and maybe throw in a mention of what PSRs you support to avoid any confusion with users and contributors.

## Installation

You can install the package via composer:

```bash
composer require nasirkhan/module-manager
```

After installig the package, you need to publish the config file and the module stub files. You can do this by running the following command:

```php
php artisan vendor:publish --tag=module-manager
```


## Usage

To create a project use the following command, you have repalce the `MODULE_NAME` with the name of the module. 

```php
php artisan module:build MODULE_NAME
```

You may want to use ` --force` option to overwrite the existing module. if you use this option, it will replace all the exisitng files with the defalut stub files.


```php
php artisan module:build MODULE_NAME --force
```

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email nasir8891@gmail.com instead of using the issue tracker.

## Credits

-   [Nasir Khan](https://github.com/nasirkhan)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Starter Boilerplate

[Laravel Starter](https://github.com/nasirkhan/laravel-starter) is a CMS like modular starter application project built with Laravel 9.x. 

