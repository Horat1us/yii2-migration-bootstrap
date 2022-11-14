# Yii2 Migrations Bootstrap
[![Test & Lint](https://github.com/Horat1us/yii2-migration-bootstrap/actions/workflows/php.yml/badge.svg?branch=master)](https://github.com/Horat1us/yii2-migration-bootstrap/actions/workflows/php.yml)
[![codecov](https://codecov.io/gh/Horat1us/yii2-migration-bootstrap/branch/master/graph/badge.svg)](https://codecov.io/gh/Horat1us/yii2-migration-bootstrap)

This package provides `\yii\base\BootstrapInterface` implementation to append packages migrations to console application.  
Main purpose is to prevent mistakes while re-configuring console application.  

Previous implementation was available in [horat1us/yii2-base](https://github.com/Horat1us/yii2-base) package
as [BootstrapMigrations](https://github.com/Horat1us/yii2-base/blob/1.16.0/src/Traits/BootstrapMigrations.php) trait. 

## Installation
Using [packagist.org](https://packagist.org/packages/horat1us/yii2-migration-bootstrap):
```bash
composer require horat1us/yii2-migration-bootstrap:^1.0
```

## Structure
- [BootstrapTrait](./src/BootstrapTrait.php) - base implementation, can be used outside `\yii\base\BootstrapInterface`
implementation.
- [Bootstrap](./src/Bootstrap.php) - `yii\base\BootstrapInterface` implementation using *BootstrapTrait*


## Example
Implement `\yii\base\BootstrapInterface` in your yii2 package:
```php
<?php

namespace Package;

use Horat1us\Yii;

class Bootstrap extends Yii\Migration\Bootstrap
{
    public $namespaces = __NAMESPACE__ . "\\Migrations";
    
    public $aliases = ['Package' => '@vendor/developer/package/src'];
}
```

Then add to application bootstraps:
```php
<?php

// config.php

use Package;

return [
    'bootstrap' => [
        'class' => Package\Bootstrap::class,    
    ],
    // ... another application config
];
```

## License
[MIT](./LICENSE)
