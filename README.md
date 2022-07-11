# Simple Laravel Package - Transaction fee calculator - By MSM

[![Latest Version on Packagist](https://img.shields.io/packagist/v/masmaleki/calculator.svg?style=flat-square)](https://packagist.org/packages/masmaleki/calculator)
[![Total Downloads](https://img.shields.io/packagist/dt/masmaleki/calculator.svg?style=flat-square)](https://packagist.org/packages/masmaleki/calculator)

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
Then you should see this:
![image](https://user-images.githubusercontent.com/5430351/178139960-4f046c5f-c843-4421-b00f-c15fdb5cef23.png)

After submiting the form you will see the table of trasaction with the calculated commissions based on your rules
![image](https://user-images.githubusercontent.com/5430351/178139878-2c62cf25-4034-404b-b137-9eabb7270908.png)

## Publish Resources
If you want to modify the config file for rates or Rate API url or view and asset files you need to run the below commands:

```bash
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

    'limit' => env('CALC_limit', 1000),
```
### Testing
For testing first you should publish the package test then run the laravel test command. follow this :
```bash
php artisan vendor:publish --tag=Calculator-Tests
php artisan test
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
