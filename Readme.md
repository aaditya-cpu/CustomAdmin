# Custom Admin Permissions Plugin

**Version:** 1.0  
**Author:** Your Name  
**Description:** This plugin allows administrators to control the admin menu, plugin access, and specific permissions (read, edit, delete) for a custom user role in WordPress.

## Features

- **Custom User Role:** Creates a `Custom User` role with basic permissions.
- **Admin Menu Control:** Allows you to customize which admin menu items the `Custom User` can access.
- **Plugin Access Control:** Enables selective plugin access for the `Custom User`.
- **Role Permissions:** Provides options to control `read`, `edit`, and `delete` capabilities for the `Custom User`.
- **Admin Settings Page:** A user-friendly settings page for managing all the above features.

## Installation

1. **Upload the Plugin Files:**
   - Download the plugin ZIP file.
   - Extract the `custom-admin-permissions` directory.
   - Upload the `custom-admin-permissions` directory to the `/wp-content/plugins/` directory on your WordPress site.

2. **Activate the Plugin:**
   - Go to the WordPress Admin Dashboard.
   - Navigate to `Plugins > Installed Plugins`.
   - Find the "Custom Admin Permissions" plugin and click "Activate".

3. **Configure the Plugin:**
   - Once activated, go to the WordPress Admin Dashboard.
   - Navigate to `User Permissions` in the admin menu.
   - Customize the settings by selecting which plugins, admin menu items, and permissions the `Custom User` role should have.

## Usage

### Creating a Custom User Role

This plugin automatically creates a new user role called `Custom User` upon activation. You can assign this role to any user in the `Users > All Users` section by editing their profile.

### Customizing Admin Menu Access

- Go to `User Permissions` in the WordPress Admin Dashboard.
- In the **Allowed Admin Menu Items** section, check the boxes for the menu items you want the `Custom User` to have access to.
- Save your changes.

### Controlling Plugin Access

- In the **Allowed Plugins** section of the `User Permissions` page, check the boxes for the plugins you want the `Custom User` to access.
- Save your changes.

### Setting Role Permissions

- In the **Role Permissions** section of the `User Permissions` page, you can set `read`, `edit`, and `delete` permissions for the `Custom User`.
- Check the boxes corresponding to the permissions you want to grant.
- Save your changes.

## Frequently Asked Questions

### Can I customize the permissions for other user roles?

This version of the plugin is designed to manage permissions for the `Custom User` role only. If you need to manage permissions for other roles, you may need to modify the plugin code or use an additional plugin like User Role Editor.

### What happens if I deactivate the plugin?

Deactivating the plugin will restore the default permissions for the `Custom User` role. Any customizations made through the plugin will be lost until the plugin is reactivated.

### Can I add more capabilities to the `Custom User` role?

Yes, you can modify the `cap_register_custom_user_role` function in the plugin code to add or remove capabilities from the `Custom User` role.

## Contributing

If you would like to contribute to this plugin, feel free to submit a pull request or open an issue on the [GitHub repository](#).

## License

This plugin is licensed under the [MIT License](https://opensource.org/licenses/MIT).

## Support

For support, please contact [Your Name] at [Your Email].

