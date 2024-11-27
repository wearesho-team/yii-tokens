# Yii Tokens Changelog

## 5.0.0
- Remove static method [Token](./src/Models/Token.php)::getType()
type configuration moved to [Repository](./src/Repositories/TokenRepository.php)
- Make method [TokenRecordInterface::getToken](./src/Interfaces/TokenRecordInterface.php) non-static.
- Rewrite [TokenableEntityInterface.php](./src/Interfaces/TokenableEntityInterface.php)
- All methods of [TokenRepository](./src/Repositories/TokenRepository.php) now 

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