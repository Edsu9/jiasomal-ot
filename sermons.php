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
    <title>Sermons - <?php echo $church_name; ?></title>
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
        
        /* Sermons Section */
        .sermons-section {
            padding: 5rem 0;
        }
        
        .sermons-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .sermons-header h2 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            position: relative;
            display: inline-block;
            padding-bottom: 1rem;
        }
        
        .sermons-header h2::after {
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
        
        .sermons-header p {
            max-width: 700px;
            margin: 0 auto;
            color: var(--gray-600);
            font-size: 1.1rem;
        }
        
        .sermons-filter {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        
        .filter-btn {
            padding: 0.5rem 1.25rem;
            background-color: white;
            border: 1px solid var(--gray-300);
            border-radius: var(--border-radius);
            color: var(--gray-700);
            font-weight: 500;
            cursor: pointer;
            transition: all var(--transition-normal) ease;
        }
        
        .filter-btn:hover {
            background-color: var(--gray-100);
            border-color: var(--gray-400);
        }
        
        .filter-btn.active {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }
        
        .sermons-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2rem;
        }
        
        .sermon-card {
            background-color: white;
            border-radius: var(--border-radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: all var(--transition-normal) ease;
            display: flex;
            flex-direction: column;
        }
        
        .sermon-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }
        
        .sermon-image {
            height: 200px;
            overflow: hidden;
            position: relative;
        }
        
        .sermon-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform var(--transition-slow) ease;
        }
        
        .sermon-card:hover .sermon-image img {
            transform: scale(1.05);
        }
        
        .sermon-play-btn {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 60px;
            height: 60px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 1.5rem;
            cursor: pointer;
            transition: all var(--transition-normal) ease;
            z-index: 2;
        }
        
        .sermon-play-btn:hover {
            background-color: white;
            transform: translate(-50%, -50%) scale(1.1);
            color: var(--primary-dark);
        }
        
        .sermon-content {
            padding: 1.5rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        .sermon-date {
            color: var(--primary-color);
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
        }
        
        .sermon-date i {
            margin-right: 0.5rem;
        }
        
        .sermon-content h3 {
            font-size: 1.25rem;
            margin-bottom: 0.75rem;
            line-height: 1.4;
        }
        
        .sermon-content h3 a {
            color: var(--gray-900);
            text-decoration: none;
            transition: color var(--transition-fast) ease;
        }
        
        .sermon-content h3 a:hover {
            color: var(--primary-color);
        }
        
        .sermon-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .sermon-meta-item {
            display: flex;
            align-items: center;
            color: var(--gray-600);
            font-size: 0.875rem;
        }
        
        .sermon-meta-item i {
            margin-right: 0.5rem;
            color: var(--primary-color);
        }
        
        .sermon-excerpt {
            color: var(--gray-600);
            font-size: 0.95rem;
            margin-bottom: 1.5rem;
            line-height: 1.6;
            flex: 1;
        }
        
        .sermon-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: auto;
        }
        
        .sermon-series {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            background-color: var(--primary-light);
            color: var(--primary-dark);
            border-radius: var(--border-radius);
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .sermon-links {
            display: flex;
            gap: 0.75rem;
        }
        
        .sermon-link {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray-700);
            transition: all var(--transition-normal) ease;
        }
        
        .sermon-link:hover {
            background-color: var(--primary-color);
            color: white;
        }
        
        /* Featured Sermon */
        .featured-sermon {
            background-color: white;
            border-radius: var(--border-radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-lg);
            margin-bottom: 4rem;
        }
        
        .featured-sermon-content {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
        }
        
        .featured-sermon-image {
            grid-column: span 5;
            position: relative;
            min-height: 400px;
        }
        
        .featured-sermon-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .featured-sermon-play {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80px;
            height: 80px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 2rem;
            cursor: pointer;
            transition: all var(--transition-normal) ease;
            z-index: 2;
        }
        
        .featured-sermon-play:hover {
            background-color: white;
            transform: translate(-50%, -50%) scale(1.1);
            color: var(--primary-dark);
        }
        
        .featured-sermon-details {
            grid-column: span 7;
            padding: 3rem;
            display: flex;
            flex-direction: column;
        }
        
        .featured-label {
            display: inline-block;
            padding: 0.5rem 1rem;
            background-color: var(--primary-light);
            color: var(--primary-dark);
            border-radius: var(--border-radius);
            font-weight: 600;
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
        }
        
        .featured-sermon-details h3 {
            font-size: 2rem;
            margin-bottom: 1rem;
            line-height: 1.3;
        }
        
        .featured-sermon-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .featured-sermon-meta-item {
            display: flex;
            align-items: center;
            color: var(--gray-600);
        }
        
        .featured-sermon-meta-item i {
            margin-right: 0.5rem;
            color: var(--primary-color);
        }
        
        .featured-sermon-excerpt {
            color: var(--gray-600);
            font-size: 1.05rem;
            line-height: 1.7;
            margin-bottom: 2rem;
            flex: 1;
        }
        
        .featured-sermon-actions {
            display: flex;
            gap: 1rem;
            margin-top: auto;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.5rem;
            border-radius: var(--border-radius);
            font-weight: 600;
            transition: all var(--transition-normal) ease;
            cursor: pointer;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
            border: none;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }
        
        .btn-outline {
            background-color: transparent;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
        }
        
        .btn-outline:hover {
            background-color: var(--primary-light);
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }
        
        .btn i {
            margin-right: 0.5rem;
        }
        
        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 3rem;
            gap: 0.5rem;
        }
        
        .pagination-item {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: var(--border-radius);
            background-color: white;
            color: var(--gray-700);
            font-weight: 500;
            transition: all var(--transition-normal) ease;
            cursor: pointer;
            box-shadow: var(--shadow-sm);
        }
        
        .pagination-item:hover {
            background-color: var(--gray-100);
            color: var(--primary-color);
        }
        
        .pagination-item.active {
            background-color: var(--primary-color);
            color: white;
        }
        
        .pagination-item.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        /* Subscribe Section */
        .subscribe-section {
            padding: 5rem 0;
            background-color: var(--gray-100);
        }
        
        .subscribe-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }
        
        .subscribe-content {
            background-color: white;
            border-radius: var(--border-radius-lg);
            padding: 3rem;
            box-shadow: var(--shadow);
            text-align: center;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .subscribe-content h2 {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: var(--gray-900);
        }
        
        .subscribe-content p {
            color: var(--gray-600);
            margin-bottom: 2rem;
            font-size: 1.05rem;
        }
        
        .subscribe-form {
            display: flex;
            max-width: 500px;
            margin: 0 auto;
        }
        
        .subscribe-form input {
            flex: 1;
            padding: 0.75rem 1rem;
            border: 1px solid var(--gray-300);
            border-radius: var(--border-radius) 0 0 var(--border-radius);
            font-size: 1rem;
            outline: none;
            transition: border-color var(--transition-fast) ease;
        }
        
        .subscribe-form input:focus {
            border-color: var(--primary-color);
        }
        
        .subscribe-form button {
            padding: 0.75rem 1.5rem;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 0 var(--border-radius) var(--border-radius) 0;
            font-weight: 600;
            cursor: pointer;
            transition: background-color var(--transition-fast) ease;
        }
        
        .subscribe-form button:hover {
            background-color: var(--primary-dark);
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
            .featured-sermon-content {
                grid-template-columns: 1fr;
            }
            
            .featured-sermon-image, .featured-sermon-details {
                grid-column: span 12;
            }
            
            .featured-sermon-image {
                height: 300px;
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
            
            .sermons-grid {
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            }
        }
        
        @media (max-width: 576px) {
            .hero h2 {
                font-size: 2rem;
            }
            
            .hero p {
                font-size: 1rem;
            }
            
            .sermons-header h2, .subscribe-content h2 {
                font-size: 1.75rem;
            }
            
            .featured-sermon-details h3 {
                font-size: 1.5rem;
            }
            
            .sermons-grid {
                grid-template-columns: 1fr;
            }
            
            .featured-sermon-actions {
                flex-direction: column;
                gap: 0.75rem;
            }
            
            .btn {
                width: 100%;
            }
            
            .subscribe-form {
                flex-direction: column;
            }
            
            .subscribe-form input {
                border-radius: var(--border-radius);
                margin-bottom: 0.75rem;
            }
            
            .subscribe-form button {
                border-radius: var(--border-radius);
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
                    <li><a href="sermons.php" class="active">Sermons</a></li>
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
            <li><a href="ministries.php">Ministries</a></li>
            <li><a href="sermons.php" class="active">Sermons</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>
    </div>
    <div class="mobile-menu-overlay" id="mobileMenuOverlay"></div>
    
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h2>Sermons & Messages</h2>
            <p>Listen to our latest sermons and messages to grow in your faith and deepen your understanding of God's Word.</p>
        </div>
    </section>
    
    <!-- Featured Sermon -->
    <div class="container" style="margin-top: -4rem; position: relative; z-index: 10;">
        <div class="featured-sermon">
            <div class="featured-sermon-content">
                <div class="featured-sermon-image">
                    <img src="img/featured-sermon.jpg" alt="Featured Sermon">
                    <div class="featured-sermon-play">
                        <i class="fas fa-play"></i>
                    </div>
                </div>
                <div class="featured-sermon-details">
                    <span class="featured-label">Featured Sermon</span>
                    <h3>Finding Peace in the Midst of Chaos</h3>
                    <div class="featured-sermon-meta">
                        <div class="featured-sermon-meta-item">
                            <i class="fas fa-user"></i>
                            <span>Pastor John Smith</span>
                        </div>
                        <div class="featured-sermon-meta-item">
                            <i class="fas fa-calendar"></i>
                            <span>March 12, 2023</span>
                        </div>
                        <div class="featured-sermon-meta-item">
                            <i class="fas fa-bible"></i>
                            <span>Philippians 4:6-7</span>
                        </div>
                    </div>
                    <p class="featured-sermon-excerpt">
                        In this powerful message, Pastor John explores how we can find true peace even in life's most challenging circumstances. Drawing from Paul's letter to the Philippians, we learn practical steps to overcome anxiety and experience the peace that surpasses all understanding.
                    </p>
                    <div class="featured-sermon-actions">
                        <a href="#" class="btn btn-primary"><i class="fas fa-play"></i> Listen Now</a>
                        <a href="#" class="btn btn-outline"><i class="fas fa-download"></i> Download</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sermons Section -->
    <section class="sermons-section">
        <div class="container">
            <div class="sermons-header">
                <h2>Recent Sermons</h2>
                <p>Explore our collection of sermons to find inspiration, guidance, and biblical teaching for your spiritual journey.</p>
            </div>
            
            <div class="sermons-filter">
                <button class="filter-btn active">All</button>
                <button class="filter-btn">Faith</button>
                <button class="filter-btn">Prayer</button>
                <button class="filter-btn">Family</button>
                <button class="filter-btn">Worship</button>
                <button class="filter-btn">Discipleship</button>
            </div>
            
            <div class="sermons-grid">
                <!-- Sermon 1 -->
                <div class="sermon-card">
                    <div class="sermon-image">
                        <img src="img/sermon-1.jpg" alt="Sermon">
                        <div class="sermon-play-btn">
                            <i class="fas fa-play"></i>
                        </div>
                    </div>
                    <div class="sermon-content">
                        <div class="sermon-date">
                            <i class="fas fa-calendar-alt"></i> March 5, 2023
                        </div>
                        <h3><a href="#">The Power of Persistent Prayer</a></h3>
                        <div class="sermon-meta">
                            <div class="sermon-meta-item">
                                <i class="fas fa-user"></i> Pastor John Smith
                            </div>
                            <div class="sermon-meta-item">
                                <i class="fas fa-bible"></i> Luke 18:1-8
                            </div>
                        </div>
                        <p class="sermon-excerpt">
                            Learn how persistent prayer can transform your life and deepen your relationship with God through the parable of the persistent widow.
                        </p>
                        <div class="sermon-footer">
                            <span class="sermon-series">Prayer Series</span>
                            <div class="sermon-links">
                                <a href="#" class="sermon-link" title="Download"><i class="fas fa-download"></i></a>
                                <a href="#" class="sermon-link" title="Share"><i class="fas fa-share-alt"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sermon 2 -->
                <div class="sermon-card">
                    <div class="sermon-image">
                        <img src="img/sermon-2.jpg" alt="Sermon">
                        <div class="sermon-play-btn">
                            <i class="fas fa-play"></i>
                        </div>
                    </div>
                    <div class="sermon-content">
                        <div class="sermon-date">
                            <i class="fas fa-calendar-alt"></i> February 26, 2023
                        </div>
                        <h3><a href="#">Building a Strong Family Foundation</a></h3>
                        <div class="sermon-meta">
                            <div class="sermon-meta-item">
                                <i class="fas fa-user"></i> Pastor Sarah Johnson
                            </div>
                            <div class="sermon-meta-item">
                                <i class="fas fa-bible"></i> Ephesians 5:21-6:4
                            </div>
                        </div>
                        <p class="sermon-excerpt">
                            Discover biblical principles for creating a loving, supportive family environment where each member can thrive and grow in faith.
                        </p>
                        <div class="sermon-footer">
                            <span class="sermon-series">Family Series</span>
                            <div class="sermon-links">
                                <a href="#" class="sermon-link" title="Download"><i class="fas fa-download"></i></a>
                                <a href="#" class="sermon-link" title="Share"><i class="fas fa-share-alt"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sermon 3 -->
                <div class="sermon-card">
                    <div class="sermon-image">
                        <img src="img/sermon-3.jpg" alt="Sermon">
                        <div class="sermon-play-btn">
                            <i class="fas fa-play"></i>
                        </div>
                    </div>
                    <div class="sermon-content">
                        <div class="sermon-date">
                            <i class="fas fa-calendar-alt"></i> February 19, 2023
                        </div>
                        <h3><a href="#">Faith That Moves Mountains</a></h3>
                        <div class="sermon-meta">
                            <div class="sermon-meta-item">
                                <i class="fas fa-user"></i> Pastor John Smith
                            </div>
                            <div class="sermon-meta-item">
                                <i class="fas fa-bible"></i> Matthew 17:20
                            </div>
                        </div>
                        <p class="sermon-excerpt">
                            Explore what it means to have mountain-moving faith and how we can develop this kind of trust in God's power and promises.
                        </p>
                        <div class="sermon-footer">
                            <span class="sermon-series">Faith Series</span>
                            <div class="sermon-links">
                                <a href="#" class="sermon-link" title="Download"><i class="fas fa-download"></i></a>
                                <a href="#" class="sermon-link" title="Share"><i class="fas fa-share-alt"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sermon 4 -->
                <div class="sermon-card">
                    <div class="sermon-image">
                        <img src="img/sermon-4.jpg" alt="Sermon">
                        <div class="sermon-play-btn">
                            <i class="fas fa-play"></i>
                        </div>
                    </div>
                    <div class="sermon-content">
                        <div class="sermon-date">
                            <i class="fas fa-calendar-alt"></i> February 12, 2023
                        </div>
                        <h3><a href="#">The Heart of Worship</a></h3>
                        <div class="sermon-meta">
                            <div class="sermon-meta-item">
                                <i class="fas fa-user"></i> Pastor Michael Davis
                            </div>
                            <div class="sermon-meta-item">
                                <i class="fas fa-bible"></i> John 4:23-24
                            </div>
                        </div>
                        <p class="sermon-excerpt">
                            Discover what it means to worship God in spirit and truth, and how authentic worship transforms our relationship with God.
                        </p>
                        <div class="sermon-footer">
                            <span class="sermon-series">Worship Series</span>
                            <div class="sermon-links">
                                <a href="#" class="sermon-link" title="Download"><i class="fas fa-download"></i></a>
                                <a href="#" class="sermon-link" title="Share"><i class="fas fa-share-alt"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sermon 5 -->
                <div class="sermon-card">
                    <div class="sermon-image">
                        <img src="img/sermon-5.jpg" alt="Sermon">
                        <div class="sermon-play-btn">
                            <i class="fas fa-play"></i>
                        </div>
                    </div>
                    <div class="sermon-content">
                        <div class="sermon-date">
                            <i class="fas fa-calendar-alt"></i> February 5, 2023
                        </div>
                        <h3><a href="#">Living as Disciples in a Secular World</a></h3>
                        <div class="sermon-meta">
                            <div class="sermon-meta-item">
                                <i class="fas fa-user"></i> Pastor John Smith
                            </div>
                            <div class="sermon-meta-item">
                                <i class="fas fa-bible"></i> Romans 12:1-2
                            </div>
                        </div>
                        <p class="sermon-excerpt">
                            Learn practical ways to live out your faith and be a light in a world that often rejects Christian values and principles.
                        </p>
                        <div class="sermon-footer">
                            <span class="sermon-series">Discipleship Series</span>
                            <div class="sermon-links">
                                <a href="#" class="sermon-link" title="Download"><i class="fas fa-download"></i></a>
                                <a href="#" class="sermon-link" title="Share"><i class="fas fa-share-alt"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sermon 6 -->
                <div class="sermon-card">
                    <div class="sermon-image">
                        <img src="img/sermon-6.jpg" alt="Sermon">
                        <div class="sermon-play-btn">
                            <i class="fas fa-play"></i>
                        </div>
                    </div>
                    <div class="sermon-content">
                        <div class="sermon-date">
                            <i class="fas fa-calendar-alt"></i> January 29, 2023
                        </div>
                        <h3><a href="#">Finding Your Purpose in God's Plan</a></h3>
                        <div class="sermon-meta">
                            <div class="sermon-meta-item">
                                <i class="fas fa-user"></i> Pastor Sarah Johnson
                            </div>
                            <div class="sermon-meta-item">
                                <i class="fas fa-bible"></i> Jeremiah 29:11-13
                            </div>
                        </div>
                        <p class="sermon-excerpt">
                            Discover how to identify and fulfill God's unique purpose for your life through prayer, Scripture, and community.
                        </p>
                        <div class="sermon-footer">
                            <span class="sermon-series">Purpose Series</span>
                            <div class="sermon-links">
                                <a href="#" class="sermon-link" title="Download"><i class="fas fa-download"></i></a>
                                <a href="#" class="sermon-link" title="Share"><i class="fas fa-share-alt"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Pagination -->
            <div class="pagination">
                <a href="#" class="pagination-item disabled"><i class="fas fa-chevron-left"></i></a>
                <a href="#" class="pagination-item active">1</a>
                <a href="#" class="pagination-item">2</a>
                <a href="#" class="pagination-item">3</a>
                <a href="#" class="pagination-item">4</a>
                <a href="#" class="pagination-item"><i class="fas fa-chevron-right"></i></a>
            </div>
        </div>
    </section>
    
    <!-- Subscribe Section -->
    <section class="subscribe-section">
        <div class="subscribe-container">
            <div class="subscribe-content">
                <h2>Subscribe to Our Sermon Podcast</h2>
                <p>Get notified when new sermons are available. Subscribe to our podcast and never miss a message.</p>
                <form class="subscribe-form">
                    <input type="email" placeholder="Your email address" required>
                    <button type="submit">Subscribe</button>
                </form>
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
            
            // Filter Buttons
            const filterBtns = document.querySelectorAll('.filter-btn');
            
            filterBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    // Remove active class from all buttons
                    filterBtns.forEach(b => b.classList.remove('active'));
                    
                    // Add active class to clicked button
                    this.classList.add('active');
                    
                    // Here you would typically filter the sermons based on the selected category
                    // For this demo, we're just changing the button state
                });
            });
        });
    </script>
</body>
</html>

