# php-actuator

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

PHP port of Spring Actuator which provides an easy health checking capability of services.

## Install

Via Composer

``` bash
$ composer require postalservice14/php-actuator
```

## Parameters

* **health.indicators**: An array of indicators to be used. Key as indicator name, value as indicator object.
* **health.endpoint**: Endpoint for health checks.  Defaults to "/health".

## Registering

```php
$app->register(new Actuator\Provider\HealthServiceProvider(), array(
    "health.indicators" => array(
        new DiskSpaceHealthIndicator()
    )
));
```

## Usage

The following route is made available by default (unless you changed the "health.endpoint"):

* `GET /health`: Get health indicator statuses

## Getting Started

The following is a minimal example to get you started quickly.  It uses the 
[DiskSpaceHealthIndicator](src/Health/Indicator/DiskSpaceHealthIndicator.php).

* Create a composer.json with at minimum, the following dependecies

```json
{
    "require": {
        "postalservice/php-actuator": "^1.0"
    }
}
```

* Run composer install
* Create /public/index.php

```php
require_once __DIR__.'/../vendor/autoload.php';

use Silex\Application;
use Actuator\Health\Indicator\DiskSpaceHealthIndicator;
use Actuator\Health\Indicator\DoctrineConnectionHealthIndicator;
use Doctrine\DBAL\DriverManager;

$app = new Application();
$app['debug'] = true;

$app->register(new Actuator\Provider\HealthServiceProvider(), array(
    "health.indicators" => array(
        'diskspace' => new DiskSpaceHealthIndicator()
    )
));

$app->run();
```

* Run the service `php -S localhost:8000 -t public public/index.php`
* Go to http://localhost:8000/health to see your health indicator.

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Credits

- [John Kelly][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/postalservice14/php-actuator.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/postalservice14/php-actuator/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/postalservice14/php-actuator.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/postalservice14/php-actuator.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/postalservice14/php-actuator.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/postalservice14/php-actuator
[link-travis]: https://travis-ci.org/postalservice14/php-actuator
[link-scrutinizer]: https://scrutinizer-ci.com/g/postalservice14/php-actuator/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/postalservice14/php-actuator
[link-downloads]: https://packagist.org/packages/postalservice14/php-actuator
[link-author]: https://github.com/postalservice14
[link-contributors]: ../../contributors

