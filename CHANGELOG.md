# Craft Google Location Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) and this project adheres to [Semantic Versioning](http://semver.org/).

## 3.0.0 - 2026-06-15

### Changed

- Updated the plugin for compatibility with Craft CMS 5 and PHP 8.2+.
- Bumped the `apt/craft-countries-module` dependency to `^3.0` (Craft 5 compatible).
- Migrated field content column declarations from `getContentColumnType()` to the static `dbType()` method.
- Migrated field input rendering from `getInputHtml()` to the `inputHtml()` method.
- Updated the content service to use the Craft 5 Entries service and Matrix entry-type APIs.

## 1.1.8 - 2020-01-28
 - API Key can now be set to an environment variable. Plugin now requires Craft CMS 3.1.

## 1.1.7 - 2020-01-10

- Fixed issue with stored value getting reset to default on multisite installations.

## 1.0.0 - 2018-08-17

### Added

- Initial release
