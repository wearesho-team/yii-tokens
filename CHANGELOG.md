# Yii Tokens Changelog

## 5.2.0
- Require PHP >= 8.4 (was ^8.1). Tested on PHP 8.4 and PHP 8.5, with MySQL and PostgreSQL.
- Raise minimum versions to the first releases supporting PHP 8.4/8.5:
`yiisoft/yii2` ^2.0.54, `nesbot/carbon` ^3.2 || ^2.73,
`horat1us/yii2-carbon-behavior` ^1.4, `horat1us/yii2-validation-exception` ^1.2.
- Fix PHP 8.4 deprecation "Implicitly marking parameter $previous as nullable" in the constructors of
[InvalidTokenException](./src/Exceptions/InvalidTokenException.php),
[InvalidRecipientException](./src/Exceptions/InvalidRecipientException.php) and
[DeliveryLimitReachedException](./src/Exceptions/DeliveryLimitReachedException.php).

## 5.0.0
- Remove static method [Token](./src/Models/Token.php)::getType()
type configuration moved to [Repository](./src/Repositories/TokenRepository.php)
- Make method [TokenRecordInterface::getToken](./src/Interfaces/TokenRecordInterface.php) non-static.
- Rewrite [TokenableEntityInterface.php](./src/Interfaces/TokenableEntityInterface.php)
- All methods of [TokenRepository](./src/Repositories/TokenRepository.php) now take a
[TokenableEntityInterface](./src/Interfaces/TokenableEntityInterface.php)
(`pull()` and `verify()` took a recipient string before).

## 2.0.0
- Yii 2.0.14.1 compatibility fixes

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