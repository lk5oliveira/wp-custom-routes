<?php
/**
 * Admin Styles and Scripts
 * Separated CSS and JavaScript for the admin interface
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<style>
.crm-container {
    max-width: 1200px;
    margin: 20px auto;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    padding: 2px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}

.crm-header {
    background: white;
    border-radius: 18px 18px 0 0;
    padding: 40px;
    border-bottom: 1px solid rgba(0,0,0,0.05);
}

.crm-header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
}

.crm-title {
    font-size: 32px;
    font-weight: 700;
    color: #1a202c;
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.crm-title-icon {
    width: 36px;
    height: 36px;
    stroke: #667eea;
}

.crm-subtitle {
    color: #718096;
    font-size: 16px;
    margin: 8px 0 0 48px;
}

.crm-stats {
    display: flex;
    gap: 20px;
}

.crm-stat-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 16px;
    padding: 20px 24px;
    text-align: center;
    color: white;
    min-width: 120px;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
}

.crm-stat-number {
    font-size: 28px;
    font-weight: 700;
    line-height: 1;
}

.crm-stat-label {
    font-size: 14px;
    opacity: 0.9;
    margin-top: 4px;
}

.crm-main-content {
    background: white;
    border-radius: 0 0 18px 18px;
    padding: 40px;
}

.crm-card {
    background: white;
    border-radius: 20px;
    border: 1px solid rgba(0,0,0,0.05);
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    overflow: hidden;
    margin-bottom: 24px;
}

.crm-card-header {
    background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
    padding: 24px 32px;
    border-bottom: 1px solid rgba(0,0,0,0.05);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.crm-card-title {
    font-size: 20px;
    font-weight: 600;
    color: #2d3748;
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 0;
}

.crm-card-icon {
    width: 24px;
    height: 24px;
    stroke: #667eea;
}

.crm-routes-container {
    padding: 32px;
    min-height: 200px;
}

.crm-route-item {
    background: #f8fafc;
    border: 2px solid transparent;
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 20px;
    display: flex;
    align-items: flex-start;
    gap: 20px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.crm-route-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.crm-route-item:hover {
    border-color: #667eea;
    box-shadow: 0 8px 30px rgba(102, 126, 234, 0.15);
    transform: translateY(-2px);
}

.crm-route-item:hover::before {
    opacity: 1;
}

.crm-route-handle {
    color: #a0aec0;
    cursor: grab;
    padding: 8px;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.crm-route-handle:hover {
    background: #e2e8f0;
    color: #4a5568;
}

.crm-route-handle svg {
    width: 20px;
    height: 20px;
}

.crm-route-content {
    flex: 1;
    display: grid;
    grid-template-columns: 1fr 1fr auto;
    gap: 24px;
    align-items: end;
}

.crm-input-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.crm-label {
    font-weight: 600;
    color: #4a5568;
    font-size: 14px;
    letter-spacing: 0.025em;
}

.crm-input {
    width: 100%;
    padding: 14px 16px;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    font-size: 14px;
    color: #2d3748;
    background: white;
    transition: all 0.3s ease;
    line-height: 1.4;
    min-height: 44px;
    box-sizing: border-box;
}

.crm-input:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.crm-select-wrapper {
    position: relative;
}

.crm-select {
    width: 100%;
    padding: 12px 40px 12px 16px;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    font-size: 14px;
    color: #2d3748;
    background: white;
    appearance: none;
    cursor: pointer;
    transition: all 0.3s ease;
}

.crm-select:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.crm-select-icon {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    width: 18px;
    height: 18px;
    color: #a0aec0;
    pointer-events: none;
}

.crm-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 20px;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
}

.crm-btn-icon {
    width: 18px;
    height: 18px;
}

.crm-btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.crm-btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
}

.crm-btn-success {
    background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(72, 187, 120, 0.3);
}

.crm-btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(72, 187, 120, 0.4);
}

.crm-btn-danger {
    background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(245, 101, 101, 0.3);
    padding: 12px;
}

.crm-btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(245, 101, 101, 0.4);
}

.crm-card-content {
    padding: 32px;
}

.crm-card-footer {
    background: #f8fafc;
    padding: 24px 32px;
    border-top: 1px solid rgba(0,0,0,0.05);
    text-align: center;
}

.crm-home-settings {
    max-width: 600px;
}

.crm-toggle-wrapper {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    margin-bottom: 24px;
}

.crm-toggle {
    position: relative;
    display: inline-block;
    width: 52px;
    height: 28px;
    flex-shrink: 0;
    margin-top: 2px;
}

.crm-toggle input {
    opacity: 0;
    width: 0;
    height: 0;
}

.crm-toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #cbd5e0;
    transition: 0.3s;
    border-radius: 34px;
}

.crm-toggle-slider:before {
    position: absolute;
    content: "";
    height: 20px;
    width: 20px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    transition: 0.3s;
    border-radius: 50%;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.crm-toggle input:checked + .crm-toggle-slider {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.crm-toggle input:checked + .crm-toggle-slider:before {
    transform: translateX(24px);
}

.crm-toggle-label strong {
    color: #2d3748;
    font-size: 16px;
    display: block;
    margin-bottom: 4px;
}

.crm-toggle-label p {
    color: #718096;
    font-size: 14px;
    margin: 0;
}

.crm-redirect-options {
    border-left: 3px solid #e2e8f0;
    padding-left: 24px;
    margin-left: 26px;
}

.crm-radio-group {
    margin-bottom: 20px;
}

.crm-radio-option {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    cursor: pointer;
    padding: 16px;
    border: 2px solid transparent;
    border-radius: 12px;
    transition: all 0.3s ease;
    margin-bottom: 12px;
}

.crm-radio-option:hover {
    background: #f8fafc;
    border-color: #e2e8f0;
}

.crm-radio-option input[type="radio"] {
    opacity: 0;
    position: absolute;
}

.crm-radio-mark {
    width: 20px;
    height: 20px;
    border: 2px solid #cbd5e0;
    border-radius: 50%;
    position: relative;
    flex-shrink: 0;
    margin-top: 2px;
    transition: all 0.3s ease;
}

.crm-radio-mark:after {
    content: '';
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) scale(0);
    transition: transform 0.3s ease;
}

.crm-radio-option input[type="radio"]:checked + .crm-radio-mark {
    border-color: #667eea;
}

.crm-radio-option input[type="radio"]:checked + .crm-radio-mark:after {
    transform: translate(-50%, -50%) scale(1);
}

.crm-radio-content strong {
    color: #2d3748;
    font-size: 15px;
    display: block;
    margin-bottom: 4px;
}

.crm-radio-content p {
    color: #718096;
    font-size: 13px;
    margin: 0;
}

.crm-custom-page-input {
    margin-top: 12px;
    margin-left: 32px;
}

.crm-empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #718096;
}

.crm-empty-icon {
    width: 64px;
    height: 64px;
    margin: 0 auto 20px;
    color: #cbd5e0;
}

.crm-empty-state h3 {
    font-size: 20px;
    font-weight: 600;
    color: #4a5568;
    margin: 0 0 8px;
}

.crm-info-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 24px;
    margin-top: 32px;
}

.crm-info-card {
    background: white;
    border-radius: 16px;
    padding: 24px;
    border: 1px solid rgba(0,0,0,0.05);
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    display: flex;
    gap: 16px;
    transition: all 0.3s ease;
}

.crm-info-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.1);
}

.crm-info-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    flex-shrink: 0;
}

.crm-info-icon svg {
    width: 24px;
    height: 24px;
}

.crm-info-icon-blue {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.crm-info-icon-green {
    background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
}

.crm-info-icon-purple {
    background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%);
}

.crm-info-content h3 {
    font-size: 16px;
    font-weight: 600;
    color: #2d3748;
    margin: 0 0 8px;
}

.crm-info-content p {
    color: #718096;
    font-size: 14px;
    line-height: 1.5;
    margin: 0;
}

.crm-notification {
    position: fixed;
    top: 32px;
    right: 32px;
    z-index: 1000;
    max-width: 400px;
    border-radius: 16px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.15);
    animation: slideIn 0.3s ease-out;
}

.crm-success {
    background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
    color: white;
}

.crm-notification-content {
    padding: 16px 20px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.crm-notification .crm-icon {
    width: 20px;
    height: 20px;
    flex-shrink: 0;
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@media (max-width: 768px) {
    .crm-container {
        margin: 10px;
        border-radius: 16px;
    }
    
    .crm-header {
        padding: 24px;
        border-radius: 14px 14px 0 0;
    }
    
    .crm-main-content {
        padding: 24px;
        border-radius: 0 0 14px 14px;
    }
    
    .crm-header-content {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .crm-title {
        font-size: 24px;
    }
    
    .crm-subtitle {
        margin-left: 0;
        margin-top: 8px;
    }
    
    .crm-route-content {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .crm-info-cards {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    let routeIndex = <?php echo count(get_option('custom_routes', [])); ?>;
    
    // Home redirect toggle functionality
    $('input[name="home_redirect[enabled]"]').change(function() {
        if ($(this).is(':checked')) {
            $('.crm-redirect-options').slideDown(300);
        } else {
            $('.crm-redirect-options').slideUp(300);
        }
    });
    
    // Radio button functionality
    $('input[name="home_redirect[type]"]').change(function() {
        if ($(this).val() === 'custom') {
            $('.crm-custom-page-input').slideDown(300);
        } else {
            $('.crm-custom-page-input').slideUp(300);
        }
    });
    
    // Add new route
    $('#add-route').click(function() {
        const newRoute = $(`
            <div class="crm-route-item" data-index="${routeIndex}">
                <div class="crm-route-handle">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                    </svg>
                </div>
                <div class="crm-route-content">
                    <div class="crm-input-group">
                        <label class="crm-label">Route Path</label>
                        <input type="text" name="routes[${routeIndex}][path]" 
                               placeholder="new-page" class="crm-input">
                    </div>
                    <div class="crm-input-group">
                        <label class="crm-label">File Path</label>
                        <input type="text" name="routes[${routeIndex}][file]" 
                               placeholder="custom-pages/new-page.php" class="crm-input">
                    </div>
                    <div class="crm-input-group">
                        <label class="crm-label">Type</label>
                        <div class="crm-select-wrapper">
                            <select name="routes[${routeIndex}][type]" class="crm-select">
                                <option value="php">PHP</option>
                                <option value="html">HTML</option>
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
        `);
        
        $('.crm-empty-state').remove();
        $('#routes-container').append(newRoute);
        
        // Animate in
        newRoute.hide().slideDown(300);
        
        routeIndex++;
        updateStats();
    });
    
    // Remove route
    $(document).on('click', '.remove-route', function() {
        const $route = $(this).closest('.crm-route-item');
        $route.slideUp(300, function() {
            $(this).remove();
            updateStats();
            
            // Show empty state if no routes
            if ($('#routes-container .crm-route-item').length === 0) {
                showEmptyState();
            }
        });
    });
    
    // Update stats counter
    function updateStats() {
        const routeCount = $('#routes-container .crm-route-item').length;
        $('.crm-stat-number').text(routeCount);
    }
    
    // Show empty state
    function showEmptyState() {
        const emptyState = $(`
            <div class="crm-empty-state">
                <svg class="crm-empty-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3>No routes configured</h3>
                <p>Start by adding your first custom route</p>
            </div>
        `);
        $('#routes-container').append(emptyState);
    }
    
    // Auto-hide notification after 5 seconds
    setTimeout(function() {
        $('.crm-notification').fadeOut(500);
    }, 5000);
    
    // Input focus effects
    $(document).on('focus', '.crm-input, .crm-select', function() {
        $(this).closest('.crm-route-item').addClass('focused');
    });
    
    $(document).on('blur', '.crm-input, .crm-select', function() {
        $(this).closest('.crm-route-item').removeClass('focused');
    });
    
    // Form validation
    $('.crm-form').on('submit', function(e) {
        let hasErrors = false;
        
        $('.crm-route-item').each(function() {
            const pathInput = $(this).find('input[name*="[path]"]');
            const fileInput = $(this).find('input[name*="[file]"]');
            
            if (!pathInput.val().trim() || !fileInput.val().trim()) {
                $(this).addClass('error');
                hasErrors = true;
            } else {
                $(this).removeClass('error');
            }
        });
        
        if (hasErrors) {
            e.preventDefault();
            showNotification('Please fill in all required fields', 'error');
        }
    });
    
    // Show notification function
    function showNotification(message, type = 'success') {
        const notification = $(`
            <div class="crm-notification crm-${type}">
                <div class="crm-notification-content">
                    <svg class="crm-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        ${type === 'success' ? 
                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>' :
                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>'
                        }
                    </svg>
                    <span>${message}</span>
                </div>
            </div>
        `);
        
        $('body').append(notification);
        
        setTimeout(function() {
            notification.fadeOut(500, function() {
                $(this).remove();
            });
        }, 4000);
    }
});
</script>