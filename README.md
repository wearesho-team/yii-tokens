## Yii2 Tokens
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/wearesho-team/yii-tokens/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/wearesho-team/yii-tokens/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/wearesho-team/yii-tokens/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/wearesho-team/yii-tokes/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/wearesho-team/yii-tokens/badges/build.png?b=master)](https://scrutinizer-ci.com/g/wearesho-team/yii-tokens/build-status/master)

Verifying some action by sending token (SMS, Telegram etc.)
[Changelog](./CHANGELOG.md)

### Installation
```bash
composer require wearesho-team/yii-tokens
```

### Migrations
- Copy migrations into your project
```bash
cd path-to-your-project
cp -R ./vendor/wearesho-team/yii-tokens/migrations ./console/migrations
``` 
 
### Configuration
```php
<?php
use Wearesho\Yii\Configs\TokenRepositoryConfig;

// You may load config from anywhere
$config = [
    'TokenRepository' => [
        'expirePeriod' => 20, // in minutes
        'deliveryLimit' => 4,
        'verifyLimit' => 3,
    ],
];
$settings = TokenRepositoryConfig::instantiate($config);
```
 
### TODO
1. Documentation
2. Tests for exceptions
