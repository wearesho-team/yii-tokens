## Yii2 Token Registration
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/wearesho-team/yii-token-registration/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/wearesho-team/yii-token-registration/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/wearesho-team/yii-token-registration/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/wearesho-team/yii-token-registration/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/wearesho-team/yii-token-registration/badges/build.png?b=master)](https://scrutinizer-ci.com/g/wearesho-team/yii-token-registration/build-status/master)

Verifying registration by sending token 

### Installation
```bash
composer require wearesho-team/yii-token-registration
```

### Migrations
- Copy migrations into your project
```bash
cd path-to-your-project
cp ./vendor/wearesho-team/yii-token-registration/migrations ./console/migrations
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