<?php
/**
 * Home Redirect Class
 * Handles home page redirection functionality
 */

if (!defined('ABSPATH')) {
    exit;
}

class CustomHomeRedirect {
    
    private $redirect_session_key = 'crm_home_redirect_executed';
    
    public function __construct() {
        add_action('template_redirect', [$this, 'handle_home_redirect'], 1);
        add_action('wp', [$this, 'handle_wp_redirect'], 1);
        add_action('parse_request', [$this, 'handle_parse_request'], 1);
        add_action('init', [$this, 'handle_early_redirect'], 1);
        
        // Add a very early redirect that catches requests before WordPress processes them
        add_action('muplugins_loaded', [$this, 'handle_very_early_redirect'], 1);
        
        // Always log when class is constructed
        error_log('CRM: CustomHomeRedirect class constructed');
    }
    
    /**
     * Safe trim function that handles null values
     */
    private function safe_trim($string, $characters = " \t\n\r\0\x0B") {
        return $string !== null ? trim($string, $characters) : '';
    }
    
    /**
     * Safe ltrim function that handles null values
     */
    private function safe_ltrim($string, $characters = " \t\n\r\0\x0B") {
        return $string !== null ? ltrim($string, $characters) : '';
    }
    
    /**
     * Very early redirect - catches requests before WordPress fully loads
     */
    public function handle_very_early_redirect() {
        error_log('CRM: handle_very_early_redirect called');
        $this->try_redirect('muplugins_loaded');
    }
    
    /**
     * Early redirect handler - try to catch home page earlier
     */
    public function handle_early_redirect() {
        error_log('CRM: handle_early_redirect called');
        $this->try_redirect('init');
    }
    
    /**
     * Handle redirect on wp hook
     */
    public function handle_wp_redirect() {
        error_log('CRM: handle_wp_redirect called on wp hook');
        $this->try_redirect('wp');
    }
    
    /**
     * Handle redirect on parse_request hook
     */
    public function handle_parse_request($wp) {
        error_log('CRM: handle_parse_request called');
        error_log('CRM: WP request: ' . print_r($wp->request ?? 'not set', true));
        
        // Check if this is a home page request
        if (empty($wp->request) || $wp->request === '') {
            error_log('CRM: Empty request detected, attempting redirect');
            $this->try_redirect('parse_request');
        }
    }
    
    /**
     * Try to perform redirect from various hooks
     */
    private function try_redirect($hook_name) {
        error_log('CRM: try_redirect called from hook: ' . $hook_name);
        
        // Skip if admin
        if (is_admin() || defined('DOING_AJAX') || defined('DOING_CRON')) {
            error_log('CRM: Skipping - admin/ajax/cron');
            return;
        }
        
        $home_redirect = get_option('custom_home_redirect', []);
        error_log('CRM: Home redirect settings: ' . print_r($home_redirect, true));
        
        if (empty($home_redirect['enabled'])) {
            error_log('CRM: Home redirect disabled');
            return;
        }
        
        // Simple home page check for early hooks
        $request_uri = $_SERVER['REQUEST_URI'] ?? '';
        $home_url = home_url();
        
        error_log('CRM: Request URI: ' . $request_uri);
        error_log('CRM: Home URL: ' . $home_url);
        
        // Very simple check - if it's root path or has query params on root
        $is_root_request = ($request_uri === '/' || $request_uri === '' || 
                           preg_match('/^\/(\\?.*)?$/', $request_uri));
        
        error_log('CRM: Is root request (' . $hook_name . '): ' . ($is_root_request ? 'yes' : 'no'));
        
        if ($is_root_request) {
            // Additional check - avoid redirect loops
            if (strpos($request_uri, 'no_redirect') !== false || 
                strpos($request_uri, 'crm_redirect_bypass') !== false) {
                error_log('CRM: Bypass parameter detected');
                return;
            }
            
            // Check for recent redirects
            if ($this->has_recent_redirect()) {
                error_log('CRM: Recent redirect detected, skipping');
                return;
            }
            
            error_log('CRM: Executing redirect from ' . $hook_name);
            $this->mark_redirect_execution();
            $this->execute_redirect_direct($home_redirect);
        }
    }
    
