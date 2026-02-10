# FileManager Module

Laravel File Manager integration module for uploading and managing files and images.

## Features

- **File Upload**: Upload files and images through a user-friendly interface
- **File Browser**: Browse uploaded files in grid or list view
- **Image Thumbnails**: Automatic thumbnail generation for images
- **File Management**: Rename, move, delete files and folders
- **Private/Shared Folders**: Support for both private user folders and shared folders
- **Permission Integration**: Integrated with Laravel authentication and authorization
- **CKEditor/TinyMCE Integration**: Works seamlessly with WYSIWYG editors
- **Validation**: File type and size validation
- **Security**: Prevents uploading of executable files

## Installation

The module is automatically loaded when enabled in the module-manager package.

### Publish Config (Optional)

```bash
php artisan vendor:publish --tag=filemanager-config
# or
php artisan vendor:publish --tag=lfm-config
```

## Configuration

Edit `config/lfm.php` to customize:

### Storage Settings

```php
'disk' => 'public',  // Storage disk to use
'rename_file' => true,  // Auto-rename uploaded files
'over_write_on_duplicate' => false,  // Prevent overwriting existing files
```

### Folder Settings

```php
'allow_private_folder' => true,  // Enable private folders per user
'allow_shared_folder' => true,   // Enable shared folder
'shared_folder_name' => 'shares',  // Shared folder name
```

### File Validation

```php
'folder_categories' => [
    'file' => [
        'max_size' => 50000,  // size in KB
        'valid_mime' => [
            'image/jpeg',
            'image/png',
            'application/pdf',
            'text/plain',
        ],
    ],
    'image' => [
        'max_size' => 50000,
        'valid_mime' => [
            'image/jpeg',
            'image/png',
            'image/gif',
        ],
    ],
],
```

### Thumbnail Settings

```php
'should_create_thumbnails' => true,
'thumb_img_width' => 200,  // px
'thumb_img_height' => 200,  // px
```

## Usage

### Access File Manager

Navigate to: `/laravel-filemanager`

### Permissions Required

Users must have the `view_backend` permission to access the file manager.

### Integration with CKEditor

```html
<script>
CKEDITOR.replace('my-editor', {
    filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
    filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token={{csrf_token()}}',   
    filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
    filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token={{csrf_token()}}'
});
</script>
```

### Integration with TinyMCE

```javascript
tinymce.init({
    selector: 'textarea.my-editor',
    plugins: 'image code',
    toolbar: 'undo redo | link image | code',
    file_browser_callback: function(field_name, url, type, win) {
        var x = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
        var y = window.innerHeight|| document.documentElement.clientHeight|| document.body.clientHeight;

        var cmsURL = '/laravel-filemanager?field_name=' + field_name;
        if (type == 'image') {
            cmsURL = cmsURL + "&type=Images";
        } else {
            cmsURL = cmsURL + "&type=Files";
        }

        tinyMCE.activeEditor.windowManager.open({
            file : cmsURL,
            title : 'Filemanager',
            width : x * 0.8,
            height : y * 0.8,
            resizable : "yes",
            close_previous : "no"
        });
    }
});
```

### Standalone Button

```html
<button onclick="window.open('/laravel-filemanager?type=Files', 'Filemanager', 'width=900,height=600'); return false;">
    Select File
</button>
```

## Routes

| Method | URI | Middleware | Description |
|--------|-----|------------|-------------|
| GET | `/laravel-filemanager` | web, auth, can:view_backend | File manager interface |
| POST | `/laravel-filemanager/upload` | web, auth, can:view_backend | Upload files |
| GET | `/laravel-filemanager/folders` | web, auth, can:view_backend | List folders |
| POST | `/laravel-filemanager/folders` | web, auth, can:view_backend | Create folder |
| DELETE | `/laravel-filemanager/delete` | web, auth, can:view_backend | Delete files/folders |

Full route list provided by UniSharp Laravel File Manager package.

## Security Considerations

### Disallowed File Types

By default, the following are blocked:

```php
'disallowed_mimetypes' => ['text/x-php', 'text/html', 'text/plain'],
'disallowed_extensions' => ['php', 'html'],
```

### Private Folders

Each user gets their own private folder (if enabled). The folder name is determined by the `private_folder_name` configuration.

### Authentication

All file manager routes require authentication (`auth` middleware) and backend access permission (`can:view_backend`).

## Dependencies

- `unisharp/laravel-filemanager` - Main file manager functionality
- Laravel Storage - File storage
- Intervention Image (optional) - Image manipulation

## Troubleshooting

### Uploads not working
- Check storage disk permissions (`storage/app/public` must be writable)
- Verify symlink exists: `php artisan storage:link`
- Check `php.ini` settings: `upload_max_filesize` and `post_max_size`

### Thumbnails not generating
- Install Intervention Image: `composer require intervention/image`
- Check `should_create_thumbnails` config setting
- Verify storage permissions

### Files not visible
- Check the storage disk configuration
- Verify the `disk` setting in `config/lfm.php`
- Run `php artisan storage:link` if using public disk

### Integration with editors not working
- Verify CSRF token is passed correctly
- Check browser console for JavaScript errors
- Ensure routes are accessible (not blocked by middleware)

## Version History

- **1.0.0** - Initial release with full file manager integration (Feb 9, 2026)

## External Documentation

- [UniSharp Laravel File Manager Documentation](http://unisharp.github.io/laravel-filemanager/)
- [GitHub Repository](https://github.com/UniSharp/laravel-filemanager)

## License

MIT License - Part of the Module Manager package by Nasir Khan.
