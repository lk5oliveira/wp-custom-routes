<?php
/**
 * Debug utility for Custom Routes Manager
 * Add ?crm_debug_info=1 to any URL to see debug information
 */

if (!defined('ABSPATH')) {
    require_once('../../../wp-config.php');
}

if (!isset($_GET['crm_debug_info'])) {
    die('Add ?crm_debug_info=1 to the URL to see debug info');
}

if (!current_user_can('manage_options')) {
    die('You need admin permissions to view debug info');
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Custom Routes Manager - Debug Info</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .section { background: #f9f9f9; padding: 15px; margin: 15px 0; border-radius: 5px; }
        .good { color: #28a745; }
        .bad { color: #dc3545; }
        .warning { color: #ffc107; }
        code { background: #e9ecef; padding: 2px 6px; border-radius: 3px; }
        pre { background: #f8f9fa; padding: 10px; border-radius: 5px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>Custom Routes Manager - Debug Information</h1>
    
    <div class="section">
        <h2>Plugin Status</h2>
        <?php
        $plugin_active = is_plugin_active('custom-routes-plugin/custom-routes-manager.php');
        echo '<p>Plugin Active: <span class="' . ($plugin_active ? 'good' : 'bad') . '">' . ($plugin_active ? 'Yes' : 'No') . '</span></p>';
        
        if (class_exists('CustomHomeRedirect')) {
            echo '<p class="good">✅ CustomHomeRedirect class exists</p>';
        } else {
            echo '<p class="bad">❌ CustomHomeRedirect class NOT found</p>';
        }
        ?>
    </div>
    
    <div class="section">
        <h2>Home Redirect Settings</h2>
        <?php
        $home_redirect = get_option('custom_home_redirect', []);
        echo '<pre>' . print_r($home_redirect, true) . '</pre>';
        
        if (empty($home_redirect['enabled'])) {
            echo '<p class="warning">⚠️ Home redirect is disabled in settings</p>';
        } else {
            echo '<p class="good">✅ Home redirect is enabled</p>';
            
            if (!empty($home_redirect['custom_page'])) {
                $file_path = ABSPATH . ltrim($home_redirect['custom_page'], '/');
                if (file_exists($file_path)) {
                    echo '<p class="good">✅ Custom file exists: ' . $file_path . '</p>';
                } else {
                    echo '<p class="bad">❌ Custom file NOT found: ' . $file_path . '</p>';
                }
            }
        }
        ?>
    </div>
    
    <div class="section">
        <h2>WordPress Environment</h2>
        <?php
        echo '<p><strong>Home URL:</strong> ' . home_url() . '</p>';
        echo '<p><strong>Site URL:</strong> ' . site_url() . '</p>';
        echo '<p><strong>ABSPATH:</strong> ' . ABSPATH . '</p>';
        echo '<p><strong>Current URL:</strong> ' . (is_ssl() ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '</p>';
        ?>
    </div>
    
    <div class="section">
        <h2>Recent Error Logs (CRM entries)</h2>
        <?php
        $log_file = ini_get('error_log');
        if ($log_file && file_exists($log_file)) {
            $logs = file_get_contents($log_file);
            $crm_logs = array_filter(explode("\n", $logs), function($line) {
                return strpos($line, 'CRM:') !== false;
            });
            
            if (!empty($crm_logs)) {
                echo '<pre>' . implode("\n", array_slice($crm_logs, -20)) . '</pre>';
            } else {
                echo '<p class="warning">No CRM logs found in error log</p>';
            }
        } else {
            echo '<p class="bad">Error log file not found or not accessible</p>';
        }
        ?>
    </div>
    
    <div class="section">
        <h2>Test Actions</h2>
        <p><a href="<?php echo home_url(); ?>">🏠 Go to Home Page</a></p>
        <p><a href="<?php echo home_url('?crm_debug=1'); ?>">🔍 Go to Home Page with Debug</a></p>
        <p><a href="<?php echo home_url('?no_redirect=1'); ?>">🚫 Go to Home Page (bypass redirect)</a></p>
    </div>
</body>
</html>