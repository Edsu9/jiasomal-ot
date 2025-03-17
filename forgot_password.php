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

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // Check if email exists in the database
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        // Generate a unique token
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        // Store the token in the database
        $user_id = $user['id'];
        $query = "INSERT INTO password_resets (user_id, token, expires_at, created_at) 
                  VALUES ($user_id, '$token', '$expires', NOW())";
        
        if (mysqli_query($conn, $query)) {
            // In a real application, you would send an email with the reset link
            // For this local application, we'll just display the reset link
            $reset_link = "reset_password.php?token=$token";
            $success_message = "Password reset link has been generated. Since this is a local application, here's your reset link: <a href='$reset_link'>Reset Password</a>";
        } else {
            $error_message = "Error generating reset token: " . mysqli_error($conn);
        }
    } else {
        $error_message = "No account found with that email address.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Church Management System</title>
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
            background-image: url('img/cmnv.jpg');
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
            background-color: rgb(255, 255, 255);
            z-index: 1;
        }
        
        .forgot-password-container {
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
        
        .forgot-password-header {
            background-color: rgb(33, 117, 44);
            color: #fff;
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }
        
        .forgot-password-header img {
            max-width: 80px;
            margin-bottom: 10px;
            border-radius: 50%;
            padding: 5px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .forgot-password-header h1 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
        }
        
        .forgot-password-header p {
            margin: 5px 0 0;
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        .forgot-password-form {
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
        
        .reset-btn {
            display: block;
            width: 100%;
            padding: 12px;
            background-color: rgb(33, 117, 44);
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }
        
        .reset-btn:hover {
            background-color: rgb(33, 117, 44);
            transform: translateY(-2px);
        }
        
        .reset-btn:active {
            transform: translateY(0);
        }
        
        .forgot-password-footer {
            text-align: center;
            padding: 0 30px 20px;
            color: #7f8c8d;
            font-size: 0.9rem;
        }
        
        .forgot-password-footer a {
            color: #3498db;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .forgot-password-footer a:hover {
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
        
        @media (max-width: 480px) {
            .forgot-password-container {
                width: 100%;
                max-width: 100%;
                border-radius: 0;
                height: 100vh;
            }
            
            .forgot-password-form {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="forgot-password-container">
        <div class="forgot-password-header">
            <img src="<?php echo $church_logo; ?>" alt="<?php echo $church_name; ?> Logo">
            <h1>Forgot Password</h1>
            <p>Jesus Is Alive Community</p>
        </div>
        
        <div class="forgot-password-form">
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
            
            <p>Enter your email address below to reset your password.</p>
            
            <form action="forgot_password.php" method="post">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-with-icon">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email" placeholder="Enter your email address" required>
                    </div>
                </div>
                
                <button type="submit" class="reset-btn">
                    <i class="fas fa-paper-plane"></i> Send Reset Link
                </button>
            </form>
            
            <div class="forgot-password-footer">
                <p>Remember your password? <a href="login.php">Back to Login</a></p>
            </div>
        </div>
    </div>
</body>
</html>