    /**
     * Handle home page redirection
     */
    public function handle_home_redirect() {
        // Always log basic info when this function is called
        error_log('CRM: handle_home_redirect called');
        error_log('CRM: REQUEST_URI: ' . ($_SERVER['REQUEST_URI'] ?? 'not set'));
        error_log('CRM: HTTP_HOST: ' . ($_SERVER['HTTP_HOST'] ?? 'not set'));
        error_log('CRM: Current hook: ' . current_action());
        error_log('CRM: Is admin: ' . (is_admin() ? 'yes' : 'no'));
        error_log('CRM: DOING_AJAX: ' . (defined('DOING_AJAX') && DOING_AJAX ? 'yes' : 'no'));
        error_log('CRM: DOING_CRON: ' . (defined('DOING_CRON') && DOING_CRON ? 'yes' : 'no'));
        
        // Only run on frontend requests
        if (is_admin() || defined('DOING_AJAX') || defined('DOING_CRON')) {
            error_log('CRM: Skipping - admin/ajax/cron request');
            return;
        }
        
        $home_redirect = get_option('custom_home_redirect', []);
        error_log('CRM: Home redirect settings loaded: ' . print_r($home_redirect, true));
        
        if (empty($home_redirect['enabled'])) {
            error_log('CRM: Home redirect is disabled');
            return;
        }
        
        error_log('CRM: Home redirect is enabled, checking if on home page');
        
        // Check if we're on the home page
        $is_home = $this->is_home_page();
        error_log('CRM: Is home page result: ' . ($is_home ? 'yes' : 'no'));
        
        if (!$is_home) {
            return;
        }
        
        error_log('CRM: On home page - checking for redirect loops');
        
        // Prevent redirect loops with multiple methods
        $is_loop = $this->is_redirect_loop();
        error_log('CRM: Is redirect loop: ' . ($is_loop ? 'yes' : 'no'));
        
        if ($is_loop) {
            return;
        }
        
        // Mark that we're executing a redirect to prevent loops
        $this->mark_redirect_execution();
        error_log('CRM: Marked redirect execution, proceeding with redirect');
        
        $this->execute_redirect($home_redirect);
    }
    
    /**
     * Check if current request is for home page
     */
    private function is_home_page() {
        $request_uri = $_SERVER['REQUEST_URI'] ?? '';
        $home_url = home_url();
        
        error_log('CRM: is_home_page() check started');
        error_log('CRM: Raw REQUEST_URI: ' . $request_uri);
        error_log('CRM: Home URL: ' . $home_url);
        
        $request_uri_trimmed = $this->safe_trim($request_uri, '/');
        $parsed_path = parse_url($home_url, PHP_URL_PATH);
        $home_path = $this->safe_trim($parsed_path, '/');
        
        error_log('CRM: Trimmed REQUEST_URI: "' . $request_uri_trimmed . '"');
        error_log('CRM: Home path from URL: "' . $home_path . '"');
        
        // Remove query string for comparison
        $request_path = explode('?', $request_uri_trimmed)[0];
        $request_path = $this->safe_trim($request_path, '/');
        
        error_log('CRM: Request path (no query): "' . $request_path . '"');
        
        // Check if this is the home page
        $is_home_by_path = ($request_path === $home_path || $request_path === '');
        
        error_log('CRM: Is home by path comparison: ' . ($is_home_by_path ? 'yes' : 'no'));
        error_log('CRM: Path comparison: "' . $request_path . '" === "' . $home_path . '" OR "' . $request_path . '" === ""');
        
        // Also check WordPress functions
        $wp_is_home = is_home();
        $wp_is_front_page = is_front_page();
        
        error_log('CRM: WordPress is_home(): ' . ($wp_is_home ? 'yes' : 'no'));
        error_log('CRM: WordPress is_front_page(): ' . ($wp_is_front_page ? 'yes' : 'no'));
        
        $final_result = $is_home_by_path || $wp_is_home || $wp_is_front_page;
        error_log('CRM: Final is_home_page result: ' . ($final_result ? 'yes' : 'no'));
        
        return $final_result;
    }
    
    /**
     * Check if we're in a redirect loop
     */
    private function is_redirect_loop() {
        // Check URL parameters
        if (isset($_GET['no_redirect']) || isset($_GET['crm_redirect_bypass'])) {
            return true;
        }
        
        // Check HTTP headers to detect redirect loops
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $this->detect_redirect_chain()) {
            return true;
        }
        
        // Check session/transient to prevent multiple redirects in short time
        if ($this->has_recent_redirect()) {
            return true;
        }
        
        // Check referer to avoid redirecting from the target page back to home
        if ($this->is_redirecting_from_target()) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Detect redirect chain in headers
     */
    private function detect_redirect_chain() {
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        // Simple detection - if user agent suggests automation or multiple requests
        return strpos($user_agent, 'curl') !== false || strpos($user_agent, 'wget') !== false;
    }
    
