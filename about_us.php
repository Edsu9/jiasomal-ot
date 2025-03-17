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
    <title>About Us - <?php echo $church_name; ?></title>
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
        
        /* About Section */
        .about-section {
            padding: 5rem 0;
        }
        
        .about-container {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 2rem;
        }
        
        .about-content {
            grid-column: span 7;
        }
        
        .about-sidebar {
            grid-column: span 5;
        }
        
        .about-content h2 {
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
            position: relative;
            padding-bottom: 1rem;
        }
        
        .about-content h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 80px;
            height: 3px;
            background: linear-gradient(to right, var(--primary-color), var(--primary-dark));
            border-radius: var(--border-radius);
        }
        
        .about-content p {
            margin-bottom: 1.5rem;
            font-size: 1.1rem;
            color: var(--gray-700);
            line-height: 1.8;
        }
        
        .mission-vision {
            margin-top: 3rem;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
        }
        
        .mission-card, .vision-card {
            background-color: white;
            border-radius: var(--border-radius-lg);
            padding: 2rem;
            box-shadow: var(--shadow);
            transition: all var(--transition-normal) ease;
        }
        
        .mission-card:hover, .vision-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }
        
        .mission-card h3, .vision-card h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            color: var(--primary-color);
        }
        
        .mission-card h3 i, .vision-card h3 i {
            margin-right: 0.75rem;
            font-size: 1.75rem;
        }
        
        .mission-card p, .vision-card p {
            color: var(--gray-700);
            font-size: 1rem;
            line-height: 1.7;
        }
        
        .pastor-card {
            background-color: white;
            border-radius: var(--border-radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
        }
        
        .pastor-image {
            height: 300px;
            overflow: hidden;
        }
        
        .pastor-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .pastor-details {
            padding: 1.5rem;
        }
        
        .pastor-details h3 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }
        
        .pastor-details h4 {
            font-size: 1rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
            font-weight: 500;
            font-family: 'Inter', sans-serif;
        }
        
        .pastor-details p {
            color: var(--gray-700);
            font-size: 0.95rem;
            line-height: 1.7;
        }
        
        .contact-card {
            background-color: var(--primary-light);
            border-radius: var(--border-radius-lg);
            padding: 2rem;
            box-shadow: var(--shadow);
        }
        
        .contact-card h3 {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            color: var(--primary-dark);
        }
        
        .contact-info {
            list-style: none;
        }
        
        .contact-info li {
            margin-bottom: 1rem;
            display: flex;
            align-items: flex-start;
        }
        
        .contact-info i {
            color: var(--primary-color);
            margin-right: 0.75rem;
            margin-top: 0.25rem;
            font-size: 1.1rem;
            width: 20px;
        }
        
        .contact-info span {
            color: var(--gray-700);
            line-height: 1.5;
        }
        
        .values-section {
            padding: 5rem 0;
            background-color: var(--gray-100);
        }
        
        .values-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }
        
        .values-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .values-header h2 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            position: relative;
            display: inline-block;
            padding-bottom: 1rem;
        }
        
        .values-header h2::after {
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
        
        .values-header p {
            max-width: 700px;
            margin: 0 auto;
            color: var(--gray-600);
            font-size: 1.1rem;
        }
        
        .values-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }
        
        .value-card {
            background-color: white;
            border-radius: var(--border-radius-lg);
            padding: 2rem;
            box-shadow: var(--shadow);
            text-align: center;
            transition: all var(--transition-normal) ease;
        }
        
        .value-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }
        
        .value-icon {
            width: 80px;
            height: 80px;
            background-color: var(--primary-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }
        
        .value-icon i {
            font-size: 2rem;
            color: var(--primary-color);
        }
        
        .value-card h3 {
            font-size: 1.25rem;
            margin-bottom: 1rem;
        }
        
        .value-card p {
            color: var(--gray-600);
            font-size: 0.95rem;
            line-height: 1.7;
        }
        
        .history-section {
            padding: 5rem 0;
        }
        
        .history-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }
        
        .history-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .history-header h2 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            position: relative;
            display: inline-block;
            padding-bottom: 1rem;
        }
        
        .history-header h2::after {
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
        
        .history-header p {
            max-width: 700px;
            margin: 0 auto;
            color: var(--gray-600);
            font-size: 1.1rem;
        }
        
        .timeline {
            position: relative;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .timeline::after {
            content: '';
            position: absolute;
            width: 4px;
            background-color: var(--gray-300);
            top: 0;
            bottom: 0;
            left: 50%;
            margin-left: -2px;
            border-radius: var(--border-radius);
        }
        
        .timeline-item {
            padding: 10px 40px;
            position: relative;
            width: 50%;
            box-sizing: border-box;
        }
        
        .timeline-item::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            background-color: white;
            border: 4px solid var(--primary-color);
            top: 15px;
            border-radius: 50%;
            z-index: 1;
        }
        
        .timeline-left {
            left: 0;
        }
        
        .timeline-right {
            left: 50%;
        }
        
        .timeline-left::after {
            right: -10px;
        }
        
        .timeline-right::after {
            left: -10px;
        }
        
        .timeline-content {
            padding: 1.5rem;
            background-color: white;
            position: relative;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
        }
        
        .timeline-content h3 {
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
            color: var(--primary-color);
        }
        
        .timeline-content p {
            margin-bottom: 0;
            font-size: 0.95rem;
            color: var(--gray-700);
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
            .about-container {
                grid-template-columns: 1fr;
            }
            
            .about-content, .about-sidebar {
                grid-column: span 12;
            }
            
            .about-sidebar {
                margin-top: 2rem;
            }
            
            .mission-vision {
                grid-template-columns: 1fr;
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
            
            .values-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            }
            
            .timeline::after {
                left: 31px;
            }
            
            .timeline-item {
                width: 100%;
                padding-left: 70px;
                padding-right: 25px;
            }
            
            .timeline-item::after {
                left: 21px;
            }
            
            .timeline-right {
                left: 0;
            }
        }
        
        @media (max-width: 576px) {
            .hero h2 {
                font-size: 2rem;
            }
            
            .hero p {
                font-size: 1rem;
            }
            
            .about-content h2, .values-header h2, .history-header h2 {
                font-size: 2rem;
            }
            
            .values-grid {
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
                    <li><a href="about_us.php" class="active">About Us</a></li>
                    <li><a href="ministries.php">Ministries</a></li>
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
            <li><a href="about_us.php" class="active">About Us</a></li>
            <li><a href="ministries.php">Ministries</a></li>
            <li><a href="sermons.php">Sermons</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>
    </div>
    <div class="mobile-menu-overlay" id="mobileMenuOverlay"></div>
    
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h2>About Our Church</h2>
            <p>Learn about our history, mission, vision, and the values that guide our community of faith.</p>
        </div>
    </section>
    
    <!-- About Section -->
    <section class="about-section">
        <div class="container">
            <div class="about-container">
                <div class="about-content">
                    <h2>Welcome to <?php echo $church_name; ?></h2>
                    <p>Founded in 1985, <?php echo $church_name; ?> has been a beacon of hope and faith in our community for over 35 years. We are a vibrant, welcoming congregation committed to sharing God's love through worship, fellowship, and service.</p>
                    
                    <p>Our church is more than just a building – it's a family of believers coming together to grow in faith and serve others. We believe in creating a warm, inclusive environment where everyone can experience God's presence and develop meaningful relationships.</p>
                    
                    <p>Whether you're exploring faith for the first time or looking for a church to call home, we invite you to join us. Our doors are open to all, and we look forward to welcoming you into our community.</p>
                    
                    <div class="mission-vision">
                        <div class="mission-card">
                            <h3><i class="fas fa-bullseye"></i> Our Mission</h3>
                            <p>To share the love of Christ, make disciples, and serve our community through worship, fellowship, education, and outreach programs that transform lives and glorify God.</p>
                        </div>
                        <div class="vision-card">
                            <h3><i class="fas fa-eye"></i> Our Vision</h3>
                            <p>To be a thriving, Christ-centered community where people of all backgrounds can experience God's love, grow in their faith, and be equipped to serve others in their daily lives.</p>
                        </div>
                    </div>
                </div>
                
                <div class="about-sidebar">
                    <div class="pastor-card">
                        <div class="pastor-image">
                            <img src="img/P1.jpg" alt="Pastor">
                        </div>
                        <div class="pastor-details">
                            <h3>Pastor John Smith</h3>
                            <h4>Senior Pastor</h4>
                            <p>Pastor John has been leading our congregation since 2010. With over 20 years of ministry experience, he is passionate about teaching God's Word and helping people grow in their relationship with Christ.</p>
                        </div>
                    </div>
                    
                    <div class="contact-card">
                        <h3>Visit Us</h3>
                        <ul class="contact-info">
                            <li>
                                <i class="fas fa-map-marker-alt"></i>
                                <span><?php echo $church_address; ?></span>
                            </li>
                            <li>
                                <i class="fas fa-clock"></i>
                                <span>Sunday Service: 10:00 AM</span>
                            </li>
                            <li>
                                <i class="fas fa-phone"></i>
                                <span><?php echo $church_phone; ?></span>
                            </li>
                            <li>
                                <i class="fas fa-envelope"></i>
                                <span><?php echo $church_email; ?></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Values Section -->
    <section class="values-section">
        <div class="values-container">
            <div class="values-header">
                <h2>Our Core Values</h2>
                <p>These principles guide our decisions, shape our culture, and reflect our commitment to living out our faith in meaningful ways.</p>
            </div>
            
            <div class="values-grid">
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <h3>Biblical Teaching</h3>
                    <p>We are committed to teaching God's Word faithfully and applying its timeless truths to our daily lives.</p>
                </div>
                
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-hands-helping"></i>
                    </div>
                    <h3>Compassionate Service</h3>
                    <p>We believe in demonstrating Christ's love through practical acts of service to those in need.</p>
                </div>
                
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Authentic Community</h3>
                    <p>We foster genuine relationships where people can find belonging, support, and spiritual growth.</p>
                </div>
                
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-pray"></i>
                    </div>
                    <h3>Passionate Worship</h3>
                    <p>We seek to honor God through heartfelt, Spirit-led worship that engages our hearts, minds, and souls.</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- History Section -->
    <section class="history-section">
        <div class="history-container">
            <div class="history-header">
                <h2>Our History</h2>
                <p>For over three decades, our church has been serving the community and growing in faith together.</p>
            </div>
            
            <div class="timeline">
                <div class="timeline-item timeline-left">
                    <div class="timeline-content">
                        <h3>1985</h3>
                        <p>Our church was founded by a small group of 25 dedicated believers meeting in a local community center.</p>
                    </div>
                </div>
                
                <div class="timeline-item timeline-right">
                    <div class="timeline-content">
                        <h3>1992</h3>
                        <p>We purchased our first building and expanded our ministries to include youth programs and community outreach.</p>
                    </div>
                </div>
                
                <div class="timeline-item timeline-left">
                    <div class="timeline-content">
                        <h3>2005</h3>
                        <p>Our congregation grew to over 200 members, and we began construction on our current sanctuary.</p>
                    </div>
                </div>
                
                <div class="timeline-item timeline-right">
                    <div class="timeline-content">
                        <h3>2010</h3>
                        <p>Pastor John Smith joined our church as Senior Pastor, bringing new vision and leadership.</p>
                    </div>
                </div>
                
                <div class="timeline-item timeline-left">
                    <div class="timeline-content">
                        <h3>2018</h3>
                        <p>We launched our online ministry to reach more people with the message of hope and faith.</p>
                    </div>
                </div>
                
                <div class="timeline-item timeline-right">
                    <div class="timeline-content">
                        <h3>Today</h3>
                        <p>We continue to grow and serve our community, with multiple services and ministries reaching hundreds of people each week.</p>
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

