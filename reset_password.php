<?php
session_start();
require_once 'config/db.php';

// Set the default timezone
date_default_timezone_set('Asia/Manila'); // Change to your timezone if needed

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Get church settings
$query = "SELECT * FROM settings WHERE id = 1";
$result = mysqli_query($conn, $query);
$settings = mysqli_fetch_assoc($result);
$church_name = isset($settings['church_name']) ? $settings['church_name'] : 'JIA Somal-ot Church';
$church_logo = isset($settings['logo']) && $settings['logo'] ? 'uploads/logo/' . $settings['logo'] : 'img/jia.png';

$success_message = '';
$error_message = '';
$token = '';
$valid_token = false;
$user_id = 0;

// Check if token is provided
if (isset($_GET['token'])) {
    $token = mysqli_real_escape_string($conn, $_GET['token']);
    
    // Check if token exists and is valid
    $current_time = date('Y-m-d H:i:s');
    $query = "SELECT * FROM password_resets WHERE token = '$token' AND expires_at > '$current_time'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $reset = mysqli_fetch_assoc($result);
        $user_id = $reset['user_id'];
        $valid_token = true;
    } else {
        $error_message = "Invalid or expired token. Please request a new password reset link.";
    }
} else {
    $error_message = "No reset token provided. Please request a password reset from the forgot password page.";
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $valid_token) {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate passwords
    if ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } elseif (strlen($password) < 8) {
        $error_message = "Password must be at least 8 characters long.";
    } else {
        // Hash the new password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        // Update the user's password
        $query = "UPDATE users SET password = '$password_hash', updated_at = NOW() WHERE id = $user_id";
        
        if (mysqli_query($conn, $query)) {
            // Delete the used token
            $query = "DELETE FROM password_resets WHERE token = '$token'";
            mysqli_query($conn, $query);
            
            $success_message = "Your password has been reset successfully. You can now <a href='login.php'>login</a> with your new password.";
            $valid_token = false; // Hide the form
        } else {
            $error_message = "Error updating password: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Church Management System</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/enhanced-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-image: url('img/church-background.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            z-index: 1;
        }
        
        .reset-password-container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            width: 400px;
            max-width: 90%;
            position: relative;
            z-index: 2;
            animation: fadeIn 0.5s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .reset-password-header {
            background-color: #3498db;
            color: #fff;
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }
        
        .reset-password-header img {
            max-width: 80px;
            margin-bottom: 10px;
            border-radius: 50%;
            padding: 5px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .reset-password-header h1 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
        }
        
        .reset-password-header p {
            margin: 5px 0 0;
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        .reset-password-form {
            padding: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #2c3e50;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            transition: border-color 0.3s, box-shadow 0.3s;
            background-color: #f8f9fa;
        }
        
        .form-group input:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
            outline: none;
            background-color: #fff;
        }
        
        .form-group .input-with-icon {
            position: relative;
        }
        
        .form-group .input-with-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #7f8c8d;
        }
        
        .form-group .input-with-icon input {
            padding-left: 40px;
        }
        
        .form-group .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #7f8c8d;
            cursor: pointer;
            transition: color 0.3s;
        }
        
        .form-group .toggle-password:hover {
            color: #3498db;
        }
        
        .reset-btn {
            display: block;
            width: 100%;
            padding: 12px;
            background-color: #3498db;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }
        
        .reset-btn:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }
        
        .reset-btn:active {
            transform: translateY(0);
        }
        
        .reset-password-footer {
            text-align: center;
            padding: 0 30px 20px;
            color: #7f8c8d;
            font-size: 0.9rem;
        }
        
        .reset-password-footer a {
            color: #3498db;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .reset-password-footer a:hover {
            color: #2980b9;
            text-decoration: underline;
        }
        
        .success-message {
            background-color: #e8f5e9;
            color: #388e3c;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
        }
        
        .success-message i {
            margin-right: 10px;
            font-size: 1rem;
        }
        
        .error-message {
            background-color: #fbe9e7;
            color: #e74c3c;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
        }
        
        .error-message i {
            margin-right: 10px;
            font-size: 1rem;
        }
        
        .password-requirements {
            margin-top: 10px;
            font-size: 0.85rem;
            color: #7f8c8d;
        }
        
        .password-requirements ul {
            margin: 5px 0 0 20px;
            padding: 0;
        }
        
        @media (max-width: 480px) {
            .reset-password-container {
                width: 100%;
                max-width: 100%;
                border-radius: 0;
                height: 100vh;
            }
            
            .reset-password-form {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="reset-password-container">
        <div class="reset-password-header">
            <img src="<?php echo $church_logo; ?>" alt="<?php echo $church_name; ?> Logo">
            <h1>Reset Password</h1>
            <p><?php echo $church_name; ?> Management System</p>
        </div>
        
        <div class="reset-password-form">
            <?php if($success_message): ?>
                <div class="success-message">
                    <i class="fas fa-check-circle"></i>
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>
            
            <?php if($error_message): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            
            <?php if($valid_token): ?>
                <p>Please enter your new password below.</p>
                
                <form action="reset_password.php?token=<?php echo $token; ?>" method="post">
                    <div class="form-group">
                        <label for="password">New Password</label>
                        <div class="input-with-icon">
                            <i class="fas fa-lock"></i>
                            <input type="password" id="password" name="password" placeholder="Enter your new password" required>
                            <i class="fas fa-eye toggle-password" id="togglePassword"></i>
                        </div>
                        <div class="password-requirements">
                            Password requirements:
                            <ul>
                                <li>At least 8 characters long</li>
                                <li>Include a mix of letters, numbers, and symbols</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Confirm New Password</label>
                        <div class="input-with-icon">
                            <i class="fas fa-lock"></i>
                            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your new password" required>
                            <i class="fas fa-eye toggle-password" id="toggleConfirmPassword"></i>
                        </div>
                    </div>
                    
                    <button type="submit" class="reset-btn">
                        <i class="fas fa-key"></i> Reset Password
                    </button>
                </form>
            <?php endif; ?>
            
            <div class="reset-password-footer">
                <p><a href="login.php">Back to Login</a></p>
            </div>
        </div>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        
        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });
        }
        
        // Toggle confirm password visibility
        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
        const confirmPasswordInput = document.getElementById('confirm_password');
        
        if (toggleConfirmPassword && confirmPasswordInput) {
            toggleConfirmPassword.addEventListener('click', function() {
                const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                confirmPasswordInput.setAttribute('type', type);
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });
        }
    });
    </script>
</body>
</html>

