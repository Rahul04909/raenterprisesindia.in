<?php
// Determine path prefix based on current directory
$current_dir = basename(dirname($_SERVER['PHP_SELF']));
$pp = ($current_dir == 'brands') ? '../' : '';

// Function to check active state (simple helper)
function isActive($page) {
    // Get current file name
    $current_file = basename($_SERVER['PHP_SELF']);
    // Check for brands section specifically
    if ($page == 'brands/index.php' && $current_file == 'index.php' && basename(dirname($_SERVER['PHP_SELF'])) == 'brands') {
        return 'active';
    }
    return ($current_file == $page) ? 'active' : '';
}
?>

<!-- Include Sidebar CSS -->
<link rel="stylesheet" href="<?php echo $pp; ?>assets/css/admin-sidebar.css">
<!-- Font Awesome (if not already included in header) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<nav id="admin-sidebar" style="height: 100vh; overflow-y: auto;">
    <div class="sidebar-header">
        <div class="sidebar-brand">
            <i class="fas fa-shield-alt"></i> RA Admin
        </div>
    </div>

    <ul class="sidebar-menu">
        <!-- Dashboard -->
        <li>
            <a href="<?php echo $pp; ?>index.php" class="<?php echo isActive('index.php'); ?>">
                <div class="sidebar-icon"><i class="fas fa-tachometer-alt"></i></div>
                <div class="menu-text">Dashboard</div>
            </a>
        </li>

        <!-- Brands (New) -->
        <li>
            <a href="<?php echo $pp; ?>brands/index.php" class="<?php echo isActive('brands/index.php'); ?>">
                <div class="sidebar-icon"><i class="fas fa-tags"></i></div>
                <div class="menu-text">Brands</div>
            </a>
        </li>

        <!-- Product Categories (New) -->
        <li>
            <a href="<?php echo $pp; ?>product-categories/index.php" class="<?php echo isActive('product-categories/index.php'); ?>">
                <div class="sidebar-icon"><i class="fas fa-list-ul"></i></div>
                <div class="menu-text">Product Categories</div>
            </a>
        </li>

        <!-- Products (New) -->
        <li>
            <a href="<?php echo $pp; ?>products/index.php" class="<?php echo isActive('products/index.php'); ?>">
                <div class="sidebar-icon"><i class="fas fa-box-open"></i></div>
                <div class="menu-text">Products</div>
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
                <li><a href="<?php echo $pp; ?>all-enquiries.php">All Enquiries</a></li>
                <li><a href="<?php echo $pp; ?>add-enquiry.php">Add New</a></li>
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
                <li><a href="<?php echo $pp; ?>all-students.php">All Students</a></li>
                <li><a href="<?php echo $pp; ?>add-student.php">Add Student</a></li>
                <li><a href="<?php echo $pp; ?>student-attendance.php">Attendance</a></li>
            </ul>
        </li>

        <!-- Staff/Team -->
        <li>
            <a href="<?php echo $pp; ?>staff.php" class="<?php echo isActive('staff.php'); ?>">
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
                <li><a href="<?php echo $pp; ?>exam-schedule.php">Exam Schedule</a></li>
                <li><a href="<?php echo $pp; ?>exam-results.php">Results</a></li>
            </ul>
        </li>

         <!-- Media/Gallery -->
         <li>
            <a href="<?php echo $pp; ?>media.php">
                <div class="sidebar-icon"><i class="fas fa-images"></i></div>
                <div class="menu-text">Media & Banners</div>
            </a>
        </li>

        <!-- Settings -->
        <li>
            <a href="<?php echo $pp; ?>settings.php">
                <div class="sidebar-icon"><i class="fas fa-cog"></i></div>
                <div class="menu-text">Settings</div>
            </a>
        </li>

        <!-- Logout -->
        <li>
            <a href="<?php echo $pp; ?>logout.php">
                <div class="sidebar-icon"><i class="fas fa-sign-out-alt"></i></div>
                <div class="menu-text">Logout</div>
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
