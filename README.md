# Simple Laravel Package - Transaction fee calculator - By MSM

[![Latest Version on Packagist](https://img.shields.io/packagist/v/masmaleki/calculator.svg?style=flat-square)](https://packagist.org/packages/masmaleki/calculator)
[![Total Downloads](https://img.shields.io/packagist/dt/masmaleki/calculator.svg?style=flat-square)](https://packagist.org/packages/masmaleki/calculator)
![GitHub Actions](https://github.com/masmaleki/calculator/actions/workflows/main.yml/badge.svg)

This package is just for using in an internal project, and it's not use full for all projects,but you can use it and extend it based on your needs.

## Installation

You can install the package via composer:

```bash
composer require masmaleki/calculator
```

## Usage
After installing the package with composer, then you need to run your laravel application:
```php
php artisan serv
```
Then call this url in your browser:
```php
http://127:0.0.1:8000/calculator
```

## Publish Resources
If you want to modify the config file for rates or Rate API url or view and asset files you need to run the below commands:

```php
php artisan vendor:publish --tag=Calculator-Assets
php artisan vendor:publish --tag=Calculator-Config
php artisan vendor:publish --tag=Calculator-Views
```
## Config
There is pre-defined values in the config file which you can  publish the config file and find it in /config/calculator.php or assign the values in your .env file 
```php
    'currencies' => [

        'EUR' => 1,
        'USD' => 1.1497,
        'JPY' => 129.53

    ],

    'commission_fees' => [

        'private' => [
            'deposit'=> 0.0003,
            'withdraw'=> 0.003
        ],
        'business' => [
            'deposit'=> 0.0003,
            'withdraw'=> 0.005
        ]

    ],

    'rate_url' => env('CALC_RATE_URL', 'https://developers.paysera.com/tasks/api/currency-exchange-rates'),


    /*
    |--------------------------------------------------------------------------
    | Calculator App Mode
    |--------------------------------------------------------------------------
    |
    | All Calculations goes with the test rates and test input file, if using the App test mode
    |
    | Supported: "live", "test"
    |
    */
    'mode' => env('CALC_MODE', 'live'),

    'limit' => env('CALC_limit', 1000),
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

If you discover any security related issues, please email masmaleki@gmail.com instead of using the issue tracker.

## Credits

-   [MohammadSadegh Maleki](https://github.com/masmaleki)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
