 

# sndpbag/admin-panel

[](https://packagist.org/packages/sndpbag/admin-panel)
[](https://packagist.org/packages/sndpbag/admin-panel)
[](https://opensource.org/licenses/MIT)

A feature-rich, ready-to-use admin panel for Laravel. It comes packed with a beautiful UI, secure authentication with OTP, complete user management, and a powerful dynamic Role-Based Access Control (RBAC) system right out of the box.

This package provides a complete backend solution to kickstart any Laravel project.

## Features

This admin panel is packed with features to help you build your application faster:

### 🛡️ Authentication & Security

  * **Secure Auth Scaffolding:** Complete routes and views for Login, Register, Forgot Password, and Email Verification.
  * **Two-Factor (OTP) Login:** Enhances security by sending a One-Time Password via email after a successful login attempt.
  * **User Activity Logging:** Automatically logs detailed user login activity, including IP address, location (city/country), and device (browser, platform).

### 👑 Role & Permission Management (RBAC)

  * **Dynamic Role & Permission:** Full CRUD interface for managing Roles and Permissions.
  * **Route-to-Permission Sync:** Automatically scan your application's routes and sync them to the permissions table with a single command.
  * **Role Hierarchy:** Supports parent-child relationships between roles (e.g., an 'Admin' role inherits all permissions from a 'Moderator' role).
  * **Flexible Assignment:** Assign multiple roles to users, or assign permissions directly to a user.
  * **Super Admin Support:** Includes a 'super-admin' role that bypasses all permission checks.
  * **Middleware:** Protect routes using `role` and `permission` middleware.
  * **Blade Directives:** Convenient Blade directives for checking roles and permissions in your views (e.g., `@hasRole`, `@hasPermission`).

### 👤 User & Data Management

  * **Full User CRUD:** Manage users with full Create, Read, Update, and Delete functionality.
  * **Soft Deletes:** Includes a "Trash" view to restore or permanently delete users.
  * **Advanced Filtering:** Search users by name/email and filter by role or status.
  * **Data Export:** Export the filtered user list to PDF, XLSX, or CSV formats.
  * **Data Import:** Bulk create users by importing data from an Excel/CSV file, complete with validation.

### 🎨 UI & Customization

  * **Modern UI/UX:** Built with Tailwind CSS, featuring a responsive design, skeleton loaders, and a dark mode toggle.
  * **Profile Management:** Users can update their profile information, change their password, and manage notification settings.
  * **Image Cropper:** Includes a beautiful modal-based image cropper for profile picture uploads.
  * **Theme Customization:** Users can change the admin panel's primary color, secondary color, and font family.
  * **Config-Driven Sidebar:** Easily add new navigation items to the sidebar by editing the `admin-panel.php` config file.

## Requirements

  * **PHP:** ^8.1
  * **Laravel:** ^11.0 | ^12.0

## Installation

You can install the package via Composer.

**1. Require the package:**

```bash
composer require sndpbag/admin-panel
```

For the latest development version:

```bash
composer require sndpbag/admin-panel:dev-main
```

**2. Add the Trait to your User Model:**
Before installing, you **must** add the `HasRolesAndPermissions` trait to your `app/Models/User.php` model.

```php
<?php

namespace App\Models;

// ... other imports
use Sndpbag\AdminPanel\Traits\HasRolesAndPermissions; // <-- 1. Import the trait

class User extends Authenticatable
{
    use HasRolesAndPermissions; // <-- 2. Use the trait
    
    // ... rest of your User model
}
```

*Note: If you use the package's default User model, this is already done.*

**3. Run the Install Command:**
This is the main command that will set up everything for you.

```bash
php artisan dynamic-roles:install
```

This command will:

  * Publish the `config/admin-panel.php` file.
  * Run all necessary database migrations (for users, roles, permissions, logs, etc.).
  * Create the default 'Super Admin', 'Admin', and 'User' roles.
  * Prompt you to create a Super Admin user.
  * Sync your application routes to the permissions table.

**4. Publish Assets:**
To make the dashboard's JavaScript (like the image cropper) work, publish the assets:

```bash
php artisan vendor:publish --provider="Sndpbag\AdminPanel\Providers\AdminPanelServiceProvider" --tag="admin-panel-assets"
```

**5. Create Storage Link:**
To ensure profile pictures are visible after upload:

```bash
php artisan storage:link
```

**6. Configure Email:**
Since the package includes email verification and OTP notifications, ensure your `.env` file is configured correctly for sending emails.

```dotenv
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

## Usage

After installation, you can access the admin panel by visiting the following routes:

  * **Login:** `/login`
  * **Register:** `/register`
  * **Dashboard:** `/dashboard` (requires login)
  * **Roles & Permissions Panel:** `/admin/roles-permissions` (or as defined in your config)

### Protecting Routes with Middleware

You can protect your routes in `routes/web.php` using the built-in middleware.

**By Role:**
The user must have *any* of the specified roles.

```php
Route::middleware(['auth', 'role:super-admin,admin'])->group(function () {
    Route::get('/admin/analytics', [AnalyticsController::class, 'index']);
});
```

**By Permission:**
The user must have *any* of the specified permissions.

```php
Route::middleware(['auth', 'permission:users.create,users.edit'])->group(function () {
    Route::get('/admin/manage-users', [UserManagementController::class, 'index']);
});
```

### Blade Directives

You can check for roles and permissions directly in your Blade templates.

```blade
@hasRole('admin')
    <a href="#">Admin Settings</a>
@endhasRole

@hasPermission('posts.edit')
    <button>Edit Post</button>
@endhasPermission

@hasAnyRole(['admin', 'moderator'])
    <p>You can manage this section.</p>
@endhasAnyRole

@hasAllRoles(['admin', 'writer'])
    <p>You are an admin and a writer.</p>
@endhasAllRoles
```

## Customization

### Customizing the Sidebar

You can easily add new items to the dashboard sidebar.

1.  First, make sure you have run `php artisan dynamic-roles:install` to publish the `config/admin-panel.php` file.

2.  Open `config/admin-panel.php` and add a new entry to the `sidebar` array:

    ```php
    'sidebar' => [
        // ... default menu items
        [
            'title' => 'Products',
            'route' => 'products.index', // Make sure this route exists
            'icon' => '<svg class="w-6 h-6" ...>...</svg>', // Your custom SVG icon
            'active_on' => 'products.*' // The link will be active on routes like products.index, products.create, etc.
        ],
    ]
    ```

### Customizing Views

You can publish the views for the **Roles & Permissions** pages to customize them:

```bash
php artisan vendor:publish --provider="Sndpbag\AdminPanel\Providers\AdminPanelServiceProvider" --tag="dynamic-roles-views"
```

This will publish the files to `resources/views/vendor/dynamic-roles/`.

### Extending the Layout

You can use the admin panel's beautiful layout for your own custom pages. In your Blade view, simply extend the package's layout:

```blade
@extends('admin-panel::dashboard.layouts.app')

@section('title', 'My Custom Page')
@section('page-title', 'Page Title Here')

@section('content')
    <div class="bg-white p-6 rounded-2xl shadow-lg">
        <h1 class="text-2xl">Hello from my custom page!</h1>
        <p>This page uses the main admin panel layout.</p>
    </div>
@endsection
```

## License

This package is open-source software licensed under the **MIT License**.

## Credits

  * **Author:** sndp bag (Sandipan Kr Bag)
  * **Email:** sndpbagg@gmail.com