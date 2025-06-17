<?php
/**
 * Plugin Name: Custom Routes Manager
 * Description: Manage custom page routes through WordPress admin with modern UI
 * Version: 2.2
 * Author: Lucas Oliveira
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Include required files
require_once plugin_dir_path(__FILE__) . 'includes/class-routes-handler.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-home-redirect.php';
require_once plugin_dir_path(__FILE__) . 'admin/class-admin-interface.php';

/**
 * Main plugin class
 */
class CustomRoutesManager {
    
    private $routes_handler;
    private $home_redirect;
    private $admin_interface;
    
    public function __construct() {
        $this->init();
    }
    
    private function init() {
        // Initialize components
        $this->routes_handler = new CustomRoutesHandler();
        $this->home_redirect = new CustomHomeRedirect();
        $this->admin_interface = new CustomRoutesAdmin();
        
        // Register activation/deactivation hooks
        register_activation_hook(__FILE__, [$this, 'activate']);
        register_deactivation_hook(__FILE__, [$this, 'deactivate']);
    }
    
    public function activate() {
        // Create default routes on activation
        $default_routes = [
            [
                'path' => 'dashboard',
                'file' => 'custom-pages/dashboard.php',
                'type' => 'php'
            ]
        ];
        
        if (!get_option('custom_routes')) {
            update_option('custom_routes', $default_routes);
        }
        
        // Set default home redirect settings
        if (!get_option('custom_home_redirect')) {
            update_option('custom_home_redirect', [
                'enabled' => false,
                'type' => 'custom',
                'custom_page' => ''
            ]);
        }
    }
    
    public function deactivate() {
        flush_rewrite_rules();
    }
}

// Initialize the plugin
new CustomRoutesManager();