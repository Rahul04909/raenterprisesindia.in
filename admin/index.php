<?php
session_start();
// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard &lsaquo; RA Admin</title>
    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/admin-dashboard.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<?php include 'sidebar.php'; ?>

<!-- Content Wrapper -->
<div id="admin-content">
    <div class="dashboard-header">
        <h1 class="dashboard-title">Dashboard</h1>
    </div>

    <!-- Welcome Panel -->
    <div class="welcome-panel">
        <a href="#" class="welcome-panel-close">Dismiss</a>
        <h2>Welcome to RA ENTERPRISES Admin Panel</h2>
        <p>We’ve assembled some links to get you started:</p>
        <div style="margin-top: 15px;">
            <a href="add-student.php" class="btn btn-primary" style="background:#0073aa; color:#fff; padding: 8px 12px; text-decoration:none; border-radius:3px; font-size:13px; font-weight:600;">Add Student</a>
            <a href="#" style="margin-left: 10px; color:#0073aa; text-decoration:none; font-size:14px;">View Site</a>
        </div>
    </div>

    <!-- Stats Widgets -->
    <div class="dashboard-widgets">
        <!-- Students -->
        <div class="widget-card users">
            <div class="widget-icon">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div class="widget-info">
                <h3>150</h3>
                <p>Active Students</p>
            </div>
        </div>

        <!-- Enquiries -->
        <div class="widget-card enquiries">
            <div class="widget-icon">
                <i class="fas fa-envelope-open-text"></i>
            </div>
            <div class="widget-info">
                <h3>24</h3>
                <p>New Enquiries</p>
            </div>
        </div>

        <!-- Exams -->
        <div class="widget-card courses">
            <div class="widget-icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="widget-info">
                <h3>12</h3>
                <p>Upcoming Exams</p>
            </div>
        </div>

        <!-- Staff -->
        <div class="widget-card">
            <div class="widget-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="widget-info">
                <h3>8</h3>
                <p>Team Members</p>
            </div>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="content-grid">
        <!-- Recent Activity -->
        <div class="panel">
            <div class="panel-header">
                <h3>Recent Activity</h3>
            </div>
            <ul class="activity-list">
                <li>
                    <span>New student registration: <strong>Rahul Kumar</strong></span>
                    <span class="activity-time">2 mins ago</span>
                </li>
                <li>
                    <span>Enquiry from <strong>Amit Singh</strong> regarding Services</span>
                    <span class="activity-time">1 hour ago</span>
                </li>
                <li>
                    <span>Exam results published for <strong>Batch A</strong></span>
                    <span class="activity-time">5 hours ago</span>
                </li>
                <li>
                    <span>Updated <strong>Hero Banner</strong> image</span>
                    <span class="activity-time">Yesterday</span>
                </li>
                <li>
                    <span>New Staff member <strong>Kushal Veer</strong> added</span>
                    <span class="activity-time">Yesterday</span>
                </li>
            </ul>
        </div>

        <!-- Quick Draft / Notes -->
        <div class="panel">
            <div class="panel-header">
                <h3>Quick Note</h3>
            </div>
            <div class="panel-body" style="padding: 15px;">
                <form>
                    <input type="text" placeholder="Title" style="width: 100%; box-sizing: border-box; padding: 6px 10px; margin-bottom: 10px; border:1px solid #ddd;">
                    <textarea placeholder="What's on your mind?" style="width: 100%; box-sizing: border-box; padding: 10px; height: 100px; border:1px solid #ddd; font-family:inherit;"></textarea>
                    <button type="button" style="margin-top: 10px; background: #f0f0f1; border: 1px solid #0073aa; color: #0073aa; padding: 6px 12px; cursor: pointer; border-radius: 3px;">Save Draft</button>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>
