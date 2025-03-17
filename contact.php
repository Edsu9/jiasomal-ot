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

// Process form submission
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    
    // Simple validation
    if (empty($name) || empty($email) || empty($message)) {
        $error_message = "Please fill in all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Please enter a valid email address.";
    } else {
        // In a real application, you would send an email here
        // For now, we'll just show a success message
        $success_message = "Thank you for your message! We will get back to you soon.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - <?php echo $church_name; ?></title>
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
        
        /* Contact Section */
        .contact-section {
            padding: 5rem 0;
        }
        
        .contact-container {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 2rem;
        }
        
        .contact-info {
            grid-column: span 5;
        }
        
        .contact-form {
            grid-column: span 7;
        }
        
        .contact-info h2 {
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
            position: relative;
            padding-bottom: 1rem;
        }
        
        .contact-info h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 80px;
            height: 3px;
            background: linear-gradient(to right, var(--primary-color), var(--primary-dark));
            border-radius: var(--border-radius);
        }
        
        .contact-info p {
            color: var(--gray-600);
            margin-bottom: 2rem;
            font-size: 1.05rem;
            line-height: 1.7;
        }
        
        .contact-details {
            list-style: none;
            margin-bottom: 2rem;
        }
        
        .contact-details li {
            display: flex;
            align-items: flex-start;
            margin-bottom: 1.5rem;
        }
        
        .contact-details i {
            width: 40px;
            height: 40px;
            background-color: var(--primary-light);
            color: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            flex-shrink: 0;
        }
        
        .contact-details-content h4 {
            font-size: 1.1rem;
            margin-bottom: 0.25rem;
            color: var(--gray-900);
        }
        
        .contact-details-content p {
            margin-bottom: 0;
            color: var(--gray-600);
        }
        
        .social-links {
            display: flex;
            list-style: none;
            gap: 1rem;
        }
        
        .social-links a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background-color: var(--gray-200);
            border-radius: 50%;
            color: var(--gray-700);
            transition: all var(--transition-normal) ease;
        }
        
        .social-links a:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-3px);
            box-shadow: var(--shadow);
        }
        
        .contact-form h2 {
            font-size: 2rem;
            margin-bottom: 1.5rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--gray-700);
        }
        
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--gray-300);
            border-radius: var(--border-radius);
            font-size: 1rem;
            transition: border-color var(--transition-fast) ease;
        }
        
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px var(--primary-light);
        }
        
        .form-group textarea {
            min-height: 150px;
            resize: vertical;
        }
        
        .submit-btn {
            display: inline-block;
            padding: 0.875rem 2rem;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: var(--border-radius);
            font-weight: 600;
            cursor: pointer;
            transition: all var(--transition-normal) ease;
        }
        
        .submit-btn:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }
        
        .success-message,
        .error-message {
            padding: 1rem;
            border-radius: var(--border-radius);
            margin-bottom: 1.5rem;
        }
        
        .success-message {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        /* Map Section */
        .map-section {
            padding: 0 0 5rem;
        }
        
        .map-container {
            height: 400px;
            border-radius: var(--border-radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow);
        }
        
        .map-container iframe {
            width: 100%;
            height: 100%;
            border: 0;
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
        
        .footer-social-links {
            display: flex;
            list-style: none;
            margin-top: 1.5rem;
            gap: 0.75rem;
        }
        
        .footer-social-links a {
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
        
        .footer-social-links a:hover {
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
            .contact-container {
                grid-template-columns: 1fr;
            }
            
            .contact-info, .contact-form {
                grid-column: span 12;
            }
            
            .contact-info {
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
            
            .contact-info h2, .contact-form h2 {
                font-size: 2rem;
            }
        }
        
        @media (max-width: 576px) {
            .hero h2 {
                font-size: 2rem;
            }
            
            .hero p {
                font-size: 1rem;
            }
            
            .contact-info h2, .contact-form h2 {
                font-size: 1.75rem;
            }
            
            .contact-details li {
                flex-direction: column;
            }
            
            .contact-details i {
                margin-bottom: 0.75rem;
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
                    <li><a href="ministries.php">Ministries</a></li>
                    <li><a href="sermons.php">Sermons</a></li>
                    <li><a href="contact.php" class="active">Contact</a></li>
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
            <li><a href="ministries.php">Ministries</a></li>
            <li><a href="sermons.php">Sermons</a></li>
            <li><a href="contact.php" class="active">Contact</a></li>
        </ul>
    </div>
    <div class="mobile-menu-overlay" id="mobileMenuOverlay"></div>
    
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h2>Contact Us</h2>
            <p>We'd love to hear from you! Reach out with any questions, prayer requests, or to learn more about our church community.</p>
        </div>
    </section>
    
    <!-- Contact Section -->
    <section class="contact-section">
        <div class="container">
            <div class="contact-container">
                <div class="contact-info">
                    <h2>Get In Touch</h2>
                    <p>Whether you have a question about our services, want to join a ministry, or just want to say hello, we're here to help. Feel free to reach out using any of the methods below.</p>
                    
                    <ul class="contact-details">
                        <li>
                            <i class="fas fa-map-marker-alt"></i>
                            <div class="contact-details-content">
                                <h4>Our Location</h4>
                                <p><?php echo $church_address; ?></p>
                            </div>
                        </li>
                        <li>
                            <i class="fas fa-phone"></i>
                            <div class="contact-details-content">
                                <h4>Phone Number</h4>
                                <p><?php echo $church_phone; ?></p>
                            </div>
                        </li>
                        <li>
                            <i class="fas fa-envelope"></i>
                            <div class="contact-details-content">
                                <h4>Email Address</h4>
                                <p><?php echo $church_email; ?></p>
                            </div>
                        </li>
                        <li>
                            <i class="fas fa-clock"></i>
                            <div class="contact-details-content">
                                <h4>Service Hours</h4>
                                <p>Sunday: 10:00 AM - 12:00 PM<br>Wednesday: 7:00 PM - 8:30 PM</p>
                            </div>
                        </li>
                    </ul>
                    
                    <h4>Connect With Us</h4>
                    <ul class="social-links">
                        <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                        <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                        <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                        <li><a href="#"><i class="fab fa-youtube"></i></a></li>
                    </ul>
                </div>
                
                <div class="contact-form">
                    <h2>Send Us a Message</h2>
                    
                    <?php if ($success_message): ?>
                        <div class="success-message"><?php echo $success_message; ?></div>
                    <?php endif; ?>
                    
                    <?php if ($error_message): ?>
                        <div class="error-message"><?php echo $error_message; ?></div>
                    <?php endif; ?>
                    
                    <form action="contact.php" method="post">
                        <div class="form-group">
                            <label for="name">Your Name <span style="color: var(--danger-color);">*</span></label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Your Email <span style="color: var(--danger-color);">*</span></label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <input type="text" id="subject" name="subject">
                        </div>
                        <div class="form-group">
                            <label for="message">Your Message <span style="color: var(--danger-color);">*</span></label>
                            <textarea id="message" name="message" required></textarea>
                        </div>
                        <button type="submit" class="submit-btn">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Map Section -->
    <section class="map-section">
        <div class="container">
            <div class="map-container">
                <iframe src="https://www.google.com/maps/embed?pb=!4v1742110960545!6m8!1m7!1s4fBoiwIPcnAdVc10O2y8Yg!2m2!1d12.87350019252904!2d124.0097437799398!3f90!4f0!5f0.7820865974627469" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>About Us</h3>
                    <p><?php echo $church_name; ?> is a welcoming community of believers dedicated to worship, fellowship, and service. We strive to share God's love with all people.</p>
                    <ul class="footer-social-links">
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

