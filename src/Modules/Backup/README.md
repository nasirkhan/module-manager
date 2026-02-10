# Backup Module

Database and file backup management module for Laravel applications.

## Features

- **List Backups**: View all available backup files with details
- **Create Backup**: Generate new database and file backups on-demand
- **Download Backups**: Download backup files to local storage
- **Delete Backups**: Remove old or unnecessary backup files
- **File Size Display**: Human-readable file sizes
- **Timestamp Tracking**: View creation date and time ago
- **Spatie Laravel Backup Integration**: Built on top of the robust Spatie backup package

## Installation

The module is automatically loaded when enabled in the module-manager package.

### Publish Views (Optional)

```bash
php artisan vendor:publish --tag=backup-views
```

## Configuration

This module uses the `spatie/laravel-backup` package. Configure backup settings in `config/backup.php`:

```php
return [
    'backup' => [
        'name' => env('APP_NAME', 'laravel-backup'),
        // ... other backup settings
    ],
];
```

## Usage

### Access Backup Management

Navigate to: `/admin/backups`

### Permissions Required

- `view_backups` - View backup list
- `add_backups` - Create new backups
- `create_backups` - Create new backups
- `download_backups` - Download backup files
- `delete_backups` - Delete backup files

### Creating Backups

1. Click "Create" button on the backup list page
2. The system will run `php artisan backup:run`
3. Backup file will be created in storage/app/Laravel

### Downloading Backups

Click the "Download" button next to any backup file.

### Deleting Backups

Click the "Delete" button next to any backup file (requires confirmation).

## Routes

| Method | URI | Name | Action |
|--------|-----|------|--------|
| GET | `/admin/backups` | `backend.backups.index` | List all backups |
| GET | `/admin/backups/create` | `backend.backups.create` | Create new backup |
| GET | `/admin/backups/download/{file}` | `backend.backups.download` | Download backup file |
| GET | `/admin/backups/delete/{file}` | `backend.backups.delete` | Delete backup file |

## Dependencies

- `spatie/laravel-backup` - Main backup functionality
- Laravel Storage - File management
- Laravel Artisan - Command execution

## Technical Details

**Controller**: `Nasirkhan\ModuleManager\Modules\Backup\Controllers\BackupController`
**Views**: `backup::backups`
**Routes**: Defined in `routes/web.php`

## Demo Mode

Backup creation is disabled in demo mode to prevent system abuse.

## Log Files

Backup operations are logged to `storage/logs/laravel.log`:
- Backup creation events
- Download attempts
- Delete operations
- Error conditions

## Troubleshooting

### No backups showing
- Check `config/backup.php` configuration
- Verify storage disk permissions
- Run `php artisan backup:run` manually

### Backup creation fails
- Check storage disk space
- Verify database connection
- Check `storage/logs/laravel.log` for errors

### Download not working
- Verify file exists in storage
- Check storage disk permissions
- Ensure file hasn't been deleted

## Version History

- **1.0.0** - Initial release with full backup management features

## License

MIT License - Part of the Module Manager package by Nasir Khan.
