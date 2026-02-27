# Module Manager for Laravel Starter

[![Latest Version on Packagist](https://img.shields.io/packagist/v/nasirkhan/module-manager.svg?style=flat-square)](https://packagist.org/packages/nasirkhan/module-manager)
[![Total Downloads](https://img.shields.io/packagist/dt/nasirkhan/module-manager.svg?style=flat-square)](https://packagist.org/packages/nasirkhan/module-manager)

A powerful module management package for [Laravel Starter](https://github.com/nasirkhan/laravel-starter), providing version tracking, migration management, dependency resolution, and comprehensive module lifecycle management.

## 📦 Installation

```bash
composer require nasirkhan/module-manager
```

## Quick Start

```bash
php artisan module:status          # View all modules and their status
php artisan module:dependencies    # Check module dependencies
php artisan migrate                # Run pending migrations
```

## Available Commands

### Core

| Command | Description |
|---------|-------------|
| `module:status [module]` | View module status, versions, and dependencies |
| `module:dependencies [module]` | Check dependency satisfaction |
| `module:publish {module}` | Publish a module to `Modules/` for customization |
| `module:diff {module} [--detailed]` | Compare package version with published version |
| `module:enable {module}` | Enable a module |
| `module:disable {module}` | Disable a module |
| `module:build {module}` | Scaffold a new module |
| `module:remove {module}` | Remove a module |
| `module:make-test {module} {name} [--unit]` | Generate a test class for a module |
| `module:help [topic]` | Show command reference and workflows |

### Migration Management

| Command | Description |
|---------|-------------|
| `module:track-migrations [module] [--force]` | Record current migration state as baseline |
| `module:detect-updates [module]` | Detect new migrations after a composer update |
| `module:check-migrations [module]` | Check for unpublished migrations |

## Module Structure

```
Modules/Post/
├── module.json
├── Config/
├── Console/
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── factories/
├── Http/
│   ├── Controllers/
│   ├── Requests/
│   └── Middleware/
├── Livewire/
├── Models/
├── Providers/
├── Resources/
├── routes/
├── lang/
├── Tests/
│   ├── Feature/
│   └── Unit/
└── Resources/
    └── views/
```

---

## Module Configuration (`module.json`)

```json
{
    "name": "Post",
    "alias": "post",
    "description": "Blog post management module with categories, tags, and moderation",
    "version": "1.0.0",
    "keywords": ["post", "blog", "article", "content"],
    "priority": 0,
    "requires": ["Category", "Tag"]
}
```

- `priority`: Load order — higher values load first (e.g. `10` for core deps, `5` for UI, `0` for content)
- `requires`: Module names this module depends on

---

## Publishing Module Assets

```bash
php artisan vendor:publish --tag=post-migrations
php artisan vendor:publish --tag=post-views
php artisan vendor:publish --tag=post-config
php artisan vendor:publish --tag=post-lang
```

---

## Namespace Architecture

| Location | Namespace |
|----------|-----------|
| `vendor/nasirkhan/module-manager/src/Modules/` | `Nasirkhan\ModuleManager\Modules\{Module}\...` |
| `Modules/` (published) | `Modules\{Module}\...` |

When publishing a module (`module:publish`), all namespaces are rewritten automatically. After publishing, run:

```bash
composer dump-autoload
php artisan config:clear
```

---

## Troubleshooting

**Module not showing in status:**
```bash
composer dump-autoload
php artisan cache:clear && php artisan config:clear
```

**Migrations not detected after update:**
```bash
php artisan module:track-migrations --force
php artisan module:detect-updates
php artisan vendor:publish --tag=post-migrations
php artisan migrate
```

---

## Programmatic API

### ModuleVersion

```php
use Nasirkhan\ModuleManager\Services\ModuleVersion;

$service = app(ModuleVersion::class);

$service->getVersion('Post');               // "1.0.0"
$service->getDependencies('Post');          // ['Category', 'Tag']
$service->dependenciesSatisfied('Post');    // bool
$service->getModulesByPriority();           // ordered module list
```

### MigrationTracker

```php
use Nasirkhan\ModuleManager\Services\MigrationTracker;

$tracker = app(MigrationTracker::class);

$tracker->trackModuleMigrations('Post', '1.0.0');
$tracker->getNewMigrationsSinceLastCheck('Post');
$tracker->getPendingMigrations('Post');
$tracker->hasUpdates('Post');
```

---

## Contributing

See [CONTRIBUTING.md](CONTRIBUTING.md) for development guidelines.

## License

MIT License. See [LICENSE](LICENSE) for details.

## Available Modules

| Module | Version | Description | Dependencies |
|--------|---------|-------------|--------------|
| **Post** | 1.0.0 | Blog post management with moderation | Category, Tag |
| **Category** | 1.0.0 | Category management with nested sets | — |
| **Tag** | 1.0.0 | Polymorphic tagging system | — |
| **Menu** | 1.0.0 | Dynamic menu with nested items | — |

