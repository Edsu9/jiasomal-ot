<?php
session_start();
require_once 'config/db.php';

// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Handle form submission for adding new member
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_member'])) {
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $membership_status = mysqli_real_escape_string($conn, $_POST['membership_status']);
    
    // Additional fields
    $city = isset($_POST['city']) ? mysqli_real_escape_string($conn, $_POST['city']) : '';
    $state = isset($_POST['state']) ? mysqli_real_escape_string($conn, $_POST['state']) : '';
    $zip = isset($_POST['zip']) ? mysqli_real_escape_string($conn, $_POST['zip']) : '';
    $join_date = isset($_POST['join_date']) ? mysqli_real_escape_string($conn, $_POST['join_date']) : NULL;
    $ministry = isset($_POST['ministry']) ? mysqli_real_escape_string($conn, $_POST['ministry']) : '';
    $notes = isset($_POST['notes']) ? mysqli_real_escape_string($conn, $_POST['notes']) : '';
    
    // Handle profile photo upload
    $profile_photo = 'default.jpg'; // Default photo
    if(isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['profile_photo']['name'];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);
        
        if(in_array(strtolower($filetype), $allowed)) {
            $new_filename = uniqid() . '.' . $filetype;
            $upload_dir = 'uploads/profile_photos/';
            
            // Create directory if it doesn't exist
            if(!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            if(move_uploaded_file($_FILES['profile_photo']['tmp_name'], $upload_dir . $new_filename)) {
                $profile_photo = $new_filename;
            }
        }
    }
    
    // Insert into database with extended fields
    $query = "INSERT INTO members (first_name, last_name, email, phone, address, city, state, zip, date_of_birth, gender, membership_status, join_date, ministry, notes, profile_photo) 
              VALUES ('$first_name', '$last_name', '$email', '$phone', '$address', '$city', '$state', '$zip', '$date_of_birth', '$gender', '$membership_status', " . ($join_date ? "'$join_date'" : "NULL") . ", '$ministry', '$notes', '$profile_photo')";
    
    if (mysqli_query($conn, $query)) {
        $success_message = "Member added successfully!";
    } else {
        $error_message = "Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Member - Church Management System</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <!-- Sidebar content -->
        </div>

        <div class="main-content">
            <div class="add-member-container">
                <form class="add-member-form" action="add_member.php" method="post" enctype="multipart/form-data">
                    <h1>Add New Member</h1>
                    <?php if(isset($success_message)): ?>
                        <div class="success-message"><?php echo $success_message; ?></div>
                    <?php endif; ?>
                    
                    <?php if(isset($error_message)): ?>
                        <div class="error-message"><?php echo $error_message; ?></div>
                    <?php endif; ?>
                    
                    <div class="form-section">
                        <h3>Basic Information</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="first_name">First Name</label>
                                <input type="text" id="first_name" name="first_name" required>
                            </div>
                            <div class="form-group">
                                <label for="last_name">Last Name</label>
                                <input type="text" id="last_name" name="last_name" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="text" id="phone" name="phone" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" id="address" name="address" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="city">City</label>
                                <input type="text" id="city" name="city">
                            </div>
                            <div class="form-group">
                                <label for="state">State</label>
                                <input type="text" id="state" name="state">
                            </div>
                            <div class="form-group">
                                <label for="zip">Zip Code</label>
                                <input type="text" id="zip" name="zip">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="date_of_birth">Date of Birth</label>
                                <input type="date" id="date_of_birth" name="date_of_birth" required>
                            </div>
                            <div class="form-group">
                                <label for="gender">Gender</label>
                                <select id="gender" name="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="membership_status">Membership Status</label>
                            <select id="membership_status" name="membership_status" required>
                                <option value="">Select Status</option>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                                <option value="Visitor">Visitor</option>
                                <option value="New Member">New Member</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-section">
                        <h3>Additional Information</h3>
                        <div class="form-group">
                            <label for="join_date">Join Date</label>
                            <input type="date" id="join_date" name="join_date">
                        </div>
                        <div class="form-group">
                            <label for="ministry">Ministry</label>
                            <input type="text" id="ministry" name="ministry">
                        </div>
                        <div class="form-group">
                            <label for="notes">Notes</label>
                            <textarea id="notes" name="notes" rows="5"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="profile_photo">Profile Photo</label>
                            <input type="file" id="profile_photo" name="profile_photo">
                            <label for="profile_photo" class="file-input-label"><i class="fas fa-upload"></i> Choose Photo</label>
                            <small>Upload a profile photo (optional)</small>
                        </div>
                        <div class="form-group image-preview-container">
                            <div id="profile-photo-preview">
                                <i class="fas fa-user"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" name="add_member" class="btn btn-success"><i class="fas fa-user-plus"></i> Add Member</button>
                        <a href="members.php" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="script.js"></script>
</body>
</html>
