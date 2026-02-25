# Module Manager for Laravel Starter

[![Latest Version on Packagist](https://img.shields.io/packagist/v/nasirkhan/module-manager.svg?style=flat-square)](https://packagist.org/packages/nasirkhan/module-manager)
[![Total Downloads](https://img.shields.io/packagist/dt/nasirkhan/module-manager.svg?style=flat-square)](https://packagist.org/packages/nasirkhan/module-manager)

A powerful module management package for [Laravel Starter](https://github.com/nasirkhan/laravel-starter), providing version tracking, migration management, dependency resolution, and comprehensive module lifecycle management.

## 📦 Installation

```bash
composer require nasirkhan/module-manager
```

## 🚀 Quick Start

```bash
# Get organized help for all commands
php artisan module:help

# Get help on specific topics
php artisan module:help workflows    # Common workflows
php artisan module:help update        # After composer update  
php artisan module:help custom        # Customizing modules
php artisan module:help testing       # Testing modules

# View all modules and their status
php artisan module:status

# Check module dependencies
php artisan module:dependencies

# Generate a test for a module
php artisan module:make-test Post CreatePostTest
```

## 📋 Available Commands

### 🎯 Essential Commands (Most Used)

| Command | Description | Example |
|---------|-------------|---------|
| `module:help` | **Interactive help system** | `php artisan module:help workflows` |
| `module:status` | View module status, versions, dependencies | `php artisan module:status` |
| `module:dependencies` | Check module dependencies | `php artisan module:dependencies` |
| `module:publish` | Publish module for customization | `php artisan module:publish Post` |
| `module:diff` | Compare package vs published versions | `php artisan module:diff Post` |

### 🔄 Migration Management

| Command | Description | When to Use |
|---------|-------------|-------------|
| `module:track-migrations` | Track current migration state | After installation, before updates |
| `module:detect-updates` | Detect new migrations | After `composer update` |
| `module:check-migrations` | Check for unpublished migrations | Before running `migrate` |

### 🧪 Development & Testing

| Command | Description | Example |
|---------|-------------|---------|
| `module:make-test` | Generate test class | `php artisan module:make-test Post CreatePostTest` |
| `module:build` | Create new module | `php artisan module:build Blog` |
| `module:remove` | Remove module | `php artisan module:remove Post` |
| `module:enable` | Enable module | `php artisan module:enable Post` |
| `module:disable` | Disable module | `php artisan module:disable Post` |

## 📖 Detailed Command Reference

### module:help [topic]
**NEW!** Interactive help system with organized command reference and workflows.

```bash
# View all commands organized by category
php artisan module:help

# Get detailed help on specific topics
php artisan module:help workflows    # Common usage workflows
php artisan module:help update        # After composer update workflow
php artisan module:help custom        # Customizing modules workflow
php artisan module:help testing       # Testing workflow
```

**Features:**
- Organized command listing by usage frequency
- Step-by-step workflows for common tasks
- Detailed explanations with examples
- Quick reference without leaving terminal

💡 **Tip:** Start with `php artisan module:help` to see all available commands!

---

### module:status [module]
View comprehensive status of all modules or a specific module.

```bash
# View all modules
php artisan module:status

# View specific module
php artisan module:status Post
```

**Shows:**
- Module versions
- Location (package vs published)
- Customization status
- Dependencies
- Update strategy

---

### module:dependencies [module]
Check module dependencies and their satisfaction status.

```bash
# Check all modules
php artisan module:dependencies

# Check specific module  
php artisan module:dependencies Post
```

**Output:**
- ✓ Satisfied dependencies with versions
- ✗ Missing dependencies
- Dependency tree visualization

---

### module:publish {module}
Publish a module from package to your application for customization.

```bash
php artisan module:publish Post
```

**When to use:**
- You need to customize module code
- You want to extend module functionality  
- You need to modify module views/routes

**Note:** Published modules won't be automatically updated by composer.

---

### module:diff {module} [--detailed]
Compare package version with your published version.

```bash
# Quick overview
php artisan module:diff Post

# Detailed line-by-line comparison
php artisan module:diff Post --detailed
```

**Shows:**
- New files in package (+ green)
- Files only in your version (- red)
- Modified files (M yellow)
- Statistics and recommendations

---

### module:track-migrations [module] [--force]
Track current state of module migrations for update detection.

```bash
# Track all modules
php artisan module:track-migrations

# Track specific module
php artisan module:track-migrations Post

# Re-track (overwrite existing)
php artisan module:track-migrations --force
```

**When to use:**
- After initial package installation
- Before running `composer update`
- To establish baseline for migration detection

---

### module:detect-updates [module]
Detect new migrations and changes after package updates.

```bash
# Check all modules for updates
php artisan module:detect-updates

# Check specific module
php artisan module:detect-updates Post
```

**Shows:**
- Version changes
- New migrations
- Removed migrations
- How to update tracking

---

### module:check-migrations [module]
Check for new unpublished migrations from module packages.

```bash
# Check all modules
php artisan module:check-migrations

# Check specific module
php artisan module:check-migrations Post
```

**Shows:**
- New migrations not yet published
- How to publish them

---

### module:make-test {module} {name} [--unit]
Generate a new test class for a module.

```bash
# Create feature test
php artisan module:make-test Post CreatePostTest

# Create unit test
php artisan module:make-test Post PostModelTest --unit
```

**Generated structure:**
- Feature tests extend `Tests\TestCase`
- Unit tests extend `PHPUnit\Framework\TestCase`
- Proper namespacing and directory structure

---

## 🔄 Common Workflows

### Workflow 1: Fresh Installation

```bash
# 1. Check module status
php artisan module:status

# 2. Check dependencies are satisfied
php artisan module:dependencies

# 3. Track initial migration state
php artisan module:track-migrations

# 4. Run migrations
php artisan migrate

# 5. Verify everything works
php artisan test
```

---

### Workflow 2: After Composer Update

```bash
# 1. Detect updates and new migrations
php artisan module:detect-updates

# 2. If new migrations found, publish them
php artisan vendor:publish --tag=post-migrations

# 3. Review migrations, then run them
php artisan migrate

# 4. Update tracking state
php artisan module:track-migrations --force

# 5. Check for code changes
php artisan module:diff Post
```

---

### Workflow 3: Customizing a Module

```bash
# 1. Check current state
php artisan module:status Post

# 2. Publish the module for editing
php artisan module:publish Post

# 3. Make your customizations in Modules/Post/

# 4. After package update, check differences
php artisan module:diff Post --detailed

# 5. Manually merge upstream changes if needed
```

---

### Workflow 4: Developing & Testing

```bash
# 1. Create feature test
php artisan module:make-test Post CreatePostTest

# 2. Create unit test  
php artisan module:make-test Post PostModelTest --unit

# 3. Check dependencies are satisfied
php artisan module:dependencies Post

# 4. Run tests
php artisan test --filter=Post

# 5. Check migration status
php artisan module:check-migrations Post
```

---

## 📊 Module Structure

Each module follows this structure:

```
Modules/Post/
├── module.json          # Module metadata (version, dependencies)
├── composer.json        # Module-specific dependencies
├── Config/              # Configuration files
├── Console/             # Artisan commands
├── database/
│   ├── migrations/      # Database migrations
│   ├── seeders/         # Database seeders
│   └── factories/       # Model factories
├── Enums/               # PHP Enums
├── Events/              # Events
├── Http/
│   ├── Controllers/     # Controllers
│   ├── Requests/        # Form requests
│   └── Middleware/      # Middleware
├── Livewire/            # Livewire components
├── Models/              # Eloquent models
├── Providers/           # Service providers
├── Resources/           # API resources
├── routes/              # Route files (web.php, api.php)
├── lang/                # Translations
├── Tests/               # Tests
│   ├── Feature/         # Feature tests
│   └── Unit/            # Unit tests
└── Resources/
    └── views/           # Blade views
```

---

## 🔧 Module Configuration (module.json)

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

**Field Definitions:**
- `name`: Module name in PascalCase
- `alias`: Module alias in lowercase
- `description`: Brief module description
- `version`: Semantic version (major.minor.patch)
- `keywords`: Search keywords for the module
- `priority`: Load priority (higher loads first)
- `requires`: Array of required module names

---

## 🎯 Module Priorities

Modules load in priority order (highest first):

| Priority | Module Type | Examples |
|----------|-------------|----------|
| **10** | Core dependencies | Category, Tag |
| **5** | UI/Navigation modules | Menu |
| **0** | Content modules | Post |

**Why priorities matter:**
- Ensures dependencies load before dependent modules
- Controls service provider registration order
- Affects migration execution order

---

## 🔗 Dependency Management

### Declaring Dependencies

In your `module.json`:
```json
{
    "requires": ["Category", "Tag"]
}
```

### Checking Dependencies

```bash
# Check all modules
php artisan module:dependencies

# Check specific module
php artisan module:dependencies Post
```

### What Happens?

The package automatically:
- ✅ Validates all dependencies exist
- ✅ Shows missing dependencies
- ✅ Displays installed versions
- ✅ Orders module loading by priority

---

## 📝 Publishing Module Assets

```bash
# Publish all module assets
php artisan vendor:publish --tag=module-manager

# Publish specific module migrations
php artisan vendor:publish --tag=post-migrations
php artisan vendor:publish --tag=category-migrations
php artisan vendor:publish --tag=tag-migrations
php artisan vendor:publish --tag=menu-migrations

# Publish specific module views
php artisan vendor:publish --tag=post-views
php artisan vendor:publish --tag=category-views

# Publish specific module config
php artisan vendor:publish --tag=post-config

# Publish specific module translations
php artisan vendor:publish --tag=post-lang
```

---

## 🏷️ Namespace Architecture

Modules use different namespaces depending on whether they live in the vendor package or have been published.

| Location | Namespace |
|----------|-----------|
| `vendor/nasirkhan/module-manager/src/Modules/` | `Nasirkhan\ModuleManager\Modules\{Module}\...` |
| `Modules/` (published) | `Modules\{Module}\...` |

When you run `php artisan module:publish`, the command automatically:
- Replaces all namespace declarations, `use` statements, and string references in PHP files
- Updates the module's `composer.json` PSR-4 autoload mapping
- Published modules take precedence over vendor modules

After publishing, run:
```bash
composer dump-autoload
php artisan config:clear
```

---

## 🧪 Testing

### Running Module Tests

```bash
# Run all tests
php artisan test

# Run specific module tests
php artisan test --filter=Post

# Run with coverage
php artisan test --coverage
```

### Creating Module Tests

```bash
# Feature test (uses database, HTTP testing)
php artisan module:make-test Post CreatePostFeatureTest

# Unit test (isolated, fast)
php artisan module:make-test Post PostModelTest --unit
```

---

## 🚨 Troubleshooting

### Module not showing in status

```bash
# Clear application caches
php artisan cache:clear
php artisan config:clear

# Regenerate autoloader
composer dump-autoload

# Check status again
php artisan module:status
```

---

### Dependencies not satisfied

```bash
# Check what's missing
php artisan module:dependencies Post

# Ensure package is properly installed
composer require nasirkhan/module-manager

# Verify modules exist
ls vendor/nasirkhan/module-manager/src/Modules/
```

---

### Migrations not detected after update

```bash
# Re-track migrations
php artisan module:track-migrations --force

# Detect updates
php artisan module:detect-updates

# Publish new migrations
php artisan vendor:publish --tag=post-migrations

# Run migrations
php artisan migrate
```

---

### Too many changes in module diff

```bash
# View detailed differences
php artisan module:diff Post --detailed

# Option 1: Use package version (discard customizations)
rm -rf Modules/Post
composer update

# Option 2: Keep your customizations
# Review and manually merge changes
```

---

## 📚 Programmatic API

### ModuleVersion Service

```php
use Nasirkhan\ModuleManager\Services\ModuleVersion;

$service = app(ModuleVersion::class);

// Get version
$version = $service->getVersion('Post'); // "1.0.0"

// Get all module data
$data = $service->getModuleData('Post');

// Check version compatibility
$service->versionMatches('Post', '1.0.0'); // true
$service->versionSatisfies('Post', '1.0.0'); // true >= comparison

// Get dependencies
$deps = $service->getDependencies('Post'); // ['Category', 'Tag']

// Check if dependencies are satisfied
$status = $service->dependenciesSatisfied('Post');

// Get modules ordered by priority
$modules = $service->getModulesByPriority();
```

---

### MigrationTracker Service

```php
use Nasirkhan\ModuleManager\Services\MigrationTracker;

$tracker = app(MigrationTracker::class);

// Track current migration state
$tracker->trackModuleMigrations('Post', '1.0.0');

// Get new migrations since last track
$new = $tracker->getNewMigrationsSinceLastCheck('Post');

// Get migrations that haven't run yet
$pending = $tracker->getPendingMigrations('Post');

// Compare current state with tracked state
$comparison = $tracker->compareWithTracked('Post');

// Check if module has updates
$hasUpdates = $tracker->hasUpdates('Post');
```

---

## 🎨 Command Cheat Sheet

```bash
# ESSENTIAL (Use Daily)
module:status                       # Module overview
module:dependencies                 # Check dependencies

# CUSTOMIZATION
module:publish Post                 # Publish for editing
module:diff Post                    # Compare versions

# AFTER COMPOSER UPDATE
module:detect-updates               # Check for new migrations
module:track-migrations --force     # Update tracking

# DEVELOPMENT
module:make-test Post MyTest        # Create test
module:enable Post                  # Enable module
module:disable Post                 # Disable module

# ADVANCED
module:build Blog                   # Create new module
module:remove Post                  # Delete module
```

---

## 📖 Best Practices

### 1. Track Migrations Before Updates

```bash
# Before
php artisan module:track-migrations

# Update
composer update

# After
php artisan module:detect-updates
```

### 2. Check Dependencies Regularly

```bash
php artisan module:dependencies
```

### 3. Use Diff Before Merging Updates

```bash
php artisan module:diff Post --detailed
```

### 4. Keep Modules in Package When Possible

- ✅ Easier to update via composer
- ✅ Receive bug fixes automatically
- ✅ Less maintenance overhead

**Only publish when you absolutely need to customize.**

### 5. Version Control Your Customizations

If you publish modules:
```bash
git add Modules/Post/
git commit -m "feat: customize Post module with feature X"
```

---

## 🆘 Getting Help

1. **Check this documentation** - Most answers are here
2. **Run diagnostics:**
   ```bash
   php artisan module:status
   php artisan module:dependencies
   ```
3. **Check GitHub issues** - See if others had similar problems
4. **Create an issue** - Provide output from diagnostic commands

---

## 🤝 Contributing

See [CONTRIBUTING.md](CONTRIBUTING.md) for development guidelines.

## 📄 License

MIT License. See [LICENSE](LICENSE) for details.

---

## 📦 Available Modules

| Module | Version | Description | Dependencies |
|--------|---------|-------------|--------------|
| **Post** | 1.0.0 | Blog post management with moderation | Category, Tag |
| **Category** | 1.0.0 | Category management with nested sets | - |
| **Tag** | 1.0.0 | Polymorphic tagging system | - |
| **Menu** | 1.0.0 | Dynamic menu with nested items | - |

---

**Package Version:** 1.0.0  
**Last Updated:** February 3, 2026  
**Maintained by:** [Nasir Khan](https://github.com/nasirkhan)

