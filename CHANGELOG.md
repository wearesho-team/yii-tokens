# Yii Tokens Changelog

## 1.3.0
- Add `__toString` method to [TokenInterface](./src/Interfaces/TokenInterface.php)
- Fix issue with missing validation period

## 1.2.2
- Add [TokenableEntity](./src/Entities/TokenableEntity.php)
- Mark test classes as internal (phpDoc block)


## 1.2.0
- Remove `symfony/config` from dependencies, use `getenv` for TokenRepositoryConfig

## 1.1.0
- Changed behavior of TokenRepository::verify
now it will delete old token from storage to prevent double validation of one token.

## 1.0.0
- First release