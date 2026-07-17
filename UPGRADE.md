# Upgrade Guide

This guide covers breaking changes and migration steps between major versions of `nasirkhan/module-manager`.

## Table of Contents
- [General Upgrade Tips](#general-upgrade-tips)
- [Upgrading to 7.0 from 6.x](#upgrading-to-70-from-6x)

---

## General Upgrade Tips

1. **Backup your project** before upgrading
2. **Read the full section** for the version you are targeting
3. **Upgrade one major version at a time** if jumping multiple versions
4. **Run your test suite** after each upgrade

---

## Upgrading to 7.0 from 6.x

> **Estimated Time:** 5–10 minutes
> **Difficulty:** Low
> **Risk Level:** Low

### Overview

`module-manager` v7.0 takes ownership of two dependencies that were previously declared directly in `laravel-starter`:

| Package | Reason moved |
|---|---|
| `spatie/laravel-activitylog` | Used internally by the `Post` model (`LogsActivity` trait) — belongs in the package |
| `intervention/image-laravel` | Required by `nasirkhan/laravel-jodit` for image resize/crop in the Jodit file browser |

Both packages continue to be installed in your application — they are now pulled in transitively through `nasirkhan/module-manager` instead of being listed as direct application dependencies.

### Breaking Changes

#### Dependencies removed from `laravel-starter`

`spatie/laravel-activitylog` and `intervention/image-laravel` are no longer listed as direct dependencies in `laravel-starter/composer.json`. If you have customizations that rely on these packages, they will continue to work because Composer still installs them (via `module-manager`). No PHP code changes are required.

The only action required is removing them from your own `composer.json` to keep it clean.

---

### Migration Steps

#### Step 1: Update `nasirkhan/module-manager`

```bash
composer update nasirkhan/module-manager
```

This pulls in the new version which now declares `spatie/laravel-activitylog` and `intervention/image-laravel` as its own dependencies.

#### Step 2: Remove direct entries from your `composer.json`

Open your application's `composer.json` and remove the following lines from the `require` section if they are present:

```json
"intervention/image-laravel": "^4.0",
"spatie/laravel-activitylog": "^5.0",
```

#### Step 3: Update the lock file

```bash
composer update
```

Composer will confirm the packages are still resolved (transitively) and update `composer.lock`.

#### Step 4: Verify the packages are still installed

```bash
composer show spatie/laravel-activitylog
composer show intervention/image-laravel
```

Both commands should return package info, confirming they are still present in `vendor/`.

#### Step 5: Clear application caches

```bash
php artisan config:clear
php artisan cache:clear
```

#### Step 6: Run your tests

```bash
php artisan test
```

---

### No Code Changes Required

The package classes and facades remain the same. Any existing code that references these packages will continue to work without modification:

```php
// These continue to work — no changes needed
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;
use Intervention\Image\Laravel\Facades\Image;
```

---

### Troubleshooting

**`Class not found` errors after upgrading**

Run `composer dump-autoload` and clear caches:

```bash
composer dump-autoload
php artisan clear-all
```

**Package still showing as a direct dependency**

If `composer show --direct` still lists the package, ensure you removed it from both the `require` and `require-dev` sections of your `composer.json`, then run `composer update`.

**Activity log migrations missing**

The activity log migrations are published from `spatie/laravel-activitylog`. If you have not run them yet:

```bash
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-migrations"
php artisan migrate
```

---

## Getting Help

- **Issues:** [GitHub Issues](https://github.com/nasirkhan/module-manager/issues)
- **Discussions:** [GitHub Discussions](https://github.com/nasirkhan/module-manager/discussions)
