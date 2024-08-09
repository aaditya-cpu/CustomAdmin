<?php
/**
 * Plugin Name: Custom Admin Permissions
 * Description: Control the admin menu and permissions for a new user type, including WooCommerce product creation and selective plugin access.
 * Version: 1.0
 * Author: Death301
 */


// Register a custom user role
add_action('init', 'cap_register_custom_user_role');
function cap_register_custom_user_role() {
    add_role('custom_user_role', 'Custom User', [
        'read' => true,
        'edit_posts' => true,
        'delete_posts' => false,
    ]);
}

// Customize the admin menu
add_action('admin_menu', 'cap_customize_admin_menu', 999);
function cap_customize_admin_menu() {
    if (current_user_can('custom_user_role')) {
        remove_menu_page('tools.php');
        remove_menu_page('options-general.php');
        remove_menu_page('edit.php');
        remove_menu_page('edit-comments.php');
        // Add or remove other menus as needed
    }
}

// Control plugin access
add_filter('option_active_plugins', 'cap_control_plugin_access');
function cap_control_plugin_access($plugins) {
    if (current_user_can('custom_user_role')) {
        $allowed_plugins = get_option('cap_allowed_plugins', []);
        foreach ($plugins as $key => $plugin) {
            if (!in_array($plugin, $allowed_plugins)) {
                unset($plugins[$key]);
            }
        }
    }
    return $plugins;
}

// Add admin page for setting allowed plugins and menu items
add_action('admin_menu', 'cap_add_permissions_page');
function cap_add_permissions_page() {
    add_menu_page(
        'Custom User Permissions',
        'User Permissions',
        'manage_options',
        'cap-permissions',
        'cap_render_permissions_page'
    );
}

function cap_render_permissions_page() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    if (isset($_POST['save_permissions'])) {
        update_option('cap_allowed_plugins', $_POST['allowed_plugins']);
        update_option('cap_allowed_menu_items', $_POST['allowed_menu_items']);
        update_option('cap_role_permissions', $_POST['role_permissions']);
        echo '<div class="updated"><p>Settings saved.</p></div>';
    }

    $all_plugins = get_plugins();
    $allowed_plugins = get_option('cap_allowed_plugins', []);
    $allowed_menu_items = get_option('cap_allowed_menu_items', []);
    $role_permissions = get_option('cap_role_permissions', []);

    global $menu;
    echo '<div class="wrap">';
    echo '<h2>Select Plugins and Menu Items for Custom User</h2>';

    // Display plugin options
    echo '<h3>Allowed Plugins</h3>';
    echo '<form method="post">';
    echo '<table class="form-table">';
    foreach ($all_plugins as $plugin_file => $plugin_data) {
        $checked = in_array($plugin_file, $allowed_plugins) ? 'checked' : '';
        echo '<tr>';
        echo '<th scope="row">' . esc_html($plugin_data['Name']) . '</th>';
        echo '<td>';
        echo '<input type="checkbox" name="allowed_plugins[]" value="' . esc_attr($plugin_file) . '" ' . $checked . ' />';
        echo '</td>';
        echo '</tr>';
    }
    echo '</table>';

    // Display menu items
    echo '<h3>Allowed Admin Menu Items</h3>';
    echo '<table class="form-table">';
    foreach ($menu as $menu_item) {
        $menu_slug = $menu_item[2];
        $checked = in_array($menu_slug, $allowed_menu_items) ? 'checked' : '';
        echo '<tr>';
        echo '<th scope="row">' . esc_html($menu_item[0]) . '</th>';
        echo '<td>';
        echo '<input type="checkbox" name="allowed_menu_items[]" value="' . esc_attr($menu_slug) . '" ' . $checked . ' />';
        echo '</td>';
        echo '</tr>';
    }
    echo '</table>';

    // Display role permissions (read, edit, delete)
    echo '<h3>Role Permissions</h3>';
    echo '<table class="form-table">';
    $permissions = ['read', 'edit', 'delete'];
    foreach ($permissions as $permission) {
        $checked = isset($role_permissions[$permission]) ? 'checked' : '';
        echo '<tr>';
        echo '<th scope="row">' . ucfirst($permission) . ' Access</th>';
        echo '<td>';
        echo '<input type="checkbox" name="role_permissions[' . esc_attr($permission) . ']" value="1" ' . $checked . ' />';
        echo '</td>';
        echo '</tr>';
    }
    echo '</table>';

    echo '<p class="submit"><input type="submit" name="save_permissions" class="button-primary" value="Save Changes" /></p>';
    echo '</form>';
    echo '</div>';
}

// Apply role permissions dynamically
add_action('admin_init', 'cap_apply_role_permissions');
function cap_apply_role_permissions() {
    $role_permissions = get_option('cap_role_permissions', []);
    $role = get_role('custom_user_role');

    // Set or unset permissions
    foreach (['read', 'edit_posts', 'delete_posts'] as $cap) {
        if (isset($role_permissions[str_replace('_posts', '', $cap)])) {
            $role->add_cap($cap);
        } else {
            $role->remove_cap($cap);
        }
    }
}