<?php
/**
 * Plugin Name: Custom Admin Permissions
 * Description: Control the admin menu and permissions for a new user type, including WooCommerce product creation and selective plugin access.
 * Version: 1.0
 * Author: Death301
 */

// Hook to initialize the plugin
add_action('init', 'cap_register_custom_user_role');

// Function to register a new user role
function cap_register_custom_user_role() {
    add_role('custom_user_role', 'Custom User', [
        'read' => true,
        'edit_posts' => true,
        'edit_products' => true,
        'publish_products' => true,
        'upload_files' => true,
        'view_woocommerce_reports' => true,
        'manage_woocommerce' => true,
    ]);
}

// Hook to adjust the admin menu
add_action('admin_menu', 'cap_customize_admin_menu', 999);

function cap_customize_admin_menu() {
    // Check if the current user has the custom user role
    if (current_user_can('custom_user_role')) {
        // Remove unnecessary menu items
        remove_menu_page('tools.php');
        remove_menu_page('options-general.php');
        remove_menu_page('edit.php');
        remove_menu_page('edit-comments.php');
        // Add or remove other menus as necessary
    }
}

// Hook to control plugin access
add_filter('option_active_plugins', 'cap_control_plugin_access');

function cap_control_plugin_access($plugins) {
    if (current_user_can('custom_user_role')) {
        // Define allowed plugins
        $allowed_plugins = get_option('cap_allowed_plugins', []);

        // Filter plugins based on the allowed list
        foreach ($plugins as $key => $plugin) {
            if (!in_array($plugin, $allowed_plugins)) {
                unset($plugins[$key]);
            }
        }
    }
    return $plugins;
}

// Add an admin page for setting allowed plugins
add_action('admin_menu', 'cap_add_plugin_access_page');

function cap_add_plugin_access_page() {
    add_menu_page(
        'Custom User Permissions',
        'User Permissions',
        'manage_options',
        'cap-permissions',
        'cap_render_plugin_access_page'
    );
}

function cap_render_plugin_access_page() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    if (isset($_POST['save_plugins'])) {
        update_option('cap_allowed_plugins', $_POST['allowed_plugins']);
        echo '<div class="updated"><p>Settings saved.</p></div>';
    }

    $all_plugins = get_plugins();
    $allowed_plugins = get_option('cap_allowed_plugins', []);

    echo '<div class="wrap">';
    echo '<h2>Select Plugins for Custom User</h2>';
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
    echo '<p class="submit"><input type="submit" name="save_plugins" class="button-primary" value="Save Changes" /></p>';
    echo '</form>';
    echo '</div>';
}
