## Yii2 Tokens
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/wearesho-team/yii-tokens/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/wearesho-team/yii-tokens/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/wearesho-team/yii-tokens/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/wearesho-team/yii-tokes/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/wearesho-team/yii-tokens/badges/build.png?b=master)](https://scrutinizer-ci.com/g/wearesho-team/yii-tokens/build-status/master)

Verifying some action by sending token (SMS, Email, Telegram etc.)
Compatible only with MySQL and PostgreSQL  
[CHANGELOG](./CHANGELOG.md)

### Installation
```bash
composer require wearesho-team/yii-tokens:^3.0
```

### Migrations
- Copy migrations into your project
```bash
cd path-to-your-project
cp -R ./vendor/wearesho-team/yii-tokens/migrations ./console/migrations
``` 
 
### Configuration
#### Environment
Default *[TokenRepositoryConfig](./src/Configs/TokenRepositoryConfig.php)* loads configuration from environment variables.
Environment variables name may be changed, defaults:
- **TOKEN_EXPIRE_MINUTES** - minutes from creating token when it will be invalidated
- **TOKEN_VERIFY_LIMIT** - maximum verifying limit (used by [TokenValidator](./src/Validators/TokenValidator.php)) 
- **TOKEN_DELIVERY_LIMIT** - maximum sending limit (used by [TokenRepository](./src/Repositories/TokenRepository.php), *send* method)
#### Container
You should configure your DI-container to use environment config:
```php
<?php
// bootstrap.php

use Wearesho\Yii\Interfaces\TokenRepositoryConfigInterface;
use Wearesho\Yii\Configs\TokenRepositoryConfig;

Yii::$container->set(
    TokenRepositoryConfigInterface::class,
    [
        'class' => TokenRepositoryConfig::class,
        
        // Changing environment variables names
        'expirePeriodKey' => 'TOKEN_EXPIRE_MINUTES', // optional
        'verifyLimitKey' => 'TOKEN_VERIFY_LIMIT', // optional
        'deliveryLimitKey' => 'TOKEN_DELIVERY_LIMIT', // optional
        
        // Defaults (if no env variables set)
        'defaultExpirePeriod' => 30, // optional
        'defaultDeliveryLimit' => 3, // optional
        'defaultVerifyLimit' => 3, // optional
    ]
);

```

## Contribution
## Run Tests
Run test MySQL database
```bash
docker compose up -d
```
Run tests
```bash
composer lint
composer test
```
Down test MySQL database
```bash
docker compose down
```

### TODO
1. Documentation
2. Tests for exceptions

### License
Unlicensed