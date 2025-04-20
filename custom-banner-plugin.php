<?php
/*
Plugin Name: Custom Banner Plugin
Description: Adds banners to promote deals or announcements.
Version: 1.1
Author: PRAKRITI DUTTA
*/

// ========== 1. Display Banner in Footer ==========
add_action('wp_footer', 'cbp_display_banner');
function cbp_display_banner() {
   $default_text = '🎉 Big Sale! Get 25% Off – Limited Time Offer! 🎉';
$default_button_text = 'Shop Now';
$default_button_link = '#';

$text = get_option('cbp_banner_text');
$button_text = get_option('cbp_button_text');
$button_link = get_option('cbp_button_link');

$text = !empty($text) ? $text : $default_text;
$button_text = !empty($button_text) ? $button_text : $default_button_text;
$button_link = !empty($button_link) ? $button_link : $default_button_link;

    echo '
    <div id="custom-banner">
        <p>' . esc_html($text) . ' <a href="' . esc_url($button_link) . '" class="cbp-btn">' . esc_html($button_text) . '</a></p>
    </div>
    <style>
        #custom-banner {
            position: fixed;
            bottom: 0;
            width: 100%;
            background: linear-gradient(to right, #ff5722, #ff9800);
            color: white;
            text-align: center;
            padding: 15px;
            font-size: 18px;
            z-index: 9999;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.2);
        }
        .cbp-btn {
            background: #fff;
            color: #ff5722;
            padding: 8px 16px;
            text-decoration: none;
            margin-left: 10px;
            border-radius: 5px;
            font-weight: bold;
        }
        .cbp-btn:hover {
            background: #ffe0b2;
        }
    </style>';
}

// ========== 2. Add "Settings" link under Plugin ==========
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'cbp_settings_link');
function cbp_settings_link($links) {
    $settings_link = '<a href="options-general.php?page=cbp-settings">Settings</a>';
    array_unshift($links, $settings_link);
    return $links;
}

// ========== 3. Add Settings Page in Admin ==========
add_action('admin_menu', 'cbp_add_settings_page');
function cbp_add_settings_page() {
    add_options_page(
        'Custom Banner Settings',     // Page Title
        'Banner Settings',            // Menu Title
        'manage_options',             // Capability
        'cbp-settings',               // Menu Slug
        'cbp_render_settings_page'    // Callback
    );
}

// ========== 4. Register Settings ==========
add_action('admin_init', 'cbp_register_settings');
function cbp_register_settings() {
    register_setting('cbp_settings_group', 'cbp_banner_text');
    register_setting('cbp_settings_group', 'cbp_button_text');
    register_setting('cbp_settings_group', 'cbp_button_link');
}

// ========== 5. Render Settings Page ==========
function cbp_render_settings_page() {
    ?>
    <div class="wrap">
        <h1>Custom Banner Settings</h1>
        <form method="post" action="options.php">
            <?php
                settings_fields('cbp_settings_group');
                do_settings_sections('cbp_settings_group');
            ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Banner Text</th>
                    <td><input type="text" name="cbp_banner_text" value="<?php echo esc_attr(get_option('cbp_banner_text')); ?>" style="width: 100%;" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Button Text</th>
                    <td><input type="text" name="cbp_button_text" value="<?php echo esc_attr(get_option('cbp_button_text')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Button Link</th>
                    <td><input type="url" name="cbp_button_link" value="<?php echo esc_attr(get_option('cbp_button_link')); ?>" style="width: 100%;" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}
