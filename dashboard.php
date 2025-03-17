<?php
session_start();
require_once 'config/db.php';

// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get total members
$members_query = "SELECT COUNT(*) as total FROM members";
$members_result = mysqli_query($conn, $members_query);
$members_data = mysqli_fetch_assoc($members_result);
$total_members = $members_data['total'];

// Get total events
$events_query = "SELECT COUNT(*) as total FROM events";
$events_result = mysqli_query($conn, $events_query);
$events_data = mysqli_fetch_assoc($events_result);
$total_events = $events_data ? $events_data['total'] : 0;

// Get total donations
$donations_query = "SELECT SUM(amount) as total FROM donations";
$donations_result = mysqli_query($conn, $donations_query);
$donations_data = mysqli_fetch_assoc($donations_result);
$total_donations = $donations_data['total'] ? $donations_data['total'] : 0;

// Get recent members (last 5)
$recent_members_query = "SELECT id, CONCAT(first_name, ' ', last_name) as name, join_date, membership_status 
                        FROM members 
                        ORDER BY join_date DESC 
                        LIMIT 5";
$recent_members_result = mysqli_query($conn, $recent_members_query);

// Get upcoming events (next 5)
$upcoming_events_query = "SELECT id, event_name, event_date, location 
                          FROM events 
                          WHERE event_date >= CURDATE() 
                          ORDER BY event_date 
                          LIMIT 5";
$upcoming_events_result = mysqli_query($conn, $upcoming_events_query);

// Get recent donations (last 5)
$recent_donations_query = "SELECT d.id, d.amount, d.donation_date, CONCAT(m.first_name, ' ', m.last_name) as donor_name 
                          FROM donations d 
                          LEFT JOIN members m ON d.member_id = m.id 
                          ORDER BY d.donation_date DESC 
                          LIMIT 5";
$recent_donations_result = mysqli_query($conn, $recent_donations_query);

// Get membership status counts
$status_query = "SELECT 
                CASE 
                    WHEN LOWER(membership_status) IN ('visitor', 'visitor-status', 'visitor status') THEN 'Visitor'
                    WHEN LOWER(membership_status) IN ('new member', 'new-member', 'new_member', 'newmember') THEN 'New Member'
                    WHEN LOWER(membership_status) = 'active' THEN 'Active'
                    WHEN LOWER(membership_status) = 'inactive' THEN 'Inactive'
                    ELSE membership_status
                    END as status,
                    COUNT(*) as count
                    FROM members
                    GROUP BY status
                    ORDER BY 
                    CASE 
                    WHEN status = 'Active' THEN 1
                    WHEN status = 'Inactive' THEN 2
                    WHEN status = 'New Member' THEN 3
                    WHEN status = 'Visitor' THEN 4
                    ELSE 5
                END";
$status_result = mysqli_query($conn, $status_query);

// Prepare data for membership chart
$status_labels = [];
$status_data = [];
$status_colors = [];

