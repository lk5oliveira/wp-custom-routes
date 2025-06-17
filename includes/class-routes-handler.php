<?php
/**
 * Routes Handler Class
 * Handles custom route routing and file serving
 */

if (!defined('ABSPATH')) {
    exit;
}

class CustomRoutesHandler {
    
    public function __construct() {
        add_action('template_redirect', [$this, 'handle_routes']);
        add_action('admin_init', [$this, 'flush_rules_if_needed']);
    }
    
    /**
     * Handle custom routes
     */
    public function handle_routes() {
        $routes = get_option('custom_routes', []);
        if (empty($routes)) {
            return;
        }
        
        $requested_path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        
        foreach ($routes as $route) {
            if ($route['path'] === $requested_path) {
                $this->serve_route($route);
            }
        }
    }
    
    /**
     * Serve a specific route
     */
    private function serve_route($route) {
        $file_path = ABSPATH . $route['file'];
        
        if (!file_exists($file_path)) {
            status_header(404);
            echo 'The requested page does not exist.';
            exit;
        }
        
        if ($route['type'] === 'html') {
            header('Content-Type: text/html');
            readfile($file_path);
        } elseif ($route['type'] === 'php') {
            include($file_path);
        }
        
        exit;
    }
    
    /**
     * Flush rewrite rules if needed
     */
    public function flush_rules_if_needed() {
        if (get_option('custom_routes_flush_needed')) {
            flush_rewrite_rules();
            delete_option('custom_routes_flush_needed');
        }
    }
}