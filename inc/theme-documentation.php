<?php

/**
 * Theme Documentation Page
 *
 * @package Fossil
 */

// Exit if accessed directly.
if (! defined('ABSPATH')) {
    exit;
}

/**
 * Theme Documentation Class
 */
class Fossil_Theme_Documentation
{

    /**
     * Constructor
     */
    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_theme_page'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_ajax_fossil_handle_plugin_action', array($this, 'handle_plugin_action'));

        // Add admin notice for documentation
        add_action('admin_notices', array($this, 'display_documentation_notice'));
        add_action('wp_ajax_fossil_dismiss_documentation_notice', array($this, 'dismiss_documentation_notice'));
    }


    /**
     * Get plugin data from WordPress.org API
     *
     * @param string $plugin_slug Plugin slug
     * @param array $fields Fields to retrieve (optional)
     * @return object|WP_Error Plugin API data or WP_Error on failure
     */
    public function get_plugin_api_data($plugin_slug, $fields = array())
    {
        // Load plugins_api if not available
        if (! function_exists('plugins_api')) {
            require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
        }

        // Set default fields if none provided
        if (empty($fields)) {
            $fields = array(
                'short_description' => true,
                'icons' => true,
                'banners' => true,
                'sections' => false,
                'requires' => false,
                'rating' => false,
                'ratings' => false,
                'downloaded' => false,
                'last_updated' => false,
                'added' => false,
                'tags' => false,
                'compatibility' => false,
                'homepage' => false,
                'donate_link' => false,
            );
        }

        // Call the WordPress.org API
        return plugins_api(
            'plugin_information',
            array(
                'slug' => $plugin_slug,
                'fields' => $fields,
            )
        );
    }


    /**
     * Add theme documentation page to admin menu
     */
    public function add_theme_page()
    {
        add_theme_page(
            esc_html__('Fossil Documentation', 'fossil'),
            esc_html__('Fossil Documentation', 'fossil'),
            'edit_theme_options',
            'fossil-documentation',
            array($this, 'render_documentation_page')
        );
    }

    /**
     * Enqueue scripts and styles for the documentation page
     */
    public function enqueue_scripts($hook)
    {
        // Always enqueue jQuery for admin notices
        wp_enqueue_script('jquery');

        // Only enqueue documentation specific scripts on the documentation page
        if ('appearance_page_fossil-documentation' !== $hook) {
            return;
        }

        wp_enqueue_style(
            'fossil-documentation',
            get_template_directory_uri() . '/assets/css/admin/documentation.css',
            array(),
            '1.0.0'
        );

        wp_enqueue_script(
            'fossil-documentation',
            get_template_directory_uri() . '/assets/js/admin/documentation.js',
            array('jquery'),
            '1.0.0',
            true
        );

        wp_localize_script(
            'fossil-documentation',
            'fossilDocs',
            array(
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce'   => wp_create_nonce('fossil_plugin_action_nonce'),
            )
        );
    }

    /**
     * Get recommended plugins
     *
     * @return array List of recommended plugins
     */
    public function get_recommended_plugins()
    {
        $plugins = array(
            'elementor',
            'one-click-demo-import',
            'be-boost',
            'magical-addons-for-elementor',
            'magical-posts-display',
            'wp-edit-password-protected',
            'magical-blocks',
            'contact-form-7'

        );

        $recommended_plugins = array();

        foreach ($plugins as $plugin_slug) {
            $plugin_info = $this->get_plugin_info($plugin_slug);
            $recommended_plugins[$plugin_slug] = $plugin_info;
        }

        return $recommended_plugins;
    }

    /**
     * Get plugin information
     *
     * @param string $plugin_slug Plugin slug
     * @return array Plugin information
     */
    public function get_plugin_info($plugin_slug)
    {
        // Default values in case API request fails
        $name = ucwords(str_replace('-', ' ', $plugin_slug));
        $description = '';
        $thumbnail = 'https://ps.w.org/' . $plugin_slug . '/assets/icon-256x256.png';

        // Get plugin data from API
        $api = $this->get_plugin_api_data($plugin_slug, array(
            'short_description' => true,
            'icons' => true,
            'banners' => true,
        ));

        if (! is_wp_error($api)) {
            // Get plugin name
            $name = $api->name;

            // Get plugin description
            $description = isset($api->short_description) ? $api->short_description : '';

            // Get plugin icon from WordPress.org
            if (isset($api->icons['2x'])) {
                $thumbnail = $api->icons['2x'];
            } elseif (isset($api->icons['1x'])) {
                $thumbnail = $api->icons['1x'];
            } elseif (isset($api->banners['high'])) {
                $thumbnail = $api->banners['high'];
            } elseif (isset($api->banners['low'])) {
                $thumbnail = $api->banners['low'];
            }
        }

        return array(
            'name'        => esc_html($name),
            'slug'        => $plugin_slug,
            'description' => esc_html($description),
            'thumbnail'   => esc_url($thumbnail),
        );
    }

    /**
     * Check plugin status
     *
     * @param string $plugin_slug Plugin slug
     * @return string Plugin status (not-installed, inactive, active)
     */
    public function get_plugin_status($plugin_slug)
    {
        $plugin_path = $this->get_plugin_path($plugin_slug);

        if (! file_exists(WP_PLUGIN_DIR . '/' . $plugin_path)) {
            return 'not-installed';
        }

        if (is_plugin_active($plugin_path)) {
            return 'active';
        }

        return 'inactive';
    }

    /**
     * Get plugin path
     *
     * @param string $plugin_slug Plugin slug
     * @return string Plugin path
     */
    public function get_plugin_path($plugin_slug)
    {
        // First check if the plugin is installed and get its actual path
        $plugins = get_plugins();

        // Look through installed plugins to find a match
        foreach ($plugins as $path => $data) {
            // Check if the plugin path contains the slug
            if (strpos($path, $plugin_slug . '/') === 0 || $path === $plugin_slug . '.php') {
                return $path;
            }
        }

        // If not found in installed plugins, try to get from API
        $api = $this->get_plugin_api_data($plugin_slug, array(
            'sections' => false,
            'requires' => false,
            'rating' => false,
            'ratings' => false,
            'downloaded' => false,
            'last_updated' => false,
            'added' => false,
            'tags' => false,
            'compatibility' => false,
            'homepage' => false,
            'donate_link' => false,
        ));

        // If API provides the plugin file, use it
        if (! is_wp_error($api) && isset($api->plugin_file)) {
            return $plugin_slug . '/' . $api->plugin_file;
        }

        // Fallback for common plugins with known file structures
        $known_plugins = array(
            'elementor' => 'elementor/elementor.php',
            'one-click-demo-import' => 'one-click-demo-import/one-click-demo-import.php',
            'contact-form-7' => 'contact-form-7/wp-contact-form-7.php',
            'woocommerce' => 'woocommerce/woocommerce.php',
            'advanced-custom-fields' => 'advanced-custom-fields/acf.php',
            'wordpress-seo' => 'wordpress-seo/wp-seo.php',
            'jetpack' => 'jetpack/jetpack.php',
        );

        if (isset($known_plugins[$plugin_slug])) {
            return $known_plugins[$plugin_slug];
        }

        // Default fallback - most plugins follow the pattern: slug/slug.php
        return $plugin_slug . '/' . $plugin_slug . '.php';
    }

    /**
     * Get plugin action button
     *
     * @param string $plugin_slug Plugin slug
     * @param string $plugin_status Plugin status
     * @return string Button HTML
     */
    public function get_plugin_action_button($plugin_slug, $plugin_status)
    {
        $button_class = 'fossil-plugin-button';
        $button_text = '';
        $button_attr = '';

        switch ($plugin_status) {
            case 'not-installed':
                $button_class .= ' install-now';
                $button_text = esc_html__('Install', 'fossil');
                $button_attr = ' data-action="install" data-slug="' . esc_attr($plugin_slug) . '"';
                break;
            case 'inactive':
                $button_class .= ' activate-now';
                $button_text = esc_html__('Activate', 'fossil');
                $button_attr = ' data-action="activate" data-slug="' . esc_attr($plugin_slug) . '"';
                break;
            case 'active':
                $button_class .= ' disabled';
                $button_text = esc_html__('Already Active', 'fossil');
                $button_attr = ' disabled';
                break;
        }

        return '<button class="' . esc_attr($button_class) . '"' . $button_attr . '>' . $button_text . '</button>';
    }

    /**
     * Handle plugin installation and activation via AJAX
     */
    public function handle_plugin_action()
    {
        // Check nonce
        if (! isset($_POST['nonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'fossil_plugin_action_nonce')) {
            wp_send_json_error(array('message' => esc_html__('Security check failed', 'fossil')));
        }

        // Check user capabilities
        if (! current_user_can('install_plugins')) {
            wp_send_json_error(array('message' => esc_html__('You do not have permission to install plugins', 'fossil')));
        }

        // Get action and plugin slug
        $action = isset($_POST['plugin_action']) ? sanitize_text_field(wp_unslash($_POST['plugin_action'])) : '';
        $slug = isset($_POST['plugin_slug']) ? sanitize_text_field(wp_unslash($_POST['plugin_slug'])) : '';

        if (empty($action) || empty($slug)) {
            wp_send_json_error(array('message' => esc_html__('Missing required parameters', 'fossil')));
        }

        // Handle plugin installation
        if ('install' === $action) {
            include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
            include_once ABSPATH . 'wp-admin/includes/plugin-install.php';

            $api = $this->get_plugin_api_data($slug, array(
                'short_description' => false,
                'sections' => false,
                'requires' => false,
                'rating' => false,
                'ratings' => false,
                'downloaded' => false,
                'last_updated' => false,
                'added' => false,
                'tags' => false,
                'compatibility' => false,
                'homepage' => false,
                'donate_link' => false,
            ));

            if (is_wp_error($api)) {
                wp_send_json_error(array('message' => $api->get_error_message()));
            }

            $upgrader = new Plugin_Upgrader(new WP_Ajax_Upgrader_Skin());
            $result = $upgrader->install($api->download_link);

            if (is_wp_error($result)) {
                wp_send_json_error(array('message' => $result->get_error_message()));
            }

            // Activate plugin after installation
            $plugin_path = $this->get_plugin_path($slug);
            $activate = activate_plugin($plugin_path);

            if (is_wp_error($activate)) {
                wp_send_json_error(array('message' => $activate->get_error_message()));
            }

            wp_send_json_success(array(
                'message' => esc_html__('Plugin installed and activated successfully', 'fossil'),
                'status' => 'active',
                'button' => $this->get_plugin_action_button($slug, 'active')
            ));
        }

        // Handle plugin activation
        if ('activate' === $action) {
            $plugin_path = $this->get_plugin_path($slug);
            $activate = activate_plugin($plugin_path);

            if (is_wp_error($activate)) {
                wp_send_json_error(array('message' => $activate->get_error_message()));
            }

            wp_send_json_success(array(
                'message' => esc_html__('Plugin activated successfully', 'fossil'),
                'status' => 'active',
                'button' => $this->get_plugin_action_button($slug, 'active')
            ));
        }

        wp_send_json_error(array('message' => esc_html__('Invalid action', 'fossil')));
    }

    /**
     * Render the documentation page
     */
    public function render_documentation_page()
    {
        $pro_link = 'https://wpthemespace.com/product/fossil-pro-best-wordpress-theme-for-ev-charging-and-gas-stations/?add-to-cart=13340';
?>
        <div class="wrap fossil-documentation">
            <h1><?php esc_html_e('Fossil Theme Documentation', 'fossil'); ?></h1>

            <div class="fossil-documentation-content">
                <!-- Pro Version Banner -->
                <div class="fossil-pro-banner">
                    <div class="fossil-pro-banner-content">
                        <h2><?php esc_html_e('Upgrade to Fossil Pro', 'fossil'); ?></h2>
                        <p><?php esc_html_e('Get access to premium features, priority support, and more with Fossil Pro!', 'fossil'); ?></p>
                    </div>
                    <a href="https://wpthemespace.com/product/fossil-pro-best-wordpress-theme-for-ev-charging-and-gas-stations/?add-to-cart=13340" class="button" target="_blank"><?php esc_html_e('Upgrade Now', 'fossil'); ?></a>
                </div>

                <div class="fossil-documentation-section">
                    <h2><?php esc_html_e('Welcome to Fossil Theme', 'fossil'); ?></h2>
                    <p><?php esc_html_e('Thank you for choosing Fossil! This documentation will help you set up and customize your website.', 'fossil'); ?></p>
                </div>

                <!-- Recommended Plugins Section -->
                <div class="fossil-documentation-section">
                    <h2><?php esc_html_e('Install Recommended Plugins', 'fossil'); ?></h2>
                    <p><?php esc_html_e('The following plugins are recommended to use with the Fossil theme to get the most out of its features:', 'fossil'); ?></p>

                    <div class="fossil-recommended-plugins">
                        <?php
                        $recommended_plugins = $this->get_recommended_plugins();
                        foreach ($recommended_plugins as $plugin) :
                        ?>
                            <div class="fossil-plugin-card">
                                <div class="fossil-plugin-thumbnail">
                                    <img src="<?php echo esc_url($plugin['thumbnail']); ?>" alt="<?php echo esc_attr($plugin['name']); ?>">
                                </div>
                                <div class="fossil-plugin-info">
                                    <h3><?php echo esc_html($plugin['name']); ?></h3>
                                    <p><?php echo esc_html($plugin['description']); ?></p>
                                    <div class="fossil-plugin-actions">
                                        <?php echo $this->get_plugin_action_button($plugin['slug'], $this->get_plugin_status($plugin['slug'])); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Demo Content Import Section -->
                <div class="fossil-documentation-section">
                    <h2><?php esc_html_e('Demo Content Import', 'fossil'); ?></h2>
                    <p><?php esc_html_e('Import demo content to make your website look like our demo sites. This will give you a great starting point for your website.', 'fossil'); ?></p>

                    <div class="fossil-notice">
                        <p><?php esc_html_e('Note: Before importing demo content, make sure you have installed all the recommended plugins.', 'fossil'); ?></p>
                    </div>

                    <h3><?php esc_html_e('Available Demo Sites', 'fossil'); ?></h3>
                    <div class="fossil-demo-previews">
                        <div class="fossil-demo-preview">
                            <div class="fossil-demo-image">
                                <img src="<?php echo esc_url('https://fullsitediting.com/fossil/free/wp-content/uploads/sites/5/2025/05/fossil-free1.jpg'); ?>" alt=" <?php esc_attr_e('Main Demo', 'fossil'); ?>">
                            </div>
                            <h3><?php esc_html_e('Free Demo', 'fossil'); ?></h3>
                            <a href="https://fullsitediting.com/fossil/free/" class="button button-primary" target="_blank">
                                <?php esc_html_e('Preview Demo', 'fossil'); ?></a>
                            <?php if (is_plugin_active('one-click-demo-import/one-click-demo-import.php')): ?>
                                <a href="<?php echo esc_url(admin_url('themes.php?page=one-click-demo-import')); ?>" class="button fossil-import-demo" target="_blank"><?php esc_html_e('Import Demo', 'fossil'); ?></a>
                            <?php endif; ?>
                        </div>

                        <div class="fossil-demo-preview">
                            <div class="fossil-demo-image">
                                <img src="<?php echo esc_url('https://fullsitediting.com/fossil/free/wp-content/uploads/sites/5/2024/12/home1.jpg'); ?>" alt="<?php esc_attr_e('Business Demo', 'fossil'); ?>">
                            </div>
                            <h3><?php esc_html_e('Pro Demo One', 'fossil'); ?></h3>
                            <a href="https://fullsitediting.com/fossil" class="button button-primary" target="_blank"><?php esc_html_e('Preview Demo', 'fossil'); ?></a>
                            <a href="<?php echo esc_url($pro_link); ?>" class="button fossil-import-demo fossil-import-pro "><?php esc_html_e('Upgrade Pro', 'fossil'); ?></a>
                        </div>

                        <div class="fossil-demo-preview">
                            <div class="fossil-demo-image">
                                <img src="<?php echo esc_url('https://fullsitediting.com/fossil/free/wp-content/uploads/sites/5/2024/12/home2.jpg'); ?>" alt="<?php esc_attr_e('Agency Demo', 'fossil'); ?>">
                            </div>
                            <h3><?php esc_html_e('Pro Demo Two', 'fossil'); ?></h3>
                            <a href="https://fullsitediting.com/fossil/home-two/" class="button button-primary" target="_blank"><?php esc_html_e('Preview Demo', 'fossil'); ?></a>
                            <a href="<?php echo esc_url($pro_link); ?>" class="button fossil-import-demo fossil-import-pro"><?php esc_html_e('Upgrade Pro', 'fossil'); ?></a>
                        </div>
                        <div class="fossil-demo-preview">
                            <div class="fossil-demo-image">
                                <img src="<?php echo esc_url('https://fullsitediting.com/fossil/free/wp-content/uploads/sites/5/2024/12/home3.jpg'); ?>" alt="<?php esc_attr_e('Agency Demo', 'fossil'); ?>">
                            </div>
                            <h3><?php esc_html_e('Pro Demo Three', 'fossil'); ?></h3>
                            <a href="https://fullsitediting.com/fossil/home-three/" class="button button-primary" target="_blank"><?php esc_html_e('Preview Demo', 'fossil'); ?></a>
                            <a href="<?php echo esc_url($pro_link); ?>" class="button fossil-import-demo fossil-import-pro"><?php esc_html_e('Upgrade Pro', 'fossil'); ?></a>
                        </div>
                    </div>
                </div>

                <!-- How to Customize Section -->
                <div class="fossil-documentation-section">
                    <h2><?php esc_html_e('How to Customize', 'fossil'); ?></h2>
                    <p><strong><?php esc_html_e('You can customize various aspects of the Fossil theme through the WordPress Customizer:', 'fossil'); ?></strong></p>

                    <h3><?php esc_html_e('Using the WordPress Customizer', 'fossil'); ?></h3>
                    <ol>
                        <li><?php esc_html_e('Go to Appearance → Customize to access the WordPress Customizer.', 'fossil'); ?></li>
                        <li><?php esc_html_e('Use the various panels and sections to customize your site\'s appearance.', 'fossil'); ?></li>
                        <li><?php esc_html_e('Changes will be visible in the preview window before you publish them.', 'fossil'); ?></li>
                    </ol>

                    <h3><?php esc_html_e('Customizer Options', 'fossil'); ?></h3>
                    <p><strong><?php esc_html_e('The Fossil theme provides the following customizer sections:', 'fossil'); ?></strong>
                    </p>
                    <ul class="fossil-customizer-sections">
                        <li><?php esc_html_e('General Settings - Basic theme configuration options', 'fossil'); ?></li>
                        <li><?php esc_html_e('Fossil Typography - Font family and size settings', 'fossil'); ?></li>
                        <li><?php esc_html_e('Theme Color - Primary and secondary color options', 'fossil'); ?></li>
                        <li><?php esc_html_e('Fossil Header Top Settings - Top header bar configuration', 'fossil'); ?></li>
                        <li><?php esc_html_e('Header Settings - Main header layout and options', 'fossil'); ?></li>
                        <li><?php esc_html_e('Fossil Blog Settings - Blog layout and display options', 'fossil'); ?></li>
                        <li><?php esc_html_e('Fossil Page Settings - Page layout configuration', 'fossil'); ?></li>
                        <li><?php esc_html_e('Fossil Top Footer Settings - Top footer area options', 'fossil'); ?></li>
                        <li><?php esc_html_e('Fossil Footer Settings - Main footer configuration', 'fossil'); ?></li>
                    </ul>

                    <div class="fossil-customizer-link">
                        <a href="<?php echo esc_url(admin_url('customize.php')); ?>" class="button button-primary" target="_blank"><?php esc_html_e('Open Customizer', 'fossil'); ?></a>
                    </div>

                    <h3><?php esc_html_e('Editable Files', 'fossil'); ?></h3>
                    <p> <strong><?php esc_html_e('For advanced customization, you can edit the following files:', 'fossil'); ?></strong>
                    </p>
                    <ul>
                        <li><?php esc_html_e('CSS files - For styling changes', 'fossil'); ?></li>
                        <li><?php esc_html_e('Template files - For layout changes', 'fossil'); ?></li>
                        <li><?php esc_html_e('Customizer files - For adding new customizer options', 'fossil'); ?></li>
                    </ul>

                    <div class="fossil-notice">
                        <p><?php esc_html_e('Note: It is recommended to create a child theme before making any code modifications to avoid losing your changes during theme updates.', 'fossil'); ?></p>
                    </div>

                    <a href="<?php echo esc_url(admin_url('customize.php')); ?>" class="button button-primary"><?php esc_html_e('Open Customizer', 'fossil'); ?></a>
                </div>

                <!-- Support Section -->
                <div class="fossil-documentation-section">
                    <h2><?php esc_html_e('Support', 'fossil'); ?></h2>
                    <p><?php esc_html_e('If you need help with the Fossil theme, our support team is here to assist you:', 'fossil'); ?></p>

                    <div class="fossil-support-options">
                        <div class="fossil-support-option">
                            <h3><?php esc_html_e('Documentation', 'fossil'); ?></h3>
                            <p><?php esc_html_e('Check our comprehensive documentation for detailed instructions on how to use the theme.', 'fossil'); ?></p>
                            <a href="https://fullsitediting.com/fossil/doc/docs/fossil-documentation/theme-installation/" class="button" target="_blank"><?php esc_html_e('View Documentation', 'fossil'); ?></a>
                        </div>

                        <div class="fossil-support-option">
                            <h3><?php esc_html_e('Support Forum', 'fossil'); ?></h3>
                            <p><?php esc_html_e('Join our community forum to ask questions and get help from other users and our support team.', 'fossil'); ?></p>
                            <a href="https://wpthemespace.com/support-forums/" class="button" target="_blank"><?php esc_html_e('Visit Support Forum', 'fossil'); ?></a>
                        </div>

                        <div class="fossil-support-option">
                            <h3><?php esc_html_e('Contact Support', 'fossil'); ?></h3>
                            <p><?php esc_html_e('Need direct assistance? Contact our support team via email or support ticket.', 'fossil'); ?></p>
                            <a href="https://wpthemespace.com/contact-us/" class="button button-primary" target="_blank"><?php esc_html_e('Contact Support', 'fossil'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }
    /**
     * Display documentation notice
     */
    public function display_documentation_notice()
    {
        // Check if notice is dismissed
        if (get_option('fossil_documentation_dismissed_notice1')) {
            return;
        }

        // Only show notice to admins
        if (!current_user_can('manage_options')) {
            return;
        }
        // Get current screen
        $screen = get_current_screen();

        // Don't show notice on the documentation page itself
        if ($screen && $screen->id === 'appearance_page_fossil-documentation') {
            return;
        }

        // Create nonce for AJAX request
        $nonce = wp_create_nonce('fossil_documentation_notice_nonce');
    ?>
        <div class="notice notice-info is-dismissible fossil-documentation-notice">
            <h3><?php esc_html_e('Welcome to Fossil WordPress Theme! Get Started with Fossil in Minutes!', 'fossil'); ?></h3>
            <p>
                <?php esc_html_e('Thank you for choosing the Fossil WordPress Theme — a lightweight, responsive WordPress theme built for performance and style.
To get started: ', 'fossil'); ?>
            <ul>
                <li><?php esc_html_e('Visit the Setup Guide to install required plugins and configure your theme settings.', 'fossil'); ?></li>
                <li><?php esc_html_e('Use the Demo Import feature to instantly create a website that looks just like our live demo.', 'fossil'); ?></li>
                <li><?php esc_html_e('Customize your content and layout using the WordPress Customizer or your favorite page builder.', 'fossil'); ?></li>
            </ul>
            </p>
            <p>
                <a href="<?php echo esc_url(admin_url('themes.php?page=fossil-documentation')); ?>" class="button button-primary fn-btn">
                    <?php esc_html_e('Quick Setup and Demo Import', 'fossil'); ?>
                </a>
                <a href="#" class="fossil-dismiss-notice" data-nonce="<?php echo esc_attr($nonce); ?>" style="margin-left: 10px;">
                    <?php esc_html_e('Dismiss this notice', 'fossil'); ?>
                </a>
            </p>
        </div>
<?php
    }

    /**
     * Dismiss documentation notice
     */
    public function dismiss_documentation_notice()
    {
        // Check nonce
        if (! isset($_POST['nonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'fossil_documentation_notice_nonce')) {
            wp_send_json_error(array('message' => esc_html__('Security check failed', 'fossil')));
        }

        // Update option to dismiss notice
        update_option('fossil_documentation_dismissed_notice1', true);

        wp_send_json_success();
    }
}

// Initialize the class.
new Fossil_Theme_Documentation();
