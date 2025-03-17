<?php
require_once 'config/db.php';

// Get church settings
$query = "SELECT * FROM settings WHERE id = 1";
$result = mysqli_query($conn, $query);
$settings = mysqli_fetch_assoc($result);
$church_name = isset($settings['church_name']) ? $settings['church_name'] : 'Church Management System';
$church_address = isset($settings['church_address']) ? $settings['church_address'] : '';
$church_phone = isset($settings['church_phone']) ? $settings['church_phone'] : '';
$church_email = isset($settings['church_email']) ? $settings['church_email'] : '';

// Fetch upcoming public events (events with dates >= today and public_display = 1)
$today = date('Y-m-d');
$query = "SELECT * FROM events 
          WHERE event_date >= '$today' AND status != 'Cancelled' AND public_display = 1
          ORDER BY event_date ASC, start_time ASC";
$events = mysqli_query($conn, $query);

// Get featured event (closest upcoming public event)
$featured_query = "SELECT * FROM events 
                  WHERE event_date >= '$today' AND status != 'Cancelled' AND public_display = 1
                  ORDER BY event_date ASC, start_time ASC LIMIT 1";
$featured_result = mysqli_query($conn, $featured_query);
$featured_event = mysqli_fetch_assoc($featured_result);

// Get event types for filter
$types_query = "SELECT DISTINCT event_type FROM events WHERE event_date >= '$today' AND status != 'Cancelled' AND public_display = 1";
$types_result = mysqli_query($conn, $types_query);
$event_types = [];
while ($type = mysqli_fetch_assoc($types_result)) {
    $event_types[] = $type['event_type'];
}

// Handle filtering
$filter_type = isset($_GET['type']) ? $_GET['type'] : '';
$filter_condition = '';
if (!empty($filter_type)) {
    $filter_type = mysqli_real_escape_string($conn, $filter_type);
    $filter_condition = " AND event_type = '$filter_type'";
    
    // Re-run the query with filter
    $query = "SELECT * FROM events 
              WHERE event_date >= '$today' AND status != 'Cancelled' AND public_display = 1 $filter_condition
              ORDER BY event_date ASC, start_time ASC";
    $events = mysqli_query($conn, $query);
}

