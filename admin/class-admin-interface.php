<?php
/**
 * Admin Interface Class
 * Handles all admin-related functionality
 */

if (!defined('ABSPATH')) {
    exit;
}

class CustomRoutesAdmin {
    
    public function __construct() {
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_post_save_routes', [$this, 'save_routes']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_options_page(
            'Custom Routes',
            'Custom Routes',
            'manage_options',
            'custom-routes',
            [$this, 'admin_page']
        );
    }
    
    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets($hook) {
        if ($hook !== 'settings_page_custom-routes') {
            return;
        }
        
        // Add custom CSS and JS inline
        add_action('admin_footer', [$this, 'add_custom_styles_and_scripts']);
    }
    
    /**
     * Admin page content
     */
    public function admin_page() {
        $routes = get_option('custom_routes', []);
        $home_redirect = get_option('custom_home_redirect', [
            'enabled' => false,
            'type' => 'custom',
            'custom_page' => ''
        ]);
        
        if (isset($_GET['message']) && $_GET['message'] == 'saved') {
            echo '<div class="crm-notification crm-success">
                    <div class="crm-notification-content">
                        <svg class="crm-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Settings saved successfully!</span>
                    </div>
                  </div>';
        }
        ?>
        <div class="crm-container">
            <div class="crm-header">
                <div class="crm-header-content">
                    <div class="crm-title-section">
                        <h1 class="crm-title">
                            <svg class="crm-title-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                            </svg>
                            Custom Routes Manager
                        </h1>
                        <p class="crm-subtitle">Create and manage custom page routes with modern routing system</p>
                    </div>
                    <div class="crm-stats">
                        <div class="crm-stat-card">
                            <div class="crm-stat-number"><?php echo count($routes); ?></div>
                            <div class="crm-stat-label">Active Routes</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="crm-main-content">
                <form method="post" action="<?php echo admin_url('admin-post.php'); ?>" class="crm-form">
                    <?php wp_nonce_field('save_routes_nonce'); ?>
                    <input type="hidden" name="action" value="save_routes">
                    
                    <!-- Home Redirect Section -->
                    <div class="crm-card">
                        <div class="crm-card-header">
                            <h2 class="crm-card-title">
                                <svg class="crm-card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                                Home Page Redirect
                            </h2>
                        </div>
                        
                        <div class="crm-card-content">
                            <?php $this->render_home_redirect_section($home_redirect); ?>
                        </div>
                    </div>
                    
                    <!-- Routes Section -->
                    <div class="crm-card">
                        <div class="crm-card-header">
                            <h2 class="crm-card-title">
                                <svg class="crm-card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                Route Configuration
                            </h2>
                            <button type="button" id="add-route" class="crm-btn crm-btn-primary">
                                <svg class="crm-btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Add Route
                            </button>
                        </div>
                        
                        <div class="crm-routes-container" id="routes-container">
                            <?php $this->render_routes_section($routes); ?>
                        </div>
                        
                        <div class="crm-card-footer">
                            <button type="submit" class="crm-btn crm-btn-success">
                                <svg class="crm-btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Save All Settings
                            </button>
                        </div>
                    </div>
                </form>
                
                <?php $this->render_info_cards(); ?>
            </div>
        </div>
        <?php
    }
    
    /**
     * Render home redirect section
     */
    private function render_home_redirect_section($home_redirect) {
        ?>
        <div class="crm-home-settings">
            <div class="crm-toggle-wrapper">
                <label class="crm-toggle">
                    <input type="checkbox" name="home_redirect[enabled]" value="1" <?php checked($home_redirect['enabled']); ?>>
                    <span class="crm-toggle-slider"></span>
                </label>
                <div class="crm-toggle-label">
                    <strong>Enable Home Page Redirect</strong>
                    <p>Redirect visitors from your site's home page to a custom page</p>
                </div>
            </div>
            
            <div class="crm-redirect-options" <?php echo !$home_redirect['enabled'] ? 'style="display:none;"' : ''; ?>>
                <div class="crm-radio-group">
                    <label class="crm-radio-option">
                        <input type="radio" name="home_redirect[type]" value="custom" <?php checked($home_redirect['type'], 'custom'); ?>>
                        <span class="crm-radio-mark"></span>
                        <div class="crm-radio-content">
                            <strong>Custom Page</strong>
                            <p>Redirect to a specific URL or page</p>
                        </div>
                    </label>
                    
                    <div class="crm-custom-page-input" <?php echo $home_redirect['type'] !== 'custom' ? 'style="display:none;"' : ''; ?>>
                        <input type="text" name="home_redirect[custom_page]" 
                               value="<?php echo esc_attr($home_redirect['custom_page']); ?>" 
                               placeholder="/dashboard or https://example.com" class="crm-input">
                    </div>
                </div>
                
                <div class="crm-radio-group">
                    <label class="crm-radio-option">
                        <input type="radio" name="home_redirect[type]" value="wp_reading" <?php checked($home_redirect['type'], 'wp_reading'); ?>>
                        <span class="crm-radio-mark"></span>
                        <div class="crm-radio-content">
                            <strong>WordPress Reading Settings</strong>
                            <p>Use the page set in WordPress Settings > Reading</p>
                        </div>
                    </label>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Render routes section
     */
    private function render_routes_section($routes) {
        if (!empty($routes)) {
            foreach ($routes as $index => $route) {
                $this->render_route_item($index, $route);
            }
        } else {
            $this->render_empty_state();
        }
    }
    
    /**
     * Render single route item
     */
    private function render_route_item($index, $route) {
        ?>
        <div class="crm-route-item" data-index="<?php echo $index; ?>">
            <div class="crm-route-handle">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                </svg>
            </div>
            <div class="crm-route-content">
                <div class="crm-input-group">
                    <label class="crm-label">Route Path</label>
                    <input type="text" name="routes[<?php echo $index; ?>][path]" 
                           value="<?php echo esc_attr($route['path']); ?>" 
                           placeholder="dashboard" class="crm-input">
                </div>
                <div class="crm-input-group">
                    <label class="crm-label">File Path</label>
                    <input type="text" name="routes[<?php echo $index; ?>][file]" 
                           value="<?php echo esc_attr($route['file']); ?>" 
                           placeholder="custom-pages/dashboard.php" class="crm-input">
                </div>
                <div class="crm-input-group">
                    <label class="crm-label">Type</label>
                    <div class="crm-select-wrapper">
                        <select name="routes[<?php echo $index; ?>][type]" class="crm-select">
                            <option value="php" <?php selected($route['type'], 'php'); ?>>PHP</option>
                            <option value="html" <?php selected($route['type'], 'html'); ?>>HTML</option>
                        </select>
                        <svg class="crm-select-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
            </div>
            <button type="button" class="crm-btn crm-btn-danger remove-route">
                <svg class="crm-btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </button>
        </div>
        <?php
    }
    
    /**
     * Render empty state
     */
    private function render_empty_state() {
        ?>
        <div class="crm-empty-state">
            <svg class="crm-empty-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3>No routes configured</h3>
            <p>Start by adding your first custom route</p>
        </div>
        <?php
    }
    
    /**
     * Render info cards
     */
    private function render_info_cards() {
        ?>
        <div class="crm-info-cards">
            <div class="crm-info-card">
                <div class="crm-info-icon crm-info-icon-blue">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="crm-info-content">
                    <h3>Route Path</h3>
                    <p>The URL path without leading slash. Example: "dashboard" creates yoursite.com/dashboard</p>
                </div>
            </div>
            
            <div class="crm-info-card">
                <div class="crm-info-icon crm-info-icon-green">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 1v4"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 1v4"/>
                    </svg>
                </div>
                <div class="crm-info-content">
                    <h3>File Path</h3>
                    <p>Path relative to WordPress root. Example: "custom-pages/dashboard.php"</p>
                </div>
            </div>
            
            <div class="crm-info-card">
                <div class="crm-info-icon crm-info-icon-purple">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                    </svg>
                </div>
                <div class="crm-info-content">
                    <h3>File Types</h3>
                    <p>Choose PHP for dynamic pages with WordPress functions, HTML for static content</p>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Save routes and settings
     */
    public function save_routes() {
        if (!wp_verify_nonce($_POST['_wpnonce'], 'save_routes_nonce')) {
            wp_die('Security check failed');
        }
        
        if (!current_user_can('manage_options')) {
            wp_die('Insufficient permissions');
        }
        
        // Save routes
        $routes = [];
        if (isset($_POST['routes']) && is_array($_POST['routes'])) {
            foreach ($_POST['routes'] as $route) {
                if (!empty($route['path']) && !empty($route['file'])) {
                    $routes[] = [
                        'path' => sanitize_text_field($route['path']),
                        'file' => sanitize_text_field($route['file']),
                        'type' => sanitize_text_field($route['type'])
                    ];
                }
            }
        }
        
        update_option('custom_routes', $routes);
        
        // Save home redirect settings
        $home_redirect = [
            'enabled' => isset($_POST['home_redirect']['enabled']) ? true : false,
            'type' => sanitize_text_field($_POST['home_redirect']['type'] ?? 'custom'),
            'custom_page' => sanitize_text_field($_POST['home_redirect']['custom_page'] ?? ''),
        ];
        
        update_option('custom_home_redirect', $home_redirect);
        
        // Mark that routes need to be flushed
        update_option('custom_routes_flush_needed', true);
        
        wp_redirect(admin_url('options-general.php?page=custom-routes&message=saved'));
        exit;
    }
    
    /**
     * Add custom styles and scripts
     */
    public function add_custom_styles_and_scripts() {
        require_once plugin_dir_path(__FILE__) . 'admin-styles-scripts.php';
    }
}