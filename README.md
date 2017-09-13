## Yii2 Token Registration
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/b/wearesho_team/yii-token-registration/badges/quality-score.png?b=master&s=6a0d33567106e9ec00d31181f73e9e468996a0a1)](https://scrutinizer-ci.com/b/wearesho_team/yii-token-registration/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/b/wearesho_team/yii-token-registration/badges/coverage.png?b=master&s=feafe5097d6976ad03dae64b44ceab5b8cab9eb7)](https://scrutinizer-ci.com/b/wearesho_team/yii-token-registration/?branch=master)
[![Build Status](https://scrutinizer-ci.com/b/wearesho_team/yii-token-registration/badges/build.png?b=master&s=a0e739cf3c87110ddef5adc4977df6104759f575)](https://scrutinizer-ci.com/b/wearesho_team/yii-token-registration/build-status/master)

Verifying registration by sending token 

### Installation
- Add to your *composer.json*:
```json
{
  "require": {
    "wearesho-team/wearesho-team/yii-token-registration": "1.0.0"
  },
  "repositories": [
    {
      "type": "vcs",
      "url": "git@bitbucket.org:wearesho_team/yii-token-registration.git"
    }
  ]
}
```

### Migrations
- Copy migrations into your project
```bash
cd path-to-your-project
cp ./vendor/wearesho-team/yii-token-registration/migrations ./console/migrations
``` 
 
### TODO
1. Documentation
2. Tests for exceptions