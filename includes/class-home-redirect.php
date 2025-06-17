<?php
/**
 * Home Redirect Class
 * Handles home page redirection functionality
 */

if (!defined('ABSPATH')) {
    exit;
}

class CustomHomeRedirect {
    
    public function __construct() {
        add_action('template_redirect', [$this, 'handle_home_redirect'], 1);
    }
    
    /**
     * Handle home page redirection
     */
    public function handle_home_redirect() {
        // Only run on frontend requests
        if (is_admin() || defined('DOING_AJAX') || defined('DOING_CRON')) {
            return;
        }
        
        $home_redirect = get_option('custom_home_redirect', []);
        
        if (empty($home_redirect['enabled'])) {
            return;
        }
        
        // Check if we're on the home page
        if (!$this->is_home_page()) {
            return;
        }
        
        // Prevent redirect loops
        if (isset($_GET['no_redirect']) || isset($_GET['custom_redirect_done'])) {
            return;
        }
        
        $this->execute_redirect($home_redirect);
    }
    
    /**
     * Check if current request is for home page
     */
    private function is_home_page() {
        $request_uri = trim($_SERVER['REQUEST_URI'], '/');
        $home_path = trim(parse_url(home_url(), PHP_URL_PATH), '/');
        
        // Remove query string for comparison
        $request_path = explode('?', $request_uri)[0];
        $request_path = trim($request_path, '/');
        
        // Check if this is the home page
        return ($request_path === $home_path || $request_path === '');
    }
    
    /**
     * Execute the redirect based on settings
     */
    private function execute_redirect($home_redirect) {
        if ($home_redirect['type'] === 'wp_reading') {
            $this->redirect_to_wp_reading_page();
        } elseif ($home_redirect['type'] === 'custom' && !empty($home_redirect['custom_page'])) {
            $this->redirect_to_custom_page($home_redirect['custom_page']);
        }
    }
    
    /**
     * Redirect to WordPress reading page
     */
    private function redirect_to_wp_reading_page() {
        $page_on_front = get_option('page_on_front');
        if ($page_on_front) {
            $redirect_url = get_permalink($page_on_front);
            if (!empty($redirect_url) && $redirect_url !== home_url('/')) {
                wp_redirect($redirect_url, 302);
                exit;
            }
        }
    }
    
    /**
     * Redirect to custom page
     */
    private function redirect_to_custom_page($custom_page) {
        $custom_page = trim($custom_page);
        
        // Check if it's a full URL
        if (filter_var($custom_page, FILTER_VALIDATE_URL)) {
            wp_redirect($custom_page, 302);
            exit;
        }
        
        // Handle file paths
        if (strpos($custom_page, '.php') !== false || strpos($custom_page, '.html') !== false) {
            $this->serve_custom_file($custom_page);
        } else {
            // Treat as relative URL path
            $redirect_url = home_url(ltrim($custom_page, '/'));
            wp_redirect($redirect_url, 302);
            exit;
        }
    }
    
    /**
     * Serve custom file directly
     */
    private function serve_custom_file($custom_page) {
        $file_path = ABSPATH . ltrim($custom_page, '/');
        
        if (file_exists($file_path)) {
            // Set headers to prevent caching issues
            if (!headers_sent()) {
                header('Cache-Control: no-cache, must-revalidate');
                header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
            }
            
            // Serve the file directly
            if (strpos($custom_page, '.html') !== false) {
                if (!headers_sent()) {
                    header('Content-Type: text/html; charset=UTF-8');
                }
                readfile($file_path);
            } else {
                // For PHP files, include them
                include($file_path);
            }
            exit;
        } else {
            // File doesn't exist, show error
            wp_die('Custom redirect file not found: ' . esc_html($custom_page) . '<br>Looking for: ' . esc_html($file_path));
        }
    }
}