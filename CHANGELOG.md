# Changelog

All notable changes to the `nasirkhan/module-manager` package will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [7.0.0]

### Added
- `spatie/laravel-activitylog ^5.0` as a direct dependency — previously a direct dependency of `laravel-starter`, it is used by the `Post` model (`LogsActivity` trait) and belongs at the package level
- `intervention/image-laravel ^4.0` as a direct dependency — required by `nasirkhan/laravel-jodit` for image resize, crop, and EXIF sanitization operations in the Jodit file browser connector

### Breaking Changes

Applications that required `spatie/laravel-activitylog` or `intervention/image-laravel` directly in their `composer.json` can remove those entries — they are now provided transitively through this package. See [UPGRADE.md](UPGRADE.md) for the full migration steps.

## [6.9.0] - Previous Release

See Git history for changes prior to changelog introduction.

[Unreleased]: https://github.com/nasirkhan/module-manager/compare/v6.9.0...HEAD
[6.9.0]: https://github.com/nasirkhan/module-manager/releases/tag/v6.9.0
