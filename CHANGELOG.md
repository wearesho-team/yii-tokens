# Yii Tokens Changelog

## 1.2.0
- Remove `symfony/config` from dependencies, use `getenv` for TokenRepositoryConfig

## 1.1.0
- Changed behavior of TokenRepository::verify
now it will delete old token from storage to prevent double validation of one token.

## 1.0.0
- First release