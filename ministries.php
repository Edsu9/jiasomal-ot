<?php
require_once 'config/db.php';

// Get church settings
$query = "SELECT * FROM settings WHERE id = 1";
$result = mysqli_query($conn, $query);
$settings = mysqli_fetch_assoc($result);
$church_name = isset($settings['church_name']) ? $settings['church_name'] : 'JIA Somal-ot';
$church_address = isset($settings['church_address']) ? $settings['church_address'] : '123 Church Street, City, State 12345';
$church_phone = isset($settings['church_phone']) ? $settings['church_phone'] : '(123) 456-7890';
$church_email = isset($settings['church_email']) ? $settings['church_email'] : 'info@churchname.org';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ministries - <?php echo $church_name; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #3498db;
            --primary-dark: #2980b9;
            --primary-light: #e3f2fd;
            --secondary-color: #2c3e50;
            --accent-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #1a202c;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --gray-100: #f7fafc;
            --gray-200: #edf2f7;
            --gray-300: #e2e8f0;
            --gray-400: #cbd5e0;
            --gray-500: #a0aec0;
            --gray-600: #718096;
            --gray-700: #4a5568;
            --gray-800: #2d3748;
            --gray-900: #1a202c;
            --transition-fast: 0.2s;
            --transition-normal: 0.3s;
            --transition-slow: 0.5s;
            --border-radius-sm: 0.25rem;
            --border-radius: 0.5rem;
            --border-radius-lg: 1rem;
            --border-radius-xl: 1.5rem;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-md: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            --shadow-xl: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            line-height: 1.6;
            color: var(--gray-800);
            background-color: var(--gray-100);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 1rem;
            color: var(--gray-900);
        }
        
        p {
            margin-bottom: 1rem;
        }
        
        a {
            color: var(--primary-color);
            text-decoration: none;
            transition: all var(--transition-fast) ease;
        }
        
        a:hover {
            color: var(--primary-dark);
        }
        
        img {
            max-width: 100%;
            height: auto;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }
        
        /* Header Styles */
        .header {
            background-color: white;
            box-shadow: var(--shadow);
            position: sticky;
            top: 0;
            z-index: 100;
            padding: 1rem 0;
        }
        
        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .logo img {
            height: 50px;
            width: auto;
        }
        
        .logo h1 {
            font-size: 1.5rem;
            margin-bottom: 0;
            color: var(--gray-900);
        }
        
        .nav ul {
            display: flex;
            list-style: none;
            gap: 2rem;
        }
        
        .nav a {
            color: var(--gray-700);
            font-weight: 500;
            padding: 0.5rem 0;
            position: relative;
        }
        
        .nav a:hover {
            color: var(--primary-color);
        }
        
        .nav a.active {
            color: var(--primary-color);
        }
        
        .nav a.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: var(--primary-color);
        }
        
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--gray-700);
            cursor: pointer;
        }
        
        /* Hero Section */
        .hero {
            background-color: var(--secondary-color);
            color: white;
            padding: 6rem 0;
            text-align: center;
            background-image: linear-gradient(to right, rgba(44, 62, 80, 0.9), rgba(52, 152, 219, 0.8)), url('img/church-bg.jpg');
            background-size: cover;
            background-position: center;
            position: relative;
        }
        
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(52, 152, 219, 0.3) 0%, rgba(44, 62, 80, 0) 50%);
            z-index: 1;
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .hero h2 {
            font-size: 3.5rem;
            margin-bottom: 1.5rem;
            color: white;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        
        .hero p {
            font-size: 1.25rem;
            max-width: 700px;
            margin: 0 auto 2rem;
            color: rgba(255, 255, 255, 0.9);
        }
        
        /* Ministries Section */
        .ministries-section {
            padding: 5rem 0;
        }
        
        .ministries-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .ministries-header h2 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            position: relative;
            display: inline-block;
            padding-bottom: 1rem;
        }
        
        .ministries-header h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: linear-gradient(to right, var(--primary-color), var(--primary-dark));
            border-radius: var(--border-radius);
        }
        
        .ministries-header p {
            max-width: 700px;
            margin: 0 auto;
            color: var(--gray-600);
            font-size: 1.1rem;
        }
        
        .ministries-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }
        
        .ministry-card {
            background-color: white;
            border-radius: var(--border-radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: all var(--transition-normal) ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .ministry-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }
        
        .ministry-image {
            height: 200px;
            overflow: hidden;
        }
        
        .ministry-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform var(--transition-slow) ease;
        }
        
        .ministry-card:hover .ministry-image img {
            transform: scale(1.05);
        }
        
        .ministry-content {
            padding: 1.5rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        .ministry-content h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: var(--gray-900);
        }
        
        .ministry-content p {
            color: var(--gray-600);
            margin-bottom: 1.5rem;
            flex: 1;
        }
        
        .ministry-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: auto;
        }
        
        .ministry-leader {
            display: flex;
            align-items: center;
            color: var(--gray-700);
            font-size: 0.9rem;
        }
        
        .ministry-leader i {
            margin-right: 0.5rem;
            color: var(--primary-color);
        }
        
        .ministry-link {
            color: var(--primary-color);
            font-weight: 600;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
        }
        
        .ministry-link i {
            margin-left: 0.5rem;
            transition: transform var(--transition-fast) ease;
        }
        
        .ministry-link:hover i {
            transform: translateX(3px);
        }
        
        /* Get Involved Section */
        .get-involved-section {
            padding: 5rem 0;
            background-color: var(--gray-100);
        }
        
        .get-involved-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }
        
        .get-involved-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .get-involved-header h2 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            position: relative;
            display: inline-block;
            padding-bottom: 1rem;
        }
        
        .get-involved-header h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: linear-gradient(to right, var(--primary-color), var(--primary-dark));
            border-radius: var(--border-radius);
        }
        
        .get-involved-header p {
            max-width: 700px;
            margin: 0 auto;
            color: var(--gray-600);
            font-size: 1.1rem;
        }
        
        .get-involved-content {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 2rem;
            align-items: center;
        }
        
        .get-involved-image {
            grid-column: span 5;
            border-radius: var(--border-radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow);
        }
        
        .get-involved-image img {
            width: 100%;
            height: auto;
            display: block;
        }
        
        .get-involved-text {
            grid-column: span 7;
        }
        
        .get-involved-text h3 {
            font-size: 2rem;
            margin-bottom: 1.5rem;
            color: var(--gray-900);
        }
        
        .get-involved-text p {
            color: var(--gray-700);
            margin-bottom: 1.5rem;
            font-size: 1.1rem;
            line-height: 1.7;
        }
        
        .get-involved-steps {
            margin-top: 2rem;
        }
        
        .step-item {
            display: flex;
            margin-bottom: 1.5rem;
        }
        
        .step-number {
            width: 40px;
            height: 40px;
            background-color: var(--primary-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            margin-right: 1rem;
            flex-shrink: 0;
        }
        
        .step-content h4 {
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
            color: var(--gray-900);
        }
        
        .step-content p {
            color: var(--gray-600);
            font-size: 1rem;
            margin-bottom: 0;
        }
        
        .cta-button {
            display: inline-block;
            padding: 0.875rem 2rem;
            background-color: var(--primary-color);
            color: white;
            text-decoration: none;
            border-radius: var(--border-radius);
            font-weight: 600;
            transition: all var(--transition-normal) ease;
            margin-top: 1rem;
        }
        
        .cta-button:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            color: white;
        }
        
        /* Testimonials Section */
        .testimonials-section {
            padding: 5rem 0;
        }
        
        .testimonials-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }
        
        .testimonials-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .testimonials-header h2 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            position: relative;
            display: inline-block;
            padding-bottom: 1rem;
        }
        
        .testimonials-header h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: linear-gradient(to right, var(--primary-color), var(--primary-dark));
            border-radius: var(--border-radius);
        }
        
        .testimonials-header p {
            max-width: 700px;
            margin: 0 auto;
            color: var(--gray-600);
            font-size: 1.1rem;
        }
        
        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }
        
        .testimonial-card {
            background-color: white;
            border-radius: var(--border-radius-lg);
            padding: 2rem;
            box-shadow: var(--shadow);
            transition: all var(--transition-normal) ease;
        }
        
        .testimonial-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }
        
        .testimonial-content {
            position: relative;
            padding-top: 2rem;
            margin-bottom: 1.5rem;
        }
        
        .testimonial-content::before {
            content: '"';
            position: absolute;
            top: -1rem;
            left: -0.5rem;
            font-size: 5rem;
            color: var(--primary-light);
            font-family: 'Georgia', serif;
            line-height: 1;
        }
        
        .testimonial-content p {
            color: var(--gray-700);
            font-size: 1.05rem;
            line-height: 1.7;
            font-style: italic;
        }
        
        .testimonial-author {
            display: flex;
            align-items: center;
        }
        
        .testimonial-author-image {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 1rem;
        }
        
        .testimonial-author-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .testimonial-author-info h4 {
            font-size: 1.1rem;
            margin-bottom: 0.25rem;
            color: var(--gray-900);
        }
        
        .testimonial-author-info p {
            color: var(--gray-600);
            font-size: 0.9rem;
            margin-bottom: 0;
        }
        
        /* Footer */
        .footer {
            background-color: var(--gray-900);
            color: white;
            padding: 5rem 0 2rem;
        }
        
        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }
        
        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
            margin-bottom: 3rem;
        }
        
        .footer-column h3 {
            font-size: 1.25rem;
            margin-bottom: 1.5rem;
            position: relative;
            padding-bottom: 0.75rem;
            color: white;
        }
        
        .footer-column h3::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 2px;
            background-color: var(--primary-color);
        }
        
        .footer-column p {
            margin-bottom: 1.5rem;
            color: var(--gray-400);
            line-height: 1.7;
        }
        
        .footer-links {
            list-style: none;
        }
        
        .footer-links li {
            margin-bottom: 0.75rem;
        }
        
        .footer-links a {
            color: var(--gray-400);
            text-decoration: none;
            transition: all var(--transition-fast) ease;
            display: flex;
            align-items: center;
        }
        
        .footer-links a i {
            margin-right: 0.5rem;
            color: var(--primary-color);
            font-size: 0.875rem;
        }
        
        .footer-links a:hover {
            color: white;
            transform: translateX(5px);
        }
        
        .footer-contact-info {
            list-style: none;
        }
        
        .footer-contact-info li {
            margin-bottom: 1.25rem;
            display: flex;
            align-items: flex-start;
            color: var(--gray-400);
        }
        
        .footer-contact-info i {
            margin-right: 0.75rem;
            color: var(--primary-color);
            margin-top: 0.25rem;
        }
        
        .social-links {
            display: flex;
            list-style: none;
            margin-top: 1.5rem;
            gap: 0.75rem;
        }
        
        .social-links a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 2.5rem;
            height: 2.5rem;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            color: white;
            transition: all var(--transition-normal) ease;
        }
        
        .social-links a:hover {
            background-color: var(--primary-color);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        
        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--gray-500);
            font-size: 0.875rem;
        }
        
        /* Mobile Menu */
        .mobile-menu {
            position: fixed;
            top: 0;
            right: -300px;
            width: 300px;
            height: 100%;
            background-color: white;
            z-index: 1000;
            box-shadow: var(--shadow-lg);
            transition: right var(--transition-normal) ease;
            padding: 2rem;
            overflow-y: auto;
        }
        
        .mobile-menu.active {
            right: 0;
        }
        
        .mobile-menu-close {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--gray-700);
            cursor: pointer;
        }
        
        .mobile-menu-links {
            list-style: none;
            margin-top: 2rem;
        }
        
        .mobile-menu-links li {
            margin-bottom: 1rem;
        }
        
        .mobile-menu-links a {
            display: block;
            padding: 0.75rem 0;
            color: var(--gray-800);
            font-weight: 500;
            border-bottom: 1px solid var(--gray-200);
            transition: all var(--transition-fast) ease;
        }
        
        .mobile-menu-links a:hover {
            color: var(--primary-color);
            padding-left: 0.5rem;
        }
        
        .mobile-menu-links a.active {
            color: var(--primary-color);
            font-weight: 600;
        }
        
        .mobile-menu-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
            display: none;
        }
        
        .mobile-menu-overlay.active {
            display: block;
        }
        
        /* Responsive Styles */
        @media (max-width: 1024px) {
            .get-involved-content {
                grid-template-columns: 1fr;
            }
            
            .get-involved-image, .get-involved-text {
                grid-column: span 12;
            }
            
            .get-involved-image {
                margin-bottom: 2rem;
            }
        }
        
        @media (max-width: 768px) {
            .nav {
                display: none;
            }
            
            .mobile-menu-btn {
                display: block;
            }
            
            .hero h2 {
                font-size: 2.5rem;
            }
            
            .hero p {
                font-size: 1.125rem;
            }
            
            .ministries-grid, .testimonials-grid {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            }
        }
        
        @media (max-width: 576px) {
            .hero h2 {
                font-size: 2rem;
            }
            
            .hero p {
                font-size: 1rem;
            }
            
            .ministries-header h2, .get-involved-header h2, .testimonials-header h2 {
                font-size: 2rem;
            }
            
            .get-involved-text h3 {
                font-size: 1.75rem;
            }
            
            .ministries-grid, .testimonials-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-container">
            <div class="logo">
                <img src="img/jia.png" alt="<?php echo $church_name; ?> Logo">
                <h1><?php echo $church_name; ?></h1>
            </div>
            <nav class="nav">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="public_events.php">Events</a></li>
                    <li><a href="about_us.php">About Us</a></li>
                    <li><a href="ministries.php" class="active">Ministries</a></li>
                    <li><a href="sermons.php">Sermons</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
            </nav>
            <button class="mobile-menu-btn" id="mobileMenuBtn">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </header>
    
    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobileMenu">
        <button class="mobile-menu-close" id="mobileMenuClose">
            <i class="fas fa-times"></i>
        </button>
        <div class="logo" style="margin-bottom: 1.5rem;">
            <img src="img/jia.png" alt="<?php echo $church_name; ?> Logo" style="height: 40px;">
            <h1 style="font-size: 1.25rem;"><?php echo $church_name; ?></h1>
        </div>
        <ul class="mobile-menu-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="public_events.php">Events</a></li>
            <li><a href="about_us.php">About Us</a></li>
            <li><a href="ministries.php" class="active">Ministries</a></li>
            <li><a href="sermons.php">Sermons</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>
    </div>
    <div class="mobile-menu-overlay" id="mobileMenuOverlay"></div>
    
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h2>Our Ministries</h2>
            <p>Discover the various ways you can connect, serve, and grow in your faith through our church ministries.</p>
        </div>
    </section>
    
    <!-- Ministries Section -->
    <section class="ministries-section">
        <div class="container">
            <div class="ministries-header">
                <h2>Explore Our Ministries</h2>
                <p>We offer a variety of ministries designed to help you grow spiritually, connect with others, and serve the community.</p>
            </div>
            
            <div class="ministries-grid">
                <div class="ministry-card">
                    <div class="ministry-image">
                        <img src="img/P1.jpg" alt="Worship Ministry">
                    </div>
                    <div class="ministry-content">
                        <h3>Worship Ministry</h3>
                        <p>Our worship ministry leads the congregation in praise and worship during our services. We use music, prayer, and scripture to create an atmosphere where people can encounter God's presence.</p>
                        <div class="ministry-footer">
                            <div class="ministry-leader">
                                <i class="fas fa-user"></i> Led by: Sarah Johnson
                            </div>
                            <a href="#" class="ministry-link">Learn More <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                
                <div class="ministry-card">
                    <div class="ministry-image">
                        <img src="img/P1.jpg" alt="Youth Ministry">
                    </div>
                    <div class="ministry-content">
                        <h3>Youth Ministry</h3>
                        <p>Our youth ministry provides a safe and fun environment for teenagers to grow in their faith, build meaningful relationships, and develop leadership skills through Bible study, activities, and service projects.</p>
                        <div class="ministry-footer">
                            <div class="ministry-leader">
                                <i class="fas fa-user"></i> Led by: Michael Davis
                            </div>
                            <a href="#" class="ministry-link">Learn More <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                
                <div class="ministry-card">
                    <div class="ministry-image">
                        <img src="img/P1.jpg" alt="Children's Ministry">
                    </div>
                    <div class="ministry-content">
                        <h3>Children's Ministry</h3>
                        <p>Our children's ministry helps kids learn about God's love in age-appropriate ways through engaging lessons, activities, and games that make learning about faith fun and memorable.</p>
                        <div class="ministry-footer">
                            <div class="ministry-leader">
                                <i class="fas fa-user"></i> Led by: Emily Wilson
                            </div>
                            <a href="#" class="ministry-link">Learn More <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                
                <div class="ministry-card">
                    <div class="ministry-image">
                        <img src="img/P1.jpg" alt="Outreach Ministry">
                    </div>
                    <div class="ministry-content">
                        <h3>Outreach Ministry</h3>
                        <p>Our outreach ministry serves the local community and beyond through various service projects, mission trips, and partnerships with local organizations to meet physical and spiritual needs.</p>
                        <div class="ministry-footer">
                            <div class="ministry-leader">
                                <i class="fas fa-user"></i> Led by: Robert Thompson
                            </div>
                            <a href="#" class="ministry-link">Learn More <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                
                <div class="ministry-card">
                    <div class="ministry-image">
                        <img src="img/P1.jpg" alt="Prayer Ministry">
                    </div>
                    <div class="ministry-content">
                        <h3>Prayer Ministry</h3>
                        <p>Our prayer ministry is dedicated to interceding for the needs of our church, community, and world. We host regular prayer meetings and maintain a prayer chain for urgent requests.</p>
                        <div class="ministry-footer">
                            <div class="ministry-leader">
                                <i class="fas fa-user"></i> Led by: Grace Martinez
                            </div>
                            <a href="#" class="ministry-link">Learn More <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                
                <div class="ministry-card">
                    <div class="ministry-image">
                        <img src="img/P1.jpg" alt="Small Groups">
                    </div>
                    <div class="ministry-content">
                        <h3>Small Groups</h3>
                        <p>Our small groups provide opportunities for deeper connection, Bible study, and prayer in a more intimate setting. Groups meet weekly in homes throughout the community.</p>
                        <div class="ministry-footer">
                            <div class="ministry-leader">
                                <i class="fas fa-user"></i> Led by: David & Lisa Brown
                            </div>
                            <a href="#" class="ministry-link">Learn More <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Get Involved Section -->
    <section class="get-involved-section">
        <div class="get-involved-container">
            <div class="get-involved-header">
                <h2>Get Involved</h2>
                <p>There are many ways to serve and connect within our church community. Find your place and make a difference.</p>
            </div>
            
            <div class="get-involved-content">
                <div class="get-involved-image">
                    <img src="img/jia.png" alt="Get Involved">
                </div>
                <div class="get-involved-text">
                    <h3>How to Get Started</h3>
                    <p>We believe that everyone has unique gifts and talents that can be used to serve God and others. Whether you're interested in working with children, playing music, greeting visitors, or serving behind the scenes, there's a place for you in our church family.</p>
                    
                    <div class="get-involved-steps">
                        <div class="step-item">
                            <div class="step-number">1</div>
                            <div class="step-content">
                                <h4>Attend a Ministry Fair</h4>
                                <p>Join us at our next ministry fair to learn about all the opportunities available and meet ministry leaders.</p>
                            </div>
                        </div>
                        
                        <div class="step-item">
                            <div class="step-number">2</div>
                            <div class="step-content">
                                <h4>Complete a Gifts Assessment</h4>
                                <p>Discover your spiritual gifts and how they can be used in ministry through our gifts assessment.</p>
                            </div>
                        </div>
                        
                        <div class="step-item">
                            <div class="step-number">3</div>
                            <div class="step-content">
                                <h4>Connect with a Ministry Leader</h4>
                                <p>Reach out to a ministry leader to learn more about specific opportunities and how to get involved.</p>
                            </div>
                        </div>
                    </div>
                    
                    <a href="#" class="cta-button">Find Your Ministry</a>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Testimonials Section -->
    <section class="testimonials-section">
        <div class="testimonials-container">
            <div class="testimonials-header">
                <h2>Ministry Testimonials</h2>
                <p>Hear from those who have been impacted by our ministries and how serving has transformed their lives.</p>
            </div>
            
            <div class="testimonials-grid">
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <p>Being part of the worship team has deepened my faith and given me a community of believers who encourage me. I've grown so much as a musician and as a Christian through this ministry.</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="testimonial-author-image">
                            <img src="img/P1.jpg" alt="Testimonial Author">
                        </div>
                        <div class="testimonial-author-info">
                            <h4>Jennifer Adams</h4>
                            <p>Worship Team Member</p>
                        </div>
                    </div>
                </div>
                
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <p>Volunteering with the children's ministry has been such a blessing. Seeing the kids grow in their understanding of God's love and watching their faith develop is incredibly rewarding.</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="testimonial-author-image">
                            <img src="img/P1.jpg" alt="Testimonial Author">
                        </div>
                        <div class="testimonial-author-info">
                            <h4>Mark Wilson</h4>
                            <p>Children's Ministry Volunteer</p>
                        </div>
                    </div>
                </div>
                
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <p>Our small group has become like family to us. We've supported each other through life's challenges and celebrated together in times of joy. It's been a lifeline for our spiritual growth.</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="testimonial-author-image">
                            <img src="img/P1.jpg" alt="Testimonial Author">
                        </div>
                        <div class="testimonial-author-info">
                            <h4>Rachel & James Cooper</h4>
                            <p>Small Group Members</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="footer" id="contact">
        <div class="footer-container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>About Us</h3>
                    <p><?php echo $church_name; ?> is a welcoming community of believers dedicated to worship, fellowship, and service. We strive to share God's love with all people.</p>
                    <ul class="social-links">
                        <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                        <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                        <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                        <li><a href="#"><i class="fab fa-youtube"></i></a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Quick Links</h3>
                    <ul class="footer-links">
                        <li><a href="index.php"><i class="fas fa-chevron-right"></i> Home</a></li>
                        <li><a href="public_events.php"><i class="fas fa-chevron-right"></i> Events</a></li>
                        <li><a href="about_us.php"><i class="fas fa-chevron-right"></i> About Us</a></li>
                        <li><a href="ministries.php"><i class="fas fa-chevron-right"></i> Ministries</a></li>
                        <li><a href="sermons.php"><i class="fas fa-chevron-right"></i> Sermons</a></li>
                        <li><a href="contact.php"><i class="fas fa-chevron-right"></i> Contact</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Contact Us</h3>
                    <ul class="footer-contact-info">
                        <li>
                            <i class="fas fa-map-marker-alt"></i>
                            <span><?php echo $church_address; ?></span>
                        </li>
                        <li>
                            <i class="fas fa-phone"></i>
                            <span><?php echo $church_phone; ?></span>
                        </li>
                        <li>
                            <i class="fas fa-envelope"></i>
                            <span><?php echo $church_email; ?></span>
                        </li>
                        <li>
                            <i class="fas fa-clock"></i>
                            <span>Sunday Service: 10:00 AM</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> <?php echo $church_name; ?>. All Rights Reserved.</p>
            </div>
        </div>
    </footer>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile Menu Functionality
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const mobileMenu = document.getElementById('mobileMenu');
            const mobileMenuClose = document.getElementById('mobileMenuClose');
            const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');
            
            if (mobileMenuBtn) {
                mobileMenuBtn.addEventListener('click', function() {
                    mobileMenu.classList.add('active');
                    mobileMenuOverlay.classList.add('active');
                    document.body.style.overflow = 'hidden';
                });
            }
            
            if (mobileMenuClose) {
                mobileMenuClose.addEventListener('click', function() {
                    mobileMenu.classList.remove('active');
                    mobileMenuOverlay.classList.remove('active');
                    document.body.style.overflow = '';
                });
            }
            
            if (mobileMenuOverlay) {
                mobileMenuOverlay.addEventListener('click', function() {
                    mobileMenu.classList.remove('active');
                    mobileMenuOverlay.classList.remove('active');
                    document.body.style.overflow = '';
                });
            }
        });
    </script>
</body>
</html>

