<?php
include 'dbms/dbms_config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Brands Component</title>
    <!-- Include necessary CSS -->
    <link rel="stylesheet" href="assets/css/style.css"> 
    <link rel="stylesheet" href="assets/css/responsive.css">
    <link rel="stylesheet" href="assets/css/clients.css">
    <style>
        /* Extracting minimal CSS from our-clients.php context if it relies on specific section styles not in main css 
           Assuming .clients-section, .ticker-wrap, .ticker-content are in style.css
        */
        body { font-family: sans-serif; }
    </style>
</head>
<body>
    <h1>Brands Component Test</h1>
    <?php include 'components/brands.php'; ?>
</body>
</html>