while ($row = mysqli_fetch_assoc($status_result)) {
    $status_labels[] = $row['status'];
    $status_data[] = $row['count'];
    
    // Set colors based on status
    if (strtolower($row['status']) == 'active') {
        $status_colors[] = '#2ecc71'; // Green
    } elseif (strtolower($row['status']) == 'inactive') {
        $status_colors[] = '#e74c3c'; // Red
    } elseif (strtolower($row['status']) == 'visitor' || strpos(strtolower($row['status']), 'visitor') !== false) {
        $status_colors[] = '#3498db'; // Blue
    } elseif (strtolower($row['status']) == 'new member' || strpos(strtolower($row['status']), 'new') !== false) {
        $status_colors[] = '#9b59b6'; // Purple
    } else {
        $status_colors[] = '#95a5a6'; // Gray
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Church Management System</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <div class="logo">
                <img src="img/jia.png" alt="JIA Somal-ot Logo" style="max-width: 50%;">
                <h2>JIA Somal-t</h2>
            </div>
            <ul class="nav-links">
                <li><a href="dashboard.php" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="members.php"><i class="fas fa-users"></i> Members</a></li>
                <li><a href="events.php"><i class="fas fa-calendar"></i> Events</a></li>
                <li><a href="donations.php"><i class="fas fa-hand-holding-heart"></i> Donations</a></li>
                <li><a href="reports.php"><i class="fas fa-chart-bar"></i> Reports</a></li>
                <li><a href="settings.php"><i class="fas fa-cog"></i> Settings</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
        <div class="main-content">
            <div class="content">
                <div class="page-header">
                </div>
                <div class="stats-container">
                    <div class="stat-card">
                        <a href="members.php">
                            <div class="stat-icon member-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="stat-details">
                                <h3>Members</h3>
                                <p class="stat-number"><?php echo $total_members; ?></p>
                                <p class="stat-info">Total members</p>
                            </div>
                        </a>
                    </div>
                    <div class="stat-card">
                        <a href="events.php">
                            <div class="stat-icon event-icon">
                                <i class="fas fa-calendar"></i>
                            </div>
                            <div class="stat-details">
                                <h3>Events</h3>
                                <p class="stat-number"><?php echo $total_events; ?></p>
                                <p class="stat-info">Total events</p>
                            </div>
                        </a>
                    </div>
                    <div class="stat-card">
                        <a href="donations.php">
                            <div class="stat-icon donation-icon">
                                <i class="fas fa-hand-holding-heart"></i>
                            </div>
                            <div class="stat-details">
                                <h3>Donations</h3>
                                <p class="stat-number">$<?php echo number_format($total_donations, 2); ?></p>
                                <p class="stat-info">Total donations</p>
                            </div>
                        </a>
                    </div>
                </div>
                
                <!-- Dashboard Grid -->
                <div class="dashboard-grid">
                    <!-- Membership Chart -->
                    <div class="dashboard-card membership-chart">
                        <div class="card-header">
                            <h2><i class="fas fa-chart-pie"></i> Membership Status</h2>
                            <a href="reports.php?type=members" class="view-all">View All</a>
                        </div>
                        <div class="card-content">
                            <div class="chart-container">
                                <canvas id="membershipChart"></canvas>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Recent Members -->
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h2><i class="fas fa-user-plus"></i> Recent Members</h2>
                            <a href="members.php" class="view-all">View All</a>
                        </div>
                        <div class="card-content">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Join Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($member = mysqli_fetch_assoc($recent_members_result)): ?>
                                    <tr>
                                        <td><?php echo $member['name']; ?></td>
                                        <td><?php echo date('M d, Y', strtotime($member['join_date'])); ?></td>
                                        <td>
                                            <span class="status-badge <?php 
                                                $status = strtolower($member['membership_status']);
                                                if ($status == 'active') echo 'active';
                                                else if ($status == 'inactive') echo 'inactive';
                                                else if (strpos($status, 'visitor') !== false) echo 'visitor';
                                                else if (strpos($status, 'new') !== false && (strpos($status, 'member') !== false)) echo 'new-member';
                                                else echo str_replace(' ', '-', $status);
                                            ?>"><?php echo $member['membership_status']; ?></span>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                    <?php if (mysqli_num_rows($recent_members_result) == 0): ?>
                                    <tr>
                                        <td colspan="3" class="no-data">No recent members</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Upcoming Events -->
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h2><i class="fas fa-calendar-alt"></i> Upcoming Events</h2>
                            <a href="events.php" class="view-all">View All</a>
                        </div>
                        <div class="card-content">
                            <ul class="event-list">
                                <?php while ($event = mysqli_fetch_assoc($upcoming_events_result)): ?>
                                <li class="event-item">
                                    <div class="event-date">
                                        <span class="day"><?php echo date('d', strtotime($event['event_date'])); ?></span>
                                        <span class="month"><?php echo date('M', strtotime($event['event_date'])); ?></span>
                                    </div>
                                    <div class="event-details">
                                        <h3><?php echo $event['event_name']; ?></h3>
                                        <div class="event-time"><i class="far fa-clock"></i> <?php echo date('h:i A', strtotime($event['event_date'])); ?></div>
                                        <div class="event-location"><i class="fas fa-map-marker-alt"></i> <?php echo $event['location']; ?></div>
                                    </div>
                                </li>
                                <?php endwhile; ?>
                                <?php if (mysqli_num_rows($upcoming_events_result) == 0): ?>
                                <li class="event-item">
                                    <div class="empty-state">
                                        <i class="far fa-calendar-times"></i>
                                        <p>No upcoming events</p>
                                    </div>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Recent Donations -->
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h2><i class="fas fa-donate"></i> Recent Donations</h2>
                            <a href="donations.php" class="view-all">View All</a>
                        </div>
                        <div class="card-content">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Donor</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($donation = mysqli_fetch_assoc($recent_donations_result)): ?>
                                    <tr>
                                        <td><?php echo $donation['donor_name']; ?></td>
                                        <td>$<?php echo number_format($donation['amount'], 2); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($donation['donation_date'])); ?></td>
                                    </tr>
                                    <?php endwhile; ?>
                                    <?php if (mysqli_num_rows($recent_donations_result) == 0): ?>
                                    <tr>
                                        <td colspan="3" class="no-data">No recent donations</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var ctx = document.getElementById('membershipChart').getContext('2d');
            var membershipChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: <?php echo json_encode($status_labels); ?>,
                    datasets: [{
                        data: <?php echo json_encode($status_data); ?>,
                        backgroundColor: <?php echo json_encode($status_colors); ?>
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    var label = context.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    label += context.raw;
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>

