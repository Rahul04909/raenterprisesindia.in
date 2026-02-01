<?php
// Function to check active state (simple helper)
function isActive($page) {
    // Get current file name
    $current_file = basename($_SERVER['PHP_SELF']);
    return ($current_file == $page) ? 'active' : '';
}
?>

<!-- Include Sidebar CSS -->
<link rel="stylesheet" href="assets/css/admin-sidebar.css">
<!-- Font Awesome (if not already included in header) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<nav id="admin-sidebar">
    <div class="sidebar-header">
        <div class="sidebar-brand">
            <i class="fas fa-shield-alt"></i> RA Admin
        </div>
    </div>

    <ul class="sidebar-menu">
        <!-- Dashboard -->
        <li>
            <a href="dashboard.php" class="<?php echo isActive('dashboard.php'); ?>">
                <div class="sidebar-icon"><i class="fas fa-tachometer-alt"></i></div>
                <div class="menu-text">Dashboard</div>
            </a>
        </li>

        <!-- Enquiries (With Submenu) -->
        <li class="has-submenu">
            <a href="#">
                <div class="sidebar-icon"><i class="fas fa-envelope-open-text"></i></div>
                <div class="menu-text">Enquiries</div>
                <div class="dropdown-icon"><i class="fas fa-chevron-down"></i></div>
            </a>
            <ul class="submenu">
                <li><a href="all-enquiries.php">All Enquiries</a></li>
                <li><a href="add-enquiry.php">Add New</a></li>
            </ul>
        </li>

        <!-- Students -->
        <li class="has-submenu">
            <a href="#">
                <div class="sidebar-icon"><i class="fas fa-user-graduate"></i></div>
                <div class="menu-text">Students</div>
                <div class="dropdown-icon"><i class="fas fa-chevron-down"></i></div>
            </a>
            <ul class="submenu">
                <li><a href="all-students.php">All Students</a></li>
                <li><a href="add-student.php">Add Student</a></li>
                <li><a href="student-attendance.php">Attendance</a></li>
            </ul>
        </li>

        <!-- Staff/Team -->
        <li>
            <a href="staff.php" class="<?php echo isActive('staff.php'); ?>">
                <div class="sidebar-icon"><i class="fas fa-users"></i></div>
                <div class="menu-text">Our Team</div>
            </a>
        </li>

        <!-- Exams -->
        <li class="has-submenu">
            <a href="#">
                <div class="sidebar-icon"><i class="fas fa-file-alt"></i></div>
                <div class="menu-text">Examinations</div>
                <div class="dropdown-icon"><i class="fas fa-chevron-down"></i></div>
            </a>
            <ul class="submenu">
                <li><a href="exam-schedule.php">Exam Schedule</a></li>
                <li><a href="exam-results.php">Results</a></li>
            </ul>
        </li>

         <!-- Media/Gallery -->
         <li>
            <a href="media.php">
                <div class="sidebar-icon"><i class="fas fa-images"></i></div>
                <div class="menu-text">Media & Banners</div>
            </a>
        </li>

        <!-- Settings -->
        <li>
            <a href="settings.php">
                <div class="sidebar-icon"><i class="fas fa-cog"></i></div>
                <div class="menu-text">Settings</div>
            </a>
        </li>
    </ul>

    <!-- Collapse Button -->
    <div class="sidebar-footer">
        <button id="collapse-btn" class="collapse-btn">
            <i class="collapse-icon fas fa-chevron-circle-left"></i>
            <span class="menu-text" style="margin-left: 10px;">Collapse</span>
        </button>
    </div>
</nav>

<!-- Include Sidebar JS -->
<script src="assets/js/admin-sidebar.js"></script>
