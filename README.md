## Yii2 Token Registration
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