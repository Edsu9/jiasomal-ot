<?php
session_start();
require_once 'config/db.php';

// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user data
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// Get church settings
$query = "SELECT * FROM settings WHERE id = 1";
$result = mysqli_query($conn, $query);
$settings = mysqli_fetch_assoc($result);

// Get success or error messages from session
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';

// Clear messages after displaying them
unset($_SESSION['success_message']);
unset($_SESSION['error_message']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Church Management System</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/enhanced-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <div class="logo">
                <img src="img/jia.png" alt="JIA Somal-ot Logo" style="max-width: 50%;">
                <h2>JIA Somal-ot</h2>
            </div>
            <ul class="nav-links">
                <li><a href="index.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="members.php"><i class="fas fa-users"></i> Members</a></li>
                <li><a href="events.php"><i class="fas fa-calendar"></i> Events</a></li>
                <li><a href="donations.php"><i class="fas fa-hand-holding-heart"></i> Donations</a></li>
                <li><a href="reports.php"><i class="fas fa-chart-bar"></i> Reports</a></li>
                <li><a href="settings.php" class="active"><i class="fas fa-cog"></i> Settings</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
        <div class="main-content">
            <div class="content">
                <div class="page-header">
                    <h1><i class="fas fa-cog"></i> Settings</h1>
                </div>
                
                <?php if($success_message): ?>
                    <div class="success-message"><?php echo $success_message; ?></div>
                <?php endif; ?>
                
                <?php if($error_message): ?>
                    <div class="error-message"><?php echo $error_message; ?></div>
                <?php endif; ?>
                
                <div class="content-container">
                    <div class="settings-tabs">
                        <div class="tab-buttons">
                            <button class="tab-btn active" data-tab="account"><i class="fas fa-user-circle"></i> Account Settings</button>
                            <button class="tab-btn" data-tab="church"><i class="fas fa-church"></i> Church Information</button>
                            <button class="tab-btn" data-tab="system"><i class="fas fa-sliders-h"></i> System Settings</button>
                            <button class="tab-btn" data-tab="backup"><i class="fas fa-database"></i> Backup & Restore</button>
                        </div>
                        
                        <div class="tab-content">
                            <!-- Account Settings Tab -->
                            <div class="tab-pane active" id="account">
                                <div class="report-table-container">
                                    <div class="table-header">
                                        <h3><i class="fas fa-user-circle"></i> Account Settings</h3>
                                    </div>
                                    
                                    <div class="report-table-content">
                                        <form action="settings_process.php" method="post" class="plain-form">
                                            <input type="hidden" name="action" value="update_account">
                                            
                                            <table class="report-table">
                                                <tbody>
                                                    <tr>
                                                        <th colspan="2" class="section-header">
                                                            <i class="fas fa-user"></i> User Information
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <td class="detail-label">Username</td>
                                                        <td><input type="text" name="username" value="<?php echo $user['username']; ?>" required></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="detail-label">Email</td>
                                                        <td><input type="email" name="email" value="<?php echo $user['email']; ?>" required></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="detail-label">Full Name</td>
                                                        <td><input type="text" name="full_name" value="<?php echo $user['full_name']; ?>" required></td>
                                                    </tr>
                                                    
                                                    <tr>
                                                        <th colspan="2" class="section-header">
                                                            <i class="fas fa-lock"></i> Change Password
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <td class="detail-label">Current Password</td>
                                                        <td><input type="password" name="current_password"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="detail-label">New Password</td>
                                                        <td><input type="password" name="new_password"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="detail-label">Confirm New Password</td>
                                                        <td><input type="password" name="confirm_password"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            
                                            <div class="form-actions">
                                                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Church Information Tab -->
                            <div class="tab-pane" id="church">
                                <div class="report-table-container">
                                    <div class="table-header">
                                        <h3><i class="fas fa-church"></i> Church Information</h3>
                                    </div>
                                    
                                    <div class="report-table-content">
                                        <form action="settings_process.php" method="post" enctype="multipart/form-data" class="plain-form">
                                            <input type="hidden" name="action" value="update_church">
                                            
                                            <table class="report-table">
                                                <tbody>
                                                    <tr>
                                                        <th colspan="2" class="section-header">
                                                            <i class="fas fa-info-circle"></i> Basic Information
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <td class="detail-label">Church Name</td>
                                                        <td><input type="text" name="church_name" value="<?php echo isset($settings['church_name']) ? $settings['church_name'] : 'JIA Somal-ot Church'; ?>" required></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="detail-label">Tagline/Motto</td>
                                                        <td><input type="text" name="tagline" value="<?php echo isset($settings['tagline']) ? $settings['tagline'] : ''; ?>"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="detail-label">Established Year</td>
                                                        <td><input type="number" name="established_year" value="<?php echo isset($settings['established_year']) ? $settings['established_year'] : ''; ?>"></td>
                                                    </tr>
                                                    
                                                    <tr>
                                                        <th colspan="2" class="section-header">
                                                            <i class="fas fa-map-marker-alt"></i> Contact Information
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <td class="detail-label">Address</td>
                                                        <td><textarea name="address" rows="2"><?php echo isset($settings['address']) ? $settings['address'] : ''; ?></textarea></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="detail-label">City</td>
                                                        <td><input type="text" name="city" value="<?php echo isset($settings['city']) ? $settings['city'] : ''; ?>"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="detail-label">State/Province</td>
                                                        <td><input type="text" name="state" value="<?php echo isset($settings['state']) ? $settings['state'] : ''; ?>"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="detail-label">ZIP/Postal Code</td>
                                                        <td><input type="text" name="zip" value="<?php echo isset($settings['zip']) ? $settings['zip'] : ''; ?>"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="detail-label">Country</td>
                                                        <td><input type="text" name="country" value="<?php echo isset($settings['country']) ? $settings['country'] : ''; ?>"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="detail-label">Phone</td>
                                                        <td><input type="text" name="phone" value="<?php echo isset($settings['phone']) ? $settings['phone'] : ''; ?>"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="detail-label">Email</td>
                                                        <td><input type="email" name="email" value="<?php echo isset($settings['email']) ? $settings['email'] : ''; ?>"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="detail-label">Website</td>
                                                        <td><input type="url" name="website" value="<?php echo isset($settings['website']) ? $settings['website'] : ''; ?>"></td>
                                                    </tr>
                                                    
                                                    <tr>
                                                        <th colspan="2" class="section-header">
                                                            <i class="fas fa-image"></i> Church Logo
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <td class="detail-label">Church Logo</td>
                                                        <td>
                                                            <input type="file" name="church_logo" accept="image/*">
                                                            <div class="image-preview-container">
                                                                <?php if(isset($settings['logo']) && $settings['logo']): ?>
                                                                    <img src="uploads/logo/<?php echo $settings['logo']; ?>" alt="Church Logo">
                                                                <?php else: ?>
                                                                    <img src="img/jia.png" alt="Default Logo">
                                                                <?php endif; ?>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    
                                                    <tr>
                                                        <th colspan="2" class="section-header">
                                                            <i class="fas fa-users"></i> Leadership Information
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <td class="detail-label">Pastor/Leader Name</td>
                                                        <td><input type="text" name="pastor_name" value="<?php echo isset($settings['pastor_name']) ? $settings['pastor_name'] : ''; ?>"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="detail-label">Pastor/Leader Title</td>
                                                        <td><input type="text" name="pastor_title" value="<?php echo isset($settings['pastor_title']) ? $settings['pastor_title'] : ''; ?>"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            
                                            <div class="form-actions">
                                                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- System Settings Tab -->
                            <div class="tab-pane" id="system">
                                <div class="report-table-container">
                                    <div class="table-header">
                                        <h3><i class="fas fa-sliders-h"></i> System Settings</h3>
                                    </div>
                                    
                                    <div class="report-table-content">
                                        <form action="settings_process.php" method="post" class="plain-form">
                                            <input type="hidden" name="action" value="update_system">
                                            
                                            <table class="report-table">
                                                <tbody>
                                                    <tr>
                                                        <th colspan="2" class="section-header">
                                                            <i class="fas fa-palette"></i> Appearance
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <td class="detail-label">Theme Color</td>
                                                        <td>
                                                            <select name="theme_color">
                                                                <option value="blue" <?php echo (isset($settings['theme_color']) && $settings['theme_color'] == 'blue') ? 'selected' : ''; ?>>Blue</option>
                                                                <option value="green" <?php echo (isset($settings['theme_color']) && $settings['theme_color'] == 'green') ? 'selected' : ''; ?>>Green</option>
                                                                <option value="purple" <?php echo (isset($settings['theme_color']) && $settings['theme_color'] == 'purple') ? 'selected' : ''; ?>>Purple</option>
                                                                <option value="red" <?php echo (isset($settings['theme_color']) && $settings['theme_color'] == 'red') ? 'selected' : ''; ?>>Red</option>
                                                                <option value="orange" <?php echo (isset($settings['theme_color']) && $settings['theme_color'] == 'orange') ? 'selected' : ''; ?>>Orange</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="detail-label">Items Per Page</td>
                                                        <td>
                                                            <select name="items_per_page">
                                                                <option value="10" <?php echo (isset($settings['items_per_page']) && $settings['items_per_page'] == 10) ? 'selected' : ''; ?>>10</option>
                                                                <option value="25" <?php echo (isset($settings['items_per_page']) && $settings['items_per_page'] == 25) ? 'selected' : ''; ?>>25</option>
                                                                <option value="50" <?php echo (isset($settings['items_per_page']) && $settings['items_per_page'] == 50) ? 'selected' : ''; ?>>50</option>
                                                                <option value="100" <?php echo (isset($settings['items_per_page']) && $settings['items_per_page'] == 100) ? 'selected' : ''; ?>>100</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    
                                                    <tr>
                                                        <th colspan="2" class="section-header">
                                                            <i class="fas fa-globe"></i> Regional Settings
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <td class="detail-label">Date Format</td>
                                                        <td>
                                                            <select name="date_format">
                                                                <option value="m/d/Y" <?php echo (isset($settings['date_format']) && $settings['date_format'] == 'm/d/Y') ? 'selected' : ''; ?>>MM/DD/YYYY (e.g., 12/31/2025)</option>
                                                                <option value="d/m/Y" <?php echo (isset($settings['date_format']) && $settings['date_format'] == 'd/m/Y') ? 'selected' : ''; ?>>DD/MM/YYYY (e.g., 31/12/2025)</option>
                                                                <option value="Y-m-d" <?php echo (isset($settings['date_format']) && $settings['date_format'] == 'Y-m-d') ? 'selected' : ''; ?>>YYYY-MM-DD (e.g., 2025-12-31)</option>
                                                                <option value="F j, Y" <?php echo (isset($settings['date_format']) && $settings['date_format'] == 'F j, Y') ? 'selected' : ''; ?>>Month Day, Year (e.g., December 31, 2025)</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="detail-label">Time Format</td>
                                                        <td>
                                                            <select name="time_format">
                                                                <option value="g:i a" <?php echo (isset($settings['time_format']) && $settings['time_format'] == 'g:i a') ? 'selected' : ''; ?>>12-hour (e.g., 2:30 pm)</option>
                                                                <option value="H:i" <?php echo (isset($settings['time_format']) && $settings['time_format'] == 'H:i') ? 'selected' : ''; ?>>24-hour (e.g., 14:30)</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="detail-label">Currency Symbol</td>
                                                        <td>
                                                            <select name="currency_symbol">
                                                                <option value="$" <?php echo (isset($settings['currency_symbol']) && $settings['currency_symbol'] == '$') ? 'selected' : ''; ?>>$ (Dollar)</option>
                                                                <option value="€" <?php echo (isset($settings['currency_symbol']) && $settings['currency_symbol'] == '€') ? 'selected' : ''; ?>>€ (Euro)</option>
                                                                <option value="£" <?php echo (isset($settings['currency_symbol']) && $settings['currency_symbol'] == '£') ? 'selected' : ''; ?>>£ (Pound)</option>
                                                                <option value="¥" <?php echo (isset($settings['currency_symbol']) && $settings['currency_symbol'] == '¥') ? 'selected' : ''; ?>>¥ (Yen)</option>
                                                                <option value="₱" <?php echo (isset($settings['currency_symbol']) && $settings['currency_symbol'] == '₱') ? 'selected' : ''; ?>>₱ (Peso)</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    
                                                    
                                                </tbody>
                                            </table>
                                            
                                            <div class="form-actions">
                                                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Backup & Restore Tab -->
                            <div class="tab-pane" id="backup">
                                <div class="report-table-container">
                                    <div class="table-header">
                                        <h3><i class="fas fa-database"></i> Backup & Restore</h3>
                                    </div>
                                    
                                    <div class="report-table-content">
                                        <div class="backup-section">
                                            <h4><i class="fas fa-download"></i> Create Backup</h4>
                                            <p>Create a backup of your database. This will download a SQL file containing all your data.</p>
                                            <form action="backup_process.php" method="post">
                                                <input type="hidden" name="action" value="create_backup">
                                                <button type="submit" class="btn btn-primary"><i class="fas fa-download"></i> Create Backup</button>
                                            </form>
                                        </div>
                                        
                                        <div class="backup-section">
                                            <h4><i class="fas fa-upload"></i> Restore Backup</h4>
                                            <p>Restore your database from a previous backup. <strong>Warning:</strong> This will overwrite your current data.</p>
                                            <form action="backup_process.php" method="post" enctype="multipart/form-data">
                                                <input type="hidden" name="action" value="restore_backup">
                                                <div class="file-upload">
                                                    <input type="file" name="backup_file" accept=".sql" required>
                                                </div>
                                                <button type="submit" class="btn btn-warning" onclick="return confirm('Are you sure you want to restore this backup? This will overwrite your current data.');"><i class="fas fa-upload"></i> Restore Backup</button>
                                            </form>
                                        </div>
                                        
                                        <div class="backup-section">
                                            <h4><i class="fas fa-history"></i> Backup History</h4>
                                            <table class="report-table">
                                                <thead>
                                                    <tr>
                                                        <th>Filename</th>
                                                        <th>Date</th>
                                                        <th>Size</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $backup_dir = 'backups/';
                                                    if (is_dir($backup_dir)) {
                                                        $files = scandir($backup_dir);
                                                        $backup_files = array_filter($files, function($file) {
                                                            return pathinfo($file, PATHINFO_EXTENSION) === 'sql';
                                                        });
                                                        
                                                        if (count($backup_files) > 0) {
                                                            foreach ($backup_files as $file) {
                                                                $file_path = $backup_dir . $file;
                                                                $file_size = filesize($file_path);
                                                                $file_date = date('F d, Y H:i:s', filemtime($file_path));
                                                                
                                                                echo '<tr>';
                                                                echo '<td>' . $file . '</td>';
                                                                echo '<td>' . $file_date . '</td>';
                                                                echo '<td>' . formatFileSize($file_size) . '</td>';
                                                                echo '<td class="actions">';
                                                                echo '<a href="' . $file_path . '" class="download-btn" download><i class="fas fa-download"></i></a>';
                                                                echo '<a href="backup_process.php?action=delete_backup&file=' . $file . '" class="delete-btn" onclick="return confirm(\'Are you sure you want to delete this backup?\');"><i class="fas fa-trash"></i></a>';
                                                                echo '</td>';
                                                                echo '</tr>';
                                                            }
                                                        } else {
                                                            echo '<tr><td colspan="4" class="text-center">No backup files found.</td></tr>';
                                                        }
                                                    } else {
                                                        echo '<tr><td colspan="4" class="text-center">Backup directory not found.</td></tr>';
                                                    }
                                                    
                                                    function formatFileSize($size) {
                                                        $units = array('B', 'KB', 'MB', 'GB', 'TB');
                                                        $i = 0;
                                                        while ($size >= 1024 && $i < count($units) - 1) {
                                                            $size /= 1024;
                                                            $i++;
                                                        }
                                                        return round($size, 2) . ' ' . $units[$i];
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab functionality
        const tabButtons = document.querySelectorAll('.tab-btn');
        const tabPanes = document.querySelectorAll('.tab-pane');
        
        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons and panes
                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabPanes.forEach(pane => pane.classList.remove('active'));
                
                // Add active class to clicked button and corresponding pane
                button.classList.add('active');
                const tabId = button.getAttribute('data-tab');
                document.getElementById(tabId).classList.add('active');
            });
        });
        
        // Image preview functionality
        const fileInput = document.querySelector('input[name="church_logo"]');
        const imagePreview = document.querySelector('.image-preview-container img');
        
        if (fileInput && imagePreview) {
            fileInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            });
        }
    });
    </script>
    <script src="script.js"></script>
</body>
</html>

