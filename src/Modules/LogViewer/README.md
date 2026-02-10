# LogViewer Module

## Description

The LogViewer module provides a powerful web interface for viewing and managing Laravel application logs. It integrates the popular [Opcodesio Log Viewer](https://github.com/opcodesio/log-viewer) package to provide a feature-rich log viewing experience.

## Features

- 📋 View application logs through a beautiful web interface
- 🔍 Search and filter logs by level, date, and content
- 📊 View logs from multiple environments and hosts
- 🎨 Dark mode support
- 🔐 Permission-based access control
- 📁 Support for multiple log files and folders
- ⚡ Fast performance with lazy loading
- 🌐 API support for programmatic access

## Installation

The module is already included in the module-manager package. Simply enable it in your `modules_statuses.json`:

```json
{
    "LogViewer": true
}
```

## Configuration

The module includes a configuration file that can be published:

```bash
php artisan vendor:publish --tag=logviewer-config
```

This will publish the `config/log-viewer.php` file to your application.

## Usage

Once enabled, the log viewer will be accessible at:

```
https://your-app.test/admin/log-viewer
```

By default, access requires:
- User authentication
- `view_logs` permission

## Configuration Options

Key configuration options in `config/log-viewer.php`:

- `enabled` - Enable/disable the log viewer
- `route_path` - URL path for the log viewer (default: `admin/log-viewer`)
- `middleware` - Middleware for the web interface
- `api_middleware` - Middleware for API requests
- `per_page_options` - Results per page options
- `timezone` - Timezone for displaying timestamps

## Permissions

The module requires the `view_logs` permission. Make sure to create this permission and assign it to appropriate roles.

## Dependencies

- [opcodesio/log-viewer](https://github.com/opcodesio/log-viewer) ^3.21

## Version

1.0.0

## License

This module follows the same license as the parent application.
