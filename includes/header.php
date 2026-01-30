<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RA ENTERPRISES - Complete IT Solution Provider</title>
    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>


    <header>
        <!-- Top Bar -->
        <div class="top-bar">
            <div class="container">
                <div class="top-bar-content">
                    <ul class="top-info-list">
                        <li class="top-info-item">
                            <i class="fas fa-phone-alt"></i>
                            <span>+91-8178225111</span>
                        </li>
                        <li class="top-info-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>New Delhi, India</span>
                        </li>
                        <li class="top-info-item">
                            <i class="fas fa-file-invoice"></i>
                            <span>GST: 07HDXPK9194L1Z7</span>
                        </li>
                    </ul>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Header -->
        <div class="main-header">
            <div class="container">
                <div class="nav-wrapper">
                    <!-- Logo -->
                    <div class="logo">
                        <a href="index.php">
                            <!-- Ensure logo path is correct relative to index.php -->
                            <img src="assets/logo/logo.png" alt="RA ENTERPRISES Logo"> 
                        </a>
                    </div>

                    <!-- Navigation -->
                    <nav class="main-nav" id="mainNav">
                        <div class="close-nav" onclick="toggleMenu()">
                            <i class="fas fa-times"></i>
                        </div>
                        <ul class="nav-list">
                            <li><a href="index.php" class="nav-link">Home</a></li>
                            <li><a href="#" class="nav-link">About Us</a></li>
                            <li><a href="#" class="nav-link">Our Products</a></li>
                            <li><a href="#" class="nav-link">Services</a></li>
                            <li><a href="#" class="nav-link">Blog</a></li>
                            <li><a href="#" class="nav-link">Contact</a></li>
                        </ul>
                        <a href="#" class="desktop-btn">Get Quote</a>
                    </nav>

                    <!-- Mobile Toggle -->
                    <div class="mobile-toggle" onclick="toggleMenu()">
                        <i class="fas fa-bars"></i>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <script>
        function toggleMenu() {
            const nav = document.getElementById('mainNav');
            nav.classList.toggle('active');
        }
    </script>
