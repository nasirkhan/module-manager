# Module Manager - Quick Reference

## Namespace Architecture

### When Modules are in Vendor (Default)
**Location:** `vendor/nasirkhan/module-manager/src/Modules/`
**Namespace:** `Nasirkhan\ModuleManager\Modules\{ModuleName}\...`
**Example:** `Nasirkhan\ModuleManager\Modules\Category\Models\Category`

### When Modules are Published
**Location:** `Modules/`
**Namespace:** `Modules\{ModuleName}\...`
**Example:** `Modules\Category\Models\Category`

## Publishing Modules

### Publish a Module
```bash
php artisan module:publish Category
```

This command:
1. ✅ Copies module from vendor to `Modules/Category/`
2. ✅ Replaces ALL namespaces automatically
3. ✅ Updates composer.json autoload
4. ✅ Updates modules_statuses.json

### Force Overwrite
```bash
php artisan module:publish Category --force
```

### After Publishing
```bash
composer dump-autoload
php artisan config:clear
php artisan route:clear
```

## What Gets Changed on Publish

### PHP Files
```php
// BEFORE (vendor)
namespace Nasirkhan\ModuleManager\Modules\Category\Models;
use Nasirkhan\ModuleManager\Modules\Category\Enums\CategoryStatus;
$model = 'Nasirkhan\\ModuleManager\\Modules\\Category\\Models\\Category';

// AFTER (published)
namespace Modules\Category\Models;
use Modules\Category\Enums\CategoryStatus;
$model = 'Modules\\Category\\Models\\Category';
```

### composer.json
```json
// BEFORE (vendor)
{
  "autoload": {
    "psr-4": {
      "Nasirkhan\\ModuleManager\\Modules\\Category\\": ""
    }
  }
}

// AFTER (published)
{
  "autoload": {
    "psr-4": {
      "Modules\\Category\\": ""
    }
  }
}
```

## Module Loading Priority

1. **Published Module** (`Modules\Category\...`) - loads first if exists
2. **Vendor Module** (`Nasirkhan\ModuleManager\Modules\Category\...`) - fallback

## Available Modules

- Backup
- Category
- FileManager
- LogViewer
- Menu
- Post
- Tag

## Files Updated in This Fix

### Core Files
- ✅ `src/Commands/ModulePublishCommand.php` - Added namespace replacement
- ✅ `src/ModuleManagerServiceProvider.php` - Support both vendor & published

### Module composer.json Files
- ✅ `src/Modules/Category/composer.json`
- ✅ `src/Modules/Post/composer.json`
- ✅ `src/Modules/Tag/composer.json`
- ✅ `src/Modules/Menu/composer.json`

## Testing the Fix

### Test 1: Verify Vendor Modules Load
```bash
cd laravel-starter
php artisan route:list | grep categories
# Should show category routes from vendor
```

### Test 2: Test Publishing
```bash
# Publish module
php artisan module:publish Category --force

# Verify namespace changed
grep -n "namespace" Modules/Category/Models/Category.php
# Should show: namespace Modules\Category\Models;

# Refresh autoload
composer dump-autoload

# Test routes work
php artisan route:list | grep categories
# Should still show category routes (now from published module)
```

### Test 3: Verify Both Work
```bash
# With vendor module (no Categories in Modules/)
rm -rf Modules/Category
composer dump-autoload
php artisan route:list | grep categories
# ✅ Works from vendor

# With published module
php artisan module:publish Category --force
composer dump-autoload
php artisan route:list | grep categories
# ✅ Works from published (takes precedence)
```

## Common Issues & Solutions

### Issue: "Class not found" after publishing
**Solution:**
```bash
composer dump-autoload
php artisan config:clear
```

### Issue: Old namespace still referenced
**Solution:** The publish command replaces ALL occurrences automatically. If you manually copied files, re-run:
```bash
php artisan module:publish ModuleName --force
```

### Issue: Module not loading after publish
**Solution:** Check `modules_statuses.json` status:
```json
{
  "Category": {
    "published": true,
    "published_at": "2026-02-11T...",
    "location": "user",
    "version": "1.0.0"
  }
}
```

## Benefits of This Architecture

✅ **Zero Manual Work** - Namespaces replaced automatically
✅ **Safe Customization** - Published modules override vendor
✅ **Easy Updates** - Use `php artisan module:diff` to see changes
✅ **Clean Separation** - Vendor and user modules clearly separated
✅ **No Breaking Changes** - Both vendor and published modules work
✅ **Proper Autoloading** - PSR-4 compliant in both scenarios