    /**
     * Check if there was a recent redirect to prevent rapid loops
     */
    private function has_recent_redirect() {
        $transient_key = 'crm_redirect_' . md5($_SERVER['REMOTE_ADDR'] ?? 'unknown');
        $last_redirect = get_transient($transient_key);
        
        if ($last_redirect && (time() - $last_redirect) < 5) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Check if user is coming from the redirect target
     */
    private function is_redirecting_from_target() {
        $referer = $_SERVER['HTTP_REFERER'] ?? '';
        if (empty($referer)) {
            return false;
        }
        
        $home_redirect = get_option('custom_home_redirect', []);
        if (empty($home_redirect['custom_page'])) {
            return false;
        }
        
        $custom_page = $this->safe_trim($home_redirect['custom_page'] ?? '');
        
        // Check if referer contains the custom page path
        if (strpos($referer, $custom_page) !== false) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Mark redirect execution to prevent loops
     */
    private function mark_redirect_execution() {
        $transient_key = 'crm_redirect_' . md5($_SERVER['REMOTE_ADDR'] ?? 'unknown');
        set_transient($transient_key, time(), 10); // 10 second window
    }
    
    /**
     * Execute the redirect based on settings (direct version for early hooks)
     */
    private function execute_redirect_direct($home_redirect) {
        error_log('CRM: execute_redirect_direct called with type: ' . ($home_redirect['type'] ?? 'not set'));
        
        if ($home_redirect['type'] === 'custom' && !empty($home_redirect['custom_page'])) {
            $custom_page = $this->safe_trim($home_redirect['custom_page']);
            error_log('CRM: Direct redirect to custom page: ' . $custom_page);
            
            // Check if it's a file that exists
            $file_path = ABSPATH . $this->safe_ltrim($custom_page, '/');
            if (file_exists($file_path) && is_file($file_path)) {
                error_log('CRM: File exists, serving directly: ' . $file_path);
                
                // Set basic headers
                if (!headers_sent()) {
                    header('Cache-Control: no-cache, must-revalidate');
                    header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
                }
                
                // Include the file
                include($file_path);
                exit;
            } else {
                // Treat as URL redirect
                $redirect_url = filter_var($custom_page, FILTER_VALIDATE_URL) ? 
                               $custom_page : home_url($this->safe_ltrim($custom_page, '/'));
                
                error_log('CRM: Redirecting to URL: ' . $redirect_url);
                wp_redirect($redirect_url, 302);
                exit;
            }
        } else {
            // Fall back to original method
            $this->execute_redirect($home_redirect);
        }
    }
    
    /**
     * Execute the redirect based on settings
     */
    private function execute_redirect($home_redirect) {
        error_log('CRM: execute_redirect called with type: ' . ($home_redirect['type'] ?? 'not set'));
        
        if ($home_redirect['type'] === 'wp_reading') {
            error_log('CRM: Executing WordPress reading page redirect');
            $this->redirect_to_wp_reading_page();
        } elseif ($home_redirect['type'] === 'custom' && !empty($home_redirect['custom_page'])) {
            error_log('CRM: Executing custom page redirect to: ' . $home_redirect['custom_page']);
            $this->redirect_to_custom_page($home_redirect['custom_page']);
        } else {
            error_log('CRM: No valid redirect type or custom page is empty');
            error_log('CRM: Type: ' . ($home_redirect['type'] ?? 'not set'));
            error_log('CRM: Custom page: ' . ($home_redirect['custom_page'] ?? 'not set'));
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
        $custom_page = $this->safe_trim($custom_page);
        error_log('CRM: redirect_to_custom_page called with: "' . $custom_page . '"');
        
        // Check if it's a full URL
        $is_full_url = filter_var($custom_page, FILTER_VALIDATE_URL);
        error_log('CRM: Is full URL: ' . ($is_full_url ? 'yes' : 'no'));
        
        if ($is_full_url) {
            error_log('CRM: Redirecting to full URL: ' . $custom_page);
            wp_redirect($custom_page, 302);
            exit;
        }
        
        // Check if it's a file path by looking for file existence
        $file_path = ABSPATH . $this->safe_ltrim($custom_page, '/');
        error_log('CRM: Checking file path: ' . $file_path);
        error_log('CRM: ABSPATH: ' . ABSPATH);
        error_log('CRM: Custom page after ltrim: ' . $this->safe_ltrim($custom_page, '/'));
        
        $file_exists = file_exists($file_path);
        $is_file = is_file($file_path);
        
        error_log('CRM: File exists: ' . ($file_exists ? 'yes' : 'no'));
        error_log('CRM: Is file: ' . ($is_file ? 'yes' : 'no'));
        
        if ($file_exists && $is_file) {
            error_log('CRM: File found, serving custom file');
            $this->serve_custom_file($custom_page);
        } else {
            // Treat as relative URL path
            $redirect_url = home_url($this->safe_ltrim($custom_page, '/'));
            error_log('CRM: File not found, treating as URL path. Redirecting to: ' . $redirect_url);
            wp_redirect($redirect_url, 302);
            exit;
        }
    }
    
    /**
     * Serve custom file directly
     */
    private function serve_custom_file($custom_page) {
        $file_path = ABSPATH . $this->safe_ltrim($custom_page, '/');
        $debug_mode = isset($_GET['crm_debug']);
        
        if ($debug_mode) {
            error_log('CRM DEBUG: Attempting to serve file: ' . $file_path);
            error_log('CRM DEBUG: File exists: ' . (file_exists($file_path) ? 'yes' : 'no'));
            error_log('CRM DEBUG: Is file: ' . (is_file($file_path) ? 'yes' : 'no'));
        }
        
        if (file_exists($file_path) && is_file($file_path)) {
            // Set headers to prevent caching issues
            if (!headers_sent()) {
                header('Cache-Control: no-cache, must-revalidate');
                header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
            }
            
            // Determine file type and serve accordingly
            $file_extension = pathinfo($file_path, PATHINFO_EXTENSION);
            $mime_type = $this->get_mime_type($file_path);
            
            if ($debug_mode) {
                error_log('CRM DEBUG: File extension: ' . $file_extension);
                error_log('CRM DEBUG: MIME type: ' . $mime_type);
                error_log('CRM DEBUG: Is executable: ' . ($this->is_executable_file($file_extension) ? 'yes' : 'no'));
            }
            
            if ($this->is_executable_file($file_extension)) {
                // For executable files (PHP, etc.), include them
                if ($debug_mode) {
                    error_log('CRM DEBUG: Including PHP file');
                }
                include($file_path);
            } else {
                // For static files, serve with appropriate content type
                if (!headers_sent() && $mime_type) {
                    header('Content-Type: ' . $mime_type);
                }
                if ($debug_mode) {
                    error_log('CRM DEBUG: Serving static file with MIME: ' . $mime_type);
                }
                readfile($file_path);
            }
            exit;
        } else {
            // File doesn't exist, show error
            if ($debug_mode) {
                error_log('CRM DEBUG: File not found, showing error');
            }
            
            // Show debug info if in debug mode
            if ($debug_mode) {
                wp_die('Custom redirect file not found: ' . esc_html($custom_page) . 
                       '<br>Looking for: ' . esc_html($file_path) . 
                       '<br>ABSPATH: ' . esc_html(ABSPATH) .
                       '<br>Custom page: ' . esc_html($custom_page) .
                       '<br>File exists: ' . (file_exists($file_path) ? 'yes' : 'no') .
                       '<br>Is file: ' . (is_file($file_path) ? 'yes' : 'no') .
                       '<br>Is readable: ' . (is_readable($file_path) ? 'yes' : 'no'));
            } else {
                wp_die('Custom redirect file not found: ' . esc_html($custom_page) . '<br>Looking for: ' . esc_html($file_path));
            }
        }
    }
    
    /**
     * Get MIME type for file
     */
    private function get_mime_type($file_path) {
        // Try different methods to get MIME type
        if (function_exists('finfo_file')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($finfo, $file_path);
            finfo_close($finfo);
            if ($mime_type) {
                return $mime_type;
            }
        }
        
        if (function_exists('mime_content_type')) {
            $mime_type = mime_content_type($file_path);
            if ($mime_type) {
                return $mime_type;
            }
        }
        
        // Fallback to extension-based detection
        $extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
        $mime_types = [
            'html' => 'text/html',
            'htm' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'txt' => 'text/plain',
            'md' => 'text/markdown',
        ];
        
        return $mime_types[$extension] ?? 'application/octet-stream';
    }
    
    /**
     * Check if file should be executed rather than served
     */
    private function is_executable_file($extension) {
        $executable_extensions = ['php', 'phtml', 'php3', 'php4', 'php5', 'php7', 'php8'];
        return in_array(strtolower($extension), $executable_extensions);
    }
}