// Check if this is being viewed from admin panel
$is_admin = isset($_GET['admin']) && $_GET['admin'] == 1;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upcoming Events - <?php echo $church_name; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <?php if (!$is_admin): ?>
    <!-- Only include these styles when viewing as standalone page -->
    <link rel="stylesheet" href="css/style.css">
    <?php endif; ?>
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
        
        .public-events-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
            font-family: 'Inter', sans-serif;
        }
        
        /* Header Styles */
        .events-header {
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
        
        .events-logo {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .events-logo img {
            height: 50px;
            width: auto;
        }
        
        .events-logo h1 {
            font-size: 1.5rem;
            margin-bottom: 0;
            color: var(--gray-900);
        }
        
        .events-nav ul {
            display: flex;
            list-style: none;
            gap: 2rem;
        }
        
        .events-nav a {
            color: var(--gray-700);
            font-weight: 500;
            padding: 0.5rem 0;
            position: relative;
        }
        
        .events-nav a:hover {
            color: var(--primary-color);
        }
        
        .events-nav a.active {
            color: var(--primary-color);
        }
        
        .events-nav a.active::after {
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
        .events-hero {
            background-color: var(--secondary-color);
            color: white;
            padding: 6rem 0;
            text-align: center;
            background-image: linear-gradient(to right, rgba(44, 62, 80, 0.9), rgba(52, 152, 219, 0.8)), url('img/church-bg.jpg');
            background-size: cover;
            background-position: center;
            position: relative;
            border-radius: var(--border-radius-lg);
            margin: 2rem 0 -4rem;
            overflow: hidden;
        }
        
        .events-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(52, 152, 219, 0.3) 0%, rgba(44, 62, 80, 0) 50%);
            z-index: 1;
        }
        
        .events-hero-content {
            position: relative;
            z-index: 2;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .events-hero h2 {
            font-size: 3.5rem;
            margin-bottom: 1.5rem;
            color: white;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        
        .events-hero p {
            font-size: 1.25rem;
            max-width: 700px;
            margin: 0 auto 2rem;
            color: rgba(255, 255, 255, 0.9);
        }
        
        .events-btn {
            display: inline-block;
            padding: 0.875rem 2rem;
            background-color: var(--primary-color);
            color: white;
            text-decoration: none;
            border-radius: var(--border-radius);
            font-weight: 600;
            transition: all var(--transition-normal) ease;
            border: none;
            cursor: pointer;
            box-shadow: var(--shadow);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.875rem;
        }
        
        .events-btn:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            color: white;
        }
        
        .events-btn:active {
            transform: translateY(0);
            box-shadow: var(--shadow);
        }
        
        .events-btn-secondary {
            background-color: transparent;
            border: 2px solid white;
            color: white;
        }
        
        .events-btn-secondary:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }
        
        /* Featured Event */
        .featured-event {
            margin: 0 auto 4rem;
            background-color: white;
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            max-width: 1000px;
            position: relative;
            z-index: 10;
            transform: translateY(4rem);
        }
        
        .featured-event-content {
            display: flex;
            flex-wrap: wrap;
        }
        
        .featured-event-image {
            flex: 1;
            min-width: 300px;
            background-color: var(--gray-200);
            position: relative;
            overflow: hidden;
            min-height: 350px;
        }
        
        .featured-event-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform var(--transition-slow) ease;
        }
        
        .featured-event:hover .featured-event-image img {
            transform: scale(1.05);
        }
        
        .featured-event-details {
            flex: 1;
            min-width: 300px;
            padding: 3rem;
        }
        
        .event-date-badge {
            display: inline-block;
            background-color: var(--primary-light);
            color: var(--primary-dark);
            padding: 0.5rem 1rem;
            border-radius: var(--border-radius-xl);
            font-weight: 600;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
            box-shadow: var(--shadow-sm);
        }
        
        .featured-event h3 {
            font-size: 2.25rem;
            margin-bottom: 1.5rem;
            color: var(--gray-900);
        }
        
        .event-meta {
            display: flex;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1.5rem;
        }
        
        .event-meta-item {
            display: flex;
            align-items: center;
            color: var(--gray-700);
            font-size: 0.95rem;
        }
        
        .event-meta-item i {
            margin-right: 0.5rem;
            color: var(--primary-color);
            font-size: 1.125rem;
        }
        
        .featured-event p {
            margin-bottom: 2rem;
            color: var(--gray-700);
            line-height: 1.8;
            font-size: 1.05rem;
        }
        
        /* Events Section */
        .events-section {
            padding: 6rem 0 4rem;
            background-color: var(--gray-100);
        }
        
        .events-section-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .events-section-header h2 {
            font-size: 2.5rem;
            color: var(--gray-900);
            margin-bottom: 1rem;
            position: relative;
            display: inline-block;
        }
        
        .events-section-header h2::after {
            content: '';
            position: absolute;
            bottom: -0.75rem;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: linear-gradient(to right, var(--primary-color), var(--primary-dark));
            border-radius: var(--border-radius);
        }
        
        .events-section-header p {
            color: var(--gray-600);
            max-width: 700px;
            margin: 0 auto;
            font-size: 1.125rem;
        }
        
        /* Filter Controls */
        .filter-controls {
            display: flex;
            justify-content: center;
            margin-bottom: 2.5rem;
            flex-wrap: wrap;
            gap: 0.75rem;
        }
        
        .filter-btn {
            padding: 0.625rem 1.25rem;
            background-color: white;
            border: none;
            border-radius: var(--border-radius-xl);
            cursor: pointer;
            transition: all var(--transition-normal) ease;
            color: var(--gray-700);
            font-weight: 500;
            text-decoration: none;
            box-shadow: var(--shadow-sm);
            font-size: 0.875rem;
        }
        
        .filter-btn:hover {
            background-color: var(--gray-200);
            transform: translateY(-2px);
            box-shadow: var(--shadow);
            color: var(--gray-900);
        }
        
        .filter-btn.active {
            background-color: var(--primary-color);
            color: white;
            box-shadow: var(--shadow);
        }
        
        .filter-btn.active:hover {
            background-color: var(--primary-dark);
        }
        
        /* Events Grid */
        .events-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }
        
        .event-card {
            background-color: white;
            border-radius: var(--border-radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: all var(--transition-normal) ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            position: relative;
        }
        
        .event-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
        }
        
        .event-card-image {
            height: 220px;
            overflow: hidden;
            position: relative;
        }
        
        .event-card-image::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0) 60%, rgba(0, 0, 0, 0.5));
            z-index: 1;
        }
        
        .event-card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform var(--transition-slow) ease;
        }
        
        .event-card:hover .event-card-image img {
            transform: scale(1.08);
        }
        
        .event-card-date {
            position: absolute;
            top: 1rem;
            left: 1rem;
            z-index: 2;
            display: inline-flex;
            align-items: center;
            background-color: rgba(255, 255, 255, 0.95);
            color: var(--gray-900);
            padding: 0.5rem 1rem;
            border-radius: var(--border-radius);
            font-weight: 600;
            font-size: 0.875rem;
            box-shadow: var(--shadow);
        }
        
        .event-card-date i {
            margin-right: 0.5rem;
            color: var(--primary-color);
        }
        
        .event-card-content {
            padding: 1.75rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        .event-card h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: var(--gray-900);
            line-height: 1.3;
        }
        
        .event-card-meta {
            display: flex;
            flex-direction: column;
            margin-bottom: 1.25rem;
            gap: 0.5rem;
        }
        
        .event-card-meta-item {
            display: flex;
            align-items: center;
            color: var(--gray-600);
            font-size: 0.875rem;
        }
        
        .event-card-meta-item i {
            margin-right: 0.5rem;
            color: var(--primary-color);
            width: 1rem;
            text-align: center;
        }
        
        .event-card-description {
            color: var(--gray-600);
            font-size: 0.95rem;
            margin-bottom: 1.5rem;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            flex: 1;
            line-height: 1.6;
        }
        
        .event-card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid var(--gray-200);
            padding-top: 1.25rem;
            margin-top: auto;
        }
        
        .event-card-type {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            background-color: var(--gray-100);
            border-radius: var(--border-radius);
            font-size: 0.75rem;
            color: var(--gray-700);
            font-weight: 500;
        }
        
        .event-card-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            transition: all var(--transition-fast) ease;
        }
        
        .event-card-link i {
            margin-left: 0.375rem;
            transition: transform var(--transition-fast) ease;
        }
        
        .event-card-link:hover {
            color: var(--primary-dark);
        }
        
        .event-card-link:hover i {
            transform: translateX(4px);
        }
        
        /* Status Badges */
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: var(--border-radius);
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: capitalize;
        }
        
        .scheduled {
            background-color: var(--primary-light);
            color: var(--primary-dark);
        }
        
        .completed {
            background-color: #e8f5e9;
            color: #2e7d32;
        }
        
        .postponed {
            background-color: #fff8e1;
            color: #f57f17;
        }
        
        .cancelled {
            background-color: #ffebee;
            color: #c62828;
        }
        
        /* No Events Message */
        .no-events {
            text-align: center;
            padding: 4rem 2rem;
            background-color: white;
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow);
        }
        
        .no-events i {
            font-size: 4rem;
            color: var(--gray-300);
            margin-bottom: 1.5rem;
        }
        
        .no-events h3 {
            font-size: 1.75rem;
            color: var(--gray-800);
            margin-bottom: 1rem;
        }
        
        .no-events p {
            color: var(--gray-600);
            margin-bottom: 1.5rem;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }
        
        /* Event Details Modal */
        .events-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.6);
            opacity: 0;
            transition: opacity var(--transition-normal) ease;
            backdrop-filter: blur(5px);
        }
        
        .events-modal.show {
            display: block;
            opacity: 1;
        }
        
        .events-modal-content {
            background-color: white;
            margin: 3rem auto;
            width: 90%;
            max-width: 900px;
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-xl);
            animation: modalFadeIn 0.4s ease-out;
            overflow: hidden;
        }
        
        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: translateY(-30px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        .events-modal-header {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .events-modal-header h2 {
            font-size: 1.75rem;
            color: var(--gray-900);
            margin-bottom: 0;
        }
        
        .close-events-modal {
            font-size: 1.75rem;
            cursor: pointer;
            color: var(--gray-500);
            transition: all var(--transition-fast) ease;
            width: 2rem;
            height: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }
        
        .close-events-modal:hover {
            color: var(--gray-900);
            background-color: var(--gray-100);
        }
        
        .events-modal-body {
            padding: 2rem;
        }
        
        .events-modal-image {
            width: 100%;
            height: 350px;
            overflow: hidden;
            border-radius: var(--border-radius);
            margin-bottom: 2rem;
            box-shadow: var(--shadow);
        }
        
        .events-modal-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .events-modal-details {
            margin-bottom: 2rem;
        }
        
        .events-modal-meta {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
            background-color: var(--gray-100);
            padding: 1.5rem;
            border-radius: var(--border-radius);
        }
        
        .events-modal-meta-item {
            display: flex;
            align-items: flex-start;
        }
        
        .events-modal-meta-item i {
            margin-right: 0.75rem;
            color: var(--primary-color);
            margin-top: 0.25rem;
            font-size: 1.125rem;
        }
        
        .events-modal-meta-item-content {
            flex: 1;
        }
        
        .events-modal-meta-item-content h4 {
            font-size: 0.875rem;
            color: var(--gray-600);
            margin-bottom: 0.25rem;
            font-family: 'Inter', sans-serif;
            font-weight: 500;
        }
        
        .events-modal-meta-item-content p {
            font-weight: 600;
            color: var(--gray-900);
            margin-bottom: 0;
        }
        
        .events-modal-description {
            margin-bottom: 2rem;
            line-height: 1.8;
            color: var(--gray-700);
            font-size: 1.05rem;
        }
        
        .events-modal-footer {
            padding: 1.5rem 2rem;
            border-top: 1px solid var(--gray-200);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: var(--gray-50);
        }
        
        .share-buttons {
            display: flex;
            align-items: center;
        }
        
        .share-buttons span {
            margin-right: 1rem;
            font-weight: 600;
            color: var(--gray-700);
        }
        
        .share-buttons a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            background-color: var(--gray-200);
            color: var(--gray-700);
            margin-right: 0.5rem;
            transition: all var(--transition-normal) ease;
        }
        
        .share-buttons a:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-3px);
            box-shadow: var(--shadow);
        }
        
        /* Footer */
        .events-footer {
            background-color: var(--gray-900);
            color: white;
            padding: 5rem 0 2rem;
            margin-top: 4rem;
        }
        
        .events-footer-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }
        
        .events-footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
            margin-bottom: 3rem;
        }
        
        .events-footer-column h3 {
            font-size: 1.25rem;
            margin-bottom: 1.5rem;
            position: relative;
            padding-bottom: 0.75rem;
            color: white;
        }
        
        .events-footer-column h3::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 2px;
            background-color: var(--primary-color);
        }
        
        .events-footer-column p {
            margin-bottom: 1.5rem;
            color: var(--gray-400);
            line-height: 1.7;
        }
        
        .events-footer-links {
            list-style: none;
        }
        
        .events-footer-links li {
            margin-bottom: 0.75rem;
        }
        
        .events-footer-links a {
            color: var(--gray-400);
            text-decoration: none;
            transition: all var(--transition-fast) ease;
            display: flex;
            align-items: center;
        }
        
        .events-footer-links a i {
            margin-right: 0.5rem;
            color: var(--primary-color);
            font-size: 0.875rem;
        }
        
        .events-footer-links a:hover {
            color: white;
            transform: translateX(5px);
        }
        
        .events-contact-info {
            list-style: none;
        }
        
        .events-contact-info li {
            margin-bottom: 1.25rem;
            display: flex;
            align-items: flex-start;
            color: var(--gray-400);
        }
        
        .events-contact-info i {
            margin-right: 0.75rem;
            color: var(--primary-color);
            margin-top: 0.25rem;
        }
        
        .events-social-links {
            display: flex;
            list-style: none;
            margin-top: 1.5rem;
            gap: 0.75rem;
        }
        
        .events-social-links a {
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
        
        .events-social-links a:hover {
            background-color: var(--primary-color);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        
        .events-footer-bottom {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--gray-500);
            font-size: 0.875rem;
        }
        
        /* Loader */
        .events-loader {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        
        .events-loader i {
            color: var(--primary-color);
            font-size: 2rem;
            margin-bottom: 1rem;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .events-loader p {
            color: var(--gray-600);
            font-weight: 500;
        }
        
        /* Responsive Styles */
        @media (max-width: 1024px) {
            .events-hero h2 {
                font-size: 3rem;
            }
            
            .featured-event-details {
                padding: 2.5rem;
            }
            
            .featured-event h3 {
                font-size: 2rem;
            }
        }
        
        @media (max-width: 768px) {
            .events-nav {
                display: none;
            }
            
            .mobile-menu-btn {
                display: block;
            }
            
            .events-hero {
                padding: 4rem 0;
                margin: 1rem 0 -3rem;
            }
            
            .events-hero h2 {
                font-size: 2.5rem;
            }
            
            .events-hero p {
                font-size: 1.125rem;
            }
            
            .featured-event {
                transform: translateY(3rem);
            }
            
            .featured-event-image, 
            .featured-event-details {
                flex: 100%;
            }
            
            .featured-event-image {
                height: 250px;
            }
            
            .featured-event-details {
                padding: 2rem;
            }
            
            .featured-event h3 {
                font-size: 1.75rem;
            }
            
            .event-meta {
                flex-direction: column;
                gap: 0.75rem;
            }
            
            .events-section {
                padding: 5rem 0 3rem;
            }
            
            .events-section-header h2 {
                font-size: 2rem;
            }
            
            .events-grid {
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            }
            
            .events-modal-content {
                margin: 2rem auto;
                width: 95%;
            }
            
            .events-modal-meta {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .events-modal-footer {
                flex-direction: column;
                gap: 1.5rem;
            }
            
            .share-buttons {
                width: 100%;
                justify-content: center;
            }
        }
        
        @media (max-width: 576px) {
            .events-hero {
                padding: 3rem 0;
                margin: 0.5rem 0 -2rem;
                border-radius: var(--border-radius);
            }
            
            .events-hero h2 {
                font-size: 2rem;
            }
            
            .events-hero p {
                font-size: 1rem;
            }
            
            .events-btn {
                display: block;
                width: 100%;
                margin-bottom: 1rem;
            }
            
            .featured-event {
                transform: translateY(2rem);
            }
            
            .featured-event-details {
                padding: 1.5rem;
            }
            
            .featured-event h3 {
                font-size: 1.5rem;
            }
            
            .events-section {
                padding: 4rem 0 2rem;
            }
            
            .events-section-header h2 {
                font-size: 1.75rem;
            }
            
            .events-section-header p {
                font-size: 1rem;
            }
            
            .events-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            
            .events-modal-header {
                padding: 1.25rem 1.5rem;
            }
            
            .events-modal-header h2 {
                font-size: 1.5rem;
            }
            
            .events-modal-body {
                padding: 1.5rem;
            }
            
            .events-modal-image {
                height: 200px;
            }
        }
        
        /* Animation Keyframes */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideUp {
            from { 
                opacity: 0;
                transform: translateY(20px);
            }
            to { 
                opacity: 1;
                transform: translateY(0);
            }
            
        }
        
        /* Animation Classes */
        .fade-in {
            animation: fadeIn 0.5s ease forwards;
        }
        
        .slide-up {
            animation: slideUp 0.5s ease forwards;
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
    </style>
</head>
<body <?php if (!$is_admin): ?>class="public-events-page"<?php endif; ?>>
    <?php if (!$is_admin): ?>
    <!-- Header for standalone page -->
    <header class="events-header">
        <div class="header-container">
            <div class="events-logo">
                <img src="img/jia.png" alt="<?php echo $church_name; ?> Logo">
                <h1><?php echo $church_name; ?></h1>
            </div>
            <nav class="events-nav">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="public_events.php" class="active">Events</a></li>
                    <li><a href="about_us.php">About Us</a></li>
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
        <div class="events-logo" style="margin-bottom: 1.5rem;">
            <img src="img/jia.png" alt="<?php echo $church_name; ?> Logo" style="height: 40px;">
            <h1 style="font-size: 1.25rem;"><?php echo $church_name; ?></h1>
        </div>
        <ul class="mobile-menu-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="public_events.php" class="active">Events</a></li>
            <li><a href="about_us.php">About Us</a></li>
            <li><a href="ministries.php">Ministries</a></li>
            <li><a href="sermons.php">Sermons</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>
    </div>
    <div class="mobile-menu-overlay" id="mobileMenuOverlay"></div>
    <?php endif; ?>
    
    <div class="public-events-container">
        <!-- Hero Section -->
        <section class="events-hero">
            <div class="events-hero-content">
                <h2>Join Our Upcoming Events</h2>
                <p>Be part of our vibrant community and participate in our upcoming events. From worship services to fellowship gatherings, there's something for everyone.</p>
                <a href="#events" class="events-btn">View All Events</a>
                <?php if (!$is_admin): ?>
                <a href="#contact" class="events-btn events-btn-secondary">Contact Us</a>
                <?php endif; ?>
            </div>
        </section>
        
        <!-- Featured Event Section -->
        <?php if ($featured_event): ?>
        <div class="featured-event">
            <div class="featured-event-content">
                <div class="featured-event-image">
                    <?php if ($featured_event['event_image'] && $featured_event['event_image'] != 'default-event.jpg'): ?>
                        <img src="uploads/event_images/<?php echo $featured_event['event_image']; ?>" alt="<?php echo $featured_event['event_name']; ?>">
                    <?php else: ?>
                        <img src="img/default-event.jpg" alt="<?php echo $featured_event['event_name']; ?>">
                    <?php endif; ?>
                </div>
                <div class="featured-event-details">
                    <span class="event-date-badge">
                        <i class="fas fa-calendar-alt"></i> 
                        <?php echo date('F d, Y', strtotime($featured_event['event_date'])); ?>
                    </span>
                    <h3><?php echo $featured_event['event_name']; ?></h3>
                    <div class="event-meta">
                        <div class="event-meta-item">
                            <i class="fas fa-clock"></i>
                            <?php 
                                echo date('h:i A', strtotime($featured_event['start_time']));
                                if($featured_event['end_time']) {
                                    echo ' - ' . date('h:i A', strtotime($featured_event['end_time']));
                                }
                            ?>
                        </div>
                        <div class="event-meta-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <?php echo $featured_event['location']; ?>
                        </div>
                        <div class="event-meta-item">
                            <i class="fas fa-tag"></i>
                            <?php echo $featured_event['event_type']; ?>
                        </div>
                    </div>
                    <p>
                        <?php 
                            if (strlen($featured_event['description']) > 200) {
                                echo substr($featured_event['description'], 0, 200) . '...';
                            } else {
                                echo $featured_event['description'];
                            }
                        ?>
                    </p>
                    <a href="#" class="events-btn event-details-btn" data-id="<?php echo $featured_event['id']; ?>">View Details</a>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Events Section -->
        <section class="events-section" id="events">
            <div class="events-section-header">
                <h2>Upcoming Events</h2>
                <p>Check out our calendar of upcoming events and join us in worship, fellowship, and community service.</p>
            </div>
            
            <!-- Filter Controls -->
            <div class="filter-controls">
                <a href="public_events.php<?php echo $is_admin ? '?admin=1' : ''; ?>" class="filter-btn <?php echo empty($filter_type) ? 'active' : ''; ?>">All</a>
                <?php foreach($event_types as $type): ?>
                    <a href="public_events.php?<?php echo $is_admin ? 'admin=1&' : ''; ?>type=<?php echo urlencode($type); ?>" class="filter-btn <?php echo ($filter_type == $type) ? 'active' : ''; ?>"><?php echo $type; ?></a>
                <?php endforeach; ?>
            </div>
            
            <!-- Events Grid -->
            <?php if (mysqli_num_rows($events) > 0): ?>
            <div class="events-grid">
                <?php while($event = mysqli_fetch_assoc($events)): ?>
                <div class="event-card">
                    <div class="event-card-image">
                        <?php if ($event['event_image'] && $event['event_image'] != 'default-event.jpg'): ?>
                            <img src="uploads/event_images/<?php echo $event['event_image']; ?>" alt="<?php echo $event['event_name']; ?>">
                        <?php else: ?>
                            <img src="img/default-event.jpg" alt="<?php echo $event['event_name']; ?>">
                    <?php endif; ?>
                    </div>
                    <span class="event-card-date">
                        <i class="fas fa-calendar-alt"></i> 
                        <?php echo date('M d, Y', strtotime($event['event_date'])); ?>
                    </span>
                    <div class="event-card-content">
                        <h3><?php echo $event['event_name']; ?></h3>
                        <div class="event-card-meta">
                            <div class="event-card-meta-item">
                                <i class="fas fa-clock"></i>
                                <?php 
                                    echo date('h:i A', strtotime($event['start_time']));
                                    if($event['end_time']) {
                                        echo ' - ' . date('h:i A', strtotime($event['end_time']));
                                    }
                                ?>
                            </div>
                            <div class="event-card-meta-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <?php echo $event['location']; ?>
                            </div>
                        </div>
                        <p class="event-card-description">
                            <?php 
                                if (strlen($event['description']) > 100) {
                                    echo substr($event['description'], 0, 100) . '...';
                                } else {
                                    echo $event['description'];
                                }
                            ?>
                        </p>
                        <div class="event-card-footer">
                            <span class="event-card-type"><?php echo $event['event_type']; ?></span>
                            <a href="#" class="event-card-link event-details-btn" data-id="<?php echo $event['id']; ?>">
                                Details <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            <?php else: ?>
            <div class="no-events">
                <i class="fas fa-calendar-times"></i>
                <h3>No Events Found</h3>
                <p>There are no upcoming events matching your criteria at this time. Please check back later or try a different filter.</p>
                <a href="public_events.php<?php echo $is_admin ? '?admin=1' : ''; ?>" class="events-btn">View All Events</a>
            </div>
            <?php endif; ?>
        </section>
    </div>
    
    <?php if (!$is_admin): ?>
    <!-- Footer for standalone page -->
    <footer class="events-footer" id="contact">
        <div class="events-footer-container">
            <div class="events-footer-content">
                <div class="events-footer-column">
                    <h3>About Us</h3>
                    <p><?php echo $church_name; ?> is a welcoming community of believers dedicated to worship, fellowship, and service. We strive to share God's love with all people.</p>
                    <ul class="events-social-links">
                        <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                        <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                        <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                        <li><a href="#"><i class="fab fa-youtube"></i></a></li>
                    </ul>
                </div>
                <div class="events-footer-column">
                    <h3>Quick Links</h3>
                    <ul class="events-footer-links">
                        <li><a href="index.php"><i class="fas fa-chevron-right"></i> Home</a></li>
                        <li><a href="public_events.php"><i class="fas fa-chevron-right"></i> Events</a></li>
                        <li><a href="about_us.php"><i class="fas fa-chevron-right"></i> About Us</a></li>
                        <li><a href="ministries.php"><i class="fas fa-chevron-right"></i> Ministries</a></li>
                        <li><a href="sermons.php"><i class="fas fa-chevron-right"></i> Sermons</a></li>
                        <li><a href="contact.php"><i class="fas fa-chevron-right"></i> Contact</a></li>
                    </ul>
                </div>
                <div class="events-footer-column">
                    <h3>Contact Us</h3>
                    <ul class="events-contact-info">
                        <li>
                            <i class="fas fa-map-marker-alt"></i>
                            <span><?php echo $church_address ? $church_address : '123 Church Street, City, State 12345'; ?></span>
                        </li>
                        <li>
                            <i class="fas fa-phone"></i>
                            <span><?php echo $church_phone ? $church_phone : '(123) 456-7890'; ?></span>
                        </li>
                        <li>
                            <i class="fas fa-envelope"></i>
                            <span><?php echo $church_email ? $church_email : 'info@churchname.org'; ?></span>
                        </li>
                        <li>
                            <i class="fas fa-clock"></i>
                            <span>Sunday Service: 10:00 AM</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="events-footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> <?php echo $church_name; ?>. All Rights Reserved.</p>
            </div>
        </div>
    </footer>
    <?php endif; ?>
    
    <!-- Event Details Modal -->
    <div id="eventModal" class="events-modal">
        <div class="events-modal-content">
            <div class="events-modal-header">
                <h2 id="modal-title">Event Details</h2>
                <span class="close-events-modal">&times;</span>
            </div>
            <div class="events-modal-body">
                <div id="modal-content-loader" class="events-loader">
                    <i class="fas fa-spinner fa-spin"></i>
                    <p>Loading event details...</p>
                </div>
                <div id="modal-content" style="display: none;">
                    <div class="events-modal-image" id="modal-image">
                        <img src="img/default-event.jpg" alt="Event Image">
                    </div>
                    <div class="events-modal-details">
                        <div class="events-modal-meta">
                            <div class="events-modal-meta-item">
                                <i class="fas fa-calendar-alt"></i>
                                <div class="events-modal-meta-item-content">
                                    <h4>Date</h4>
                                    <p id="modal-date">January 1, 2023</p>
                                </div>
                            </div>
                            <div class="events-modal-meta-item">
                                <i class="fas fa-clock"></i>
                                <div class="events-modal-meta-item-content">
                                    <h4>Time</h4>
                                    <p id="modal-time">10:00 AM - 12:00 PM</p>
                                </div>
                            </div>
                            <div class="events-modal-meta-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <div class="events-modal-meta-item-content">
                                    <h4>Location</h4>
                                    <p id="modal-location">Church Main Hall</p>
                                </div>
                            </div>
                            <div class="events-modal-meta-item">
                                <i class="fas fa-tag"></i>
                                <div class="events-modal-meta-item-content">
                                    <h4>Event Type</h4>
                                    <p id="modal-type">Worship Service</p>
                                </div>
                            </div>
                        </div>
                        <div class="events-modal-description" id="modal-description">
                            Event description will appear here.
                        </div>
                        <div class="events-modal-meta">
                            <div class="events-modal-meta-item">
                                <i class="fas fa-user"></i>
                                <div class="events-modal-meta-item-content">
                                    <h4>Organizer</h4>
                                    <p id="modal-organizer">Church Staff</p>
                                </div>
                            </div>
                            <div class="events-modal-meta-item">
                                <i class="fas fa-phone"></i>
                                <div class="events-modal-meta-item-content">
                                    <h4>Contact</h4>
                                    <p id="modal-contact">Contact Person</p>
                                </div>
                            </div>
                            <div class="events-modal-meta-item">
                                <i class="fas fa-envelope"></i>
                                <div class="events-modal-meta-item-content">
                                    <h4>Email</h4>
                                    <p id="modal-email">contact@example.com</p>
                                </div>
                            </div>
                            <div class="events-modal-meta-item">
                                <i class="fas fa-users"></i>
                                <div class="events-modal-meta-item-content">
                                    <h4>Registration</h4>
                                    <p id="modal-registration">Required</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="events-modal-footer">
                <div class="share-buttons">
                    <span>Share:</span>
                    <a href="#" class="share-facebook" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="share-twitter" target="_blank"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="share-email"><i class="fas fa-envelope"></i></a>
                </div>
                <a href="#" class="events-btn" id="modal-register-btn">Register Now</a>
            </div>
        </div>
    </div>
    
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
            
            // Modal functionality
            const modal = document.getElementById('eventModal');
            const closeModal = document.querySelector('.close-events-modal');
            const detailButtons = document.querySelectorAll('.event-details-btn');
            
            // Open modal when clicking on event details button
            detailButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const eventId = this.getAttribute('data-id');
                    openEventModal(eventId);
                });
            });
            
            // Close modal when clicking on X button
            closeModal.addEventListener('click', function() {
                modal.classList.remove('show');
                setTimeout(() => {
                    modal.style.display = 'none';
                }, 300);
            });
            
            // Close modal when clicking outside of it
            window.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.classList.remove('show');
                    setTimeout(() => {
                        modal.style.display = 'none';
                    }, 300);
                }
            });
            
            // Function to open event modal and load event details
            function openEventModal(eventId) {
                // Show loader
                document.getElementById('modal-content-loader').style.display = 'block';
                document.getElementById('modal-content').style.display = 'none';
                
                // Display modal
                modal.style.display = 'block';
                setTimeout(() => {
                    modal.classList.add('show');
                }, 10);
                
                // Fetch event details
                fetch('get_event.php?id=' + eventId)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const event = data.event;
                            
                            // Update modal content
                            document.getElementById('modal-title').textContent = event.event_name;
                            
                            // Update image
                            const modalImage = document.getElementById('modal-image').querySelector('img');
                            if (event.event_image && event.event_image !== 'default-event.jpg') {
                                modalImage.src = 'uploads/event_images/' + event.event_image;
                            } else {
                                modalImage.src = 'img/default-event.jpg';
                            }
                            modalImage.alt = event.event_name;
                            
                            // Update event details
                            document.getElementById('modal-date').textContent = formatDate(event.event_date);
                            
                            let timeText = formatTime(event.start_time);
                            if (event.end_time) {
                                timeText += ' - ' + formatTime(event.end_time);
                            }
                            document.getElementById('modal-time').textContent = timeText;
                            
                            document.getElementById('modal-location').textContent = event.location;
                            document.getElementById('modal-type').textContent = event.event_type;
                            document.getElementById('modal-description').textContent = event.description;
                            document.getElementById('modal-organizer').textContent = event.organizer || 'N/A';
                            document.getElementById('modal-contact').textContent = event.contact_person || 'N/A';
                            document.getElementById('modal-email').textContent = event.contact_email || 'N/A';
                            document.getElementById('modal-registration').textContent = event.registration_required ? 'Required' : 'Not Required';
                            
                            // Update register button
                            const registerBtn = document.getElementById('modal-register-btn');
                            if (event.registration_required) {
                                registerBtn.style.display = 'inline-block';
                                registerBtn.href = 'event_registration.php?id=' + event.id;
                            } else {
                                registerBtn.style.display = 'none';
                            }
                            
                            // Update share links
                            const pageUrl = encodeURIComponent(window.location.href);
                            const eventTitle = encodeURIComponent(event.event_name);
                            
                            document.querySelector('.share-facebook').href = `https://www.facebook.com/sharer/sharer.php?u=${pageUrl}`;
                            document.querySelector('.share-twitter').href = `https://twitter.com/intent/tweet?text=${eventTitle}&url=${pageUrl}`;
                            document.querySelector('.share-email').href = `mailto:?subject=${eventTitle}&body=Check out this event: ${pageUrl}`;
                            
                            // Hide loader and show content with animation
                            document.getElementById('modal-content-loader').style.display = 'none';
                            const modalContent = document.getElementById('modal-content');
                            modalContent.style.display = 'block';
                            modalContent.classList.add('fade-in');
                        } else {
                            alert('Error loading event details. Please try again.');
                            modal.classList.remove('show');
                            setTimeout(() => {
                                modal.style.display = 'none';
                            }, 300);
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching event details:', error);
                        alert('Error loading event details. Please try again.');
                        modal.classList.remove('show');
                        setTimeout(() => {
                            modal.style.display = 'none';
                        }, 300);
                    });
            }
            
            // Helper function to format date
            function formatDate(dateString) {
                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                return new Date(dateString).toLocaleDateString('en-US', options);
            }
            
            // Helper function to format time
            function formatTime(timeString) {
                const [hours, minutes] = timeString.split(':');
                const hour = parseInt(hours);
                const ampm = hour >= 12 ? 'PM' : 'AM';
                const hour12 = hour % 12 || 12;
                return `${hour12}:${minutes} ${ampm}`;
            }
            
            // Add animation to cards on scroll
            const animateOnScroll = () => {
                const cards = document.querySelectorAll('.event-card');
                cards.forEach(card => {
                    const cardPosition = card.getBoundingClientRect().top;
                    const screenPosition = window.innerHeight / 1.2;
                    
                    if (cardPosition < screenPosition) {
                        card.classList.add('slide-up');
                    }
                });
            };
            
            // Run animation on scroll
            window.addEventListener('scroll', animateOnScroll);
            // Run once on page load
            setTimeout(animateOnScroll, 500);
        });
    </script>
</body>
</html>

