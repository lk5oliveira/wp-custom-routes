<?php
/**
 * Test file for custom home page redirection
 */
?>
<!DOCTYPE html>
<html>
<head>
    <title>Custom Home Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background: #f0f0f0;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: 0 auto;
        }
        .success {
            color: #28a745;
            font-size: 24px;
            margin-bottom: 20px;
        }
        .info {
            background: #e7f3ff;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="success">✅ Custom Home Page Redirection Working!</h1>
        <p>This page is being served from: <code>custom-pages/index.php</code></p>
        
        <div class="info">
            <h3>Debug Information:</h3>
            <ul>
                <li><strong>File Path:</strong> <?php echo __FILE__; ?></li>
                <li><strong>Time:</strong> <?php echo date('Y-m-d H:i:s'); ?></li>
                <li><strong>Server:</strong> <?php echo $_SERVER['HTTP_HOST'] ?? 'N/A'; ?></li>
                <li><strong>Request URI:</strong> <?php echo $_SERVER['REQUEST_URI'] ?? 'N/A'; ?></li>
            </ul>
        </div>
        
        <p><a href="<?php echo home_url('?no_redirect=1'); ?>">← Return to original home page</a></p>
    </div>
</body>
</html>