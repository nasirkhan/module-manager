# Module Manager & Generator for [Laravel Starter](https://github.com/nasirkhan/laravel-starter)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/nasirkhan/module-manager.svg?style=flat-square)](https://packagist.org/packages/nasirkhan/module-manager)

**Module Manager** is used to manage and generate `Module` for the ***Laravel Starter***. [Laravel Starter](https://github.com/nasirkhan/laravel-starter) is a CMS-like modular starter boilerplate application project, built with the latest release of Laravel. This package is preinstalled with the Laravel Starter. 

| **Laravel** | **module-manager** |
|-------------|---------------------|
| 11.0        | ^2.0                |
| 10.0        | ^1.0                |


## Installation

You can install the package via Composer:

```bash
composer require nasirkhan/module-manager
```

After installing the package, you need to publish the config file and the module stub files. You can do this by running the following command:

```php
php artisan vendor:publish --tag=module-manager
```


## Usage

To create a project use the following command, you have to replace the `MODULE_NAME` with the name of the module. 

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

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security-related issues, please email nasir8891@gmail.com instead of using the issue tracker.

## Credits

-   [Nasir Khan](https://github.com/nasirkhan)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please have a look at [License File](LICENSE.md) for more information. 

