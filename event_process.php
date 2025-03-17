<?php
session_start();
require_once 'config/db.php';

// Set the default timezone to ensure consistent time handling
date_default_timezone_set('Asia/Manila'); // Change to your timezone if needed

// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if action is set
if (!isset($_POST['action'])) {
    $_SESSION['error_message'] = "Invalid request";
    header("Location: events.php");
    exit();
}

$action = $_POST['action'];

// Function to sanitize input data
function sanitize_input($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return mysqli_real_escape_string($conn, $data);
}

// Handle different actions based on the 'action' parameter
switch ($action) {
    case 'add':
        // Get form data
        $event_name = sanitize_input($_POST['event_name']);
        $event_type = sanitize_input($_POST['event_type']);
        $event_date = sanitize_input($_POST['event_date']);
        
        // Format time values properly (Ensure time is in 'HH:mm' format)
        $start_time = date('H:i', strtotime($_POST['start_time']));
        $end_time = !empty($_POST['end_time']) ? date('H:i', strtotime($_POST['end_time'])) : NULL;
        
        $location = sanitize_input($_POST['location']);
        $description = isset($_POST['description']) ? sanitize_input($_POST['description']) : '';
        $organizer = isset($_POST['organizer']) ? sanitize_input($_POST['organizer']) : '';
        $status = sanitize_input($_POST['status']);
        $contact_person = isset($_POST['contact_person']) ? sanitize_input($_POST['contact_person']) : '';
        $contact_email = isset($_POST['contact_email']) ? sanitize_input($_POST['contact_email']) : '';
        $contact_phone = isset($_POST['contact_phone']) ? sanitize_input($_POST['contact_phone']) : '';
        $registration_required = isset($_POST['registration_required']) ? 1 : 0;
        $max_attendees = isset($_POST['max_attendees']) && $_POST['max_attendees'] ? intval($_POST['max_attendees']) : NULL;
        
        // Add current server timestamp for created_at and updated_at
        $current_timestamp = date('Y-m-d H:i:s');
        
        // Handle event image upload
        $event_image = 'default-event.jpg'; // Default image
        
        if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] == 0) {
            $upload_dir = 'uploads/event_images/';
            
            // Create directory if it doesn't exist
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            $file_name = time() . '_' . $_FILES['event_image']['name'];
            $file_path = $upload_dir . $file_name;
            
            // Check file type
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            if (in_array($_FILES['event_image']['type'], $allowed_types)) {
                if (move_uploaded_file($_FILES['event_image']['tmp_name'], $file_path)) {
                    $event_image = $file_name;
                }
            }
        }
        
        // Insert event into database
        $query = "INSERT INTO events (event_name, event_type, event_date, start_time, end_time, location, 
                  description, organizer, status, contact_person, contact_email, contact_phone, 
                  registration_required, max_attendees, event_image, created_at, updated_at) 
                  VALUES ('$event_name', '$event_type', '$event_date', '$start_time', " . 
                  ($end_time ? "'$end_time'" : "NULL") . ", '$location', '$description', '$organizer', 
                  '$status', '$contact_person', '$contact_email', '$contact_phone', $registration_required, " . 
                  ($max_attendees ? "$max_attendees" : "NULL") . ", '$event_image', '$current_timestamp', '$current_timestamp')";
        
        if (mysqli_query($conn, $query)) {
            $_SESSION['success_message'] = "Event added successfully!";
            header("Location: events.php");
            exit();
        } else {
            $_SESSION['error_message'] = "Error: " . mysqli_error($conn);
            header("Location: events.php?view=add");
            exit();
        }
        break;

    case 'edit':
        // Get form data
        $event_id = intval($_POST['event_id']);
        $event_name = sanitize_input($_POST['event_name']);
        $event_type = sanitize_input($_POST['event_type']);
        $event_date = sanitize_input($_POST['event_date']);
        
        // Format time values properly (Ensure time is in 'HH:mm' format)
        $start_time = date('H:i', strtotime($_POST['start_time']));
        $end_time = !empty($_POST['end_time']) ? date('H:i', strtotime($_POST['end_time'])) : NULL;
        
        $location = sanitize_input($_POST['location']);
        $description = isset($_POST['description']) ? sanitize_input($_POST['description']) : '';
        $organizer = isset($_POST['organizer']) ? sanitize_input($_POST['organizer']) : '';
        $status = sanitize_input($_POST['status']);
        $contact_person = isset($_POST['contact_person']) ? sanitize_input($_POST['contact_person']) : '';
        $contact_email = isset($_POST['contact_email']) ? sanitize_input($_POST['contact_email']) : '';
        $contact_phone = isset($_POST['contact_phone']) ? sanitize_input($_POST['contact_phone']) : '';
        $registration_required = isset($_POST['registration_required']) ? 1 : 0;
        $max_attendees = isset($_POST['max_attendees']) && $_POST['max_attendees'] ? intval($_POST['max_attendees']) : NULL;
        
        // Add current server timestamp for updated_at
        $current_timestamp = date('Y-m-d H:i:s');
        
        // Get current event data
        $query = "SELECT event_image FROM events WHERE id = $event_id";
        $result = mysqli_query($conn, $query);
        $event = mysqli_fetch_assoc($result);
        $event_image = $event['event_image'];
        
        // Handle event image upload
        if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] == 0) {
            $upload_dir = 'uploads/event_images/';
            
            // Create directory if it doesn't exist
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            $file_name = time() . '_' . $_FILES['event_image']['name'];
            $file_path = $upload_dir . $file_name;
            
            // Check file type
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            if (in_array($_FILES['event_image']['type'], $allowed_types)) {
                if (move_uploaded_file($_FILES['event_image']['tmp_name'], $file_path)) {
                    // Delete old image if not default
                    if ($event_image != 'default-event.jpg' && file_exists($upload_dir . $event_image)) {
                        unlink($upload_dir . $event_image);
                    }
                    $event_image = $file_name;
                }
            }
        }
        
        // Update event in database
        $query = "UPDATE events SET 
                  event_name = '$event_name', 
                  event_type = '$event_type', 
                  event_date = '$event_date', 
                  start_time = '$start_time', 
                  end_time = " . ($end_time ? "'$end_time'" : "NULL") . ", 
                  location = '$location', 
                  description = '$description', 
                  organizer = '$organizer', 
                  status = '$status', 
                  contact_person = '$contact_person', 
                  contact_email = '$contact_email', 
                  contact_phone = '$contact_phone', 
                  registration_required = $registration_required, 
                  max_attendees = " . ($max_attendees ? "$max_attendees" : "NULL") . ", 
                  event_image = '$event_image', 
                  updated_at = '$current_timestamp' 
                  WHERE id = $event_id";
        
        if (mysqli_query($conn, $query)) {
            $_SESSION['success_message'] = "Event updated successfully!";
            header("Location: events.php");
            exit();
        } else {
            $_SESSION['error_message'] = "Error: " . mysqli_error($conn);
            header("Location: events.php?view=edit&id=$event_id");
            exit();
        }
        break;

    case 'delete':
        // Retrieve and sanitize event ID
        $event_id = intval($_POST['event_id']);
        
        // Prepare and execute the SQL query to delete the event
        $query = "DELETE FROM events WHERE id = $event_id";
        
        if (mysqli_query($conn, $query)) {
            $_SESSION['success_message'] = "Event deleted successfully!";
            header("Location: events.php");
            exit();
        } else {
            $_SESSION['error_message'] = "Error deleting event: " . mysqli_error($conn);
            header("Location: events.php");
            exit();
        }
        break;

    default:
        $_SESSION['error_message'] = "Invalid action";
        header("Location: events.php");
        exit();
}

?>
