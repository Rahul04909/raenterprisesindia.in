<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}
include '../../dbms/dbms_config.php';

// Handle Delete Request
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    // Optional: Delete image file logic here if needed
    $del_sql = "DELETE FROM product_categories WHERE id = $id";
    if ($conn->query($del_sql) === TRUE) {
        $msg = "Category deleted successfully.";
    } else {
        $error = "Error deleting category: " . $conn->error;
    }
}

// Fetch Categories with Brand Name
$sql = "SELECT product_categories.*, brands.name as brand_name 
        FROM product_categories 
        LEFT JOIN brands ON product_categories.brand_id = brands.id 
        ORDER BY product_categories.id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Categories &lsaquo; RA Admin</title>
    <!-- Include Admin Styles -->
    <link rel="stylesheet" href="../assets/css/admin-sidebar.css">
    <link rel="stylesheet" href="../assets/css/admin-dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .wp-heading-inline {
            display: inline-block;
            margin-right: 15px;
            font-size: 23px;
            font-weight: 400;
            color: #23282d;
        }
        .page-title-action {
            display: inline-block;
            text-decoration: none;
            font-size: 13px;
            line-height: 26px;
            height: 28px;
            margin: 0;
            padding: 0 10px 1px;
            cursor: pointer;
            border-width: 1px;
            border-style: solid;
            -webkit-appearance: none;
            border-radius: 3px;
            white-space: nowrap;
            box-sizing: border-box;
            background: #f7f7f7;
            border-color: #0073aa;
            color: #0073aa;
            vertical-align: top;
        }
        .page-title-action:hover {
            background: #f0f0f1;
            border-color: #008ec2;
            color: #00a0d2;
        }
        
        .wp-list-table {
            width: 100%;
            border-spacing: 0;
            clear: both;
            margin: 0;
            border: 1px solid #c3c4c7;
            box-shadow: 0 1px 1px rgba(0,0,0,0.04);
            background: #fff;
        }
        .wp-list-table thead th {
            text-align: left;
            padding: 8px 10px;
            font-weight: 400;
            color: #2c3338;
            border-bottom: 1px solid #c3c4c7;
        }
        .wp-list-table tbody td {
            padding: 8px 10px;
            background: #fff;
            border-bottom: 1px solid #f6f7f7;
            color: #50575e;
            font-size: 13px;
            vertical-align: top;
        }
        .wp-list-table tr:hover td {
            background: #fcfcfc;
        }
        .row-actions {
            color: #ddd;
            font-size: 13px;
            padding: 2px 0 0;
            visibility: hidden;
        }
        .wp-list-table tr:hover .row-actions {
            visibility: visible;
        }
        .row-actions a { text-decoration: none; }
        .edit a { color: #0073aa; }
        .delete a { color: #b32d2e; }
        
        .cat-img-thumb {
            width: 50px;
            height: 50px;
            object-fit: contain;
            border: 1px solid #ddd;
            padding: 2px;
            background: #f9f9f9;
        }
        .notice {
            background: #fff;
            border-left: 4px solid #00a32a;
            box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
            margin: 5px 0 15px;
            padding: 1px 12px;
        }
        .notice-error { border-left-color: #d63638; }
    </style>
</head>
<body>

<?php include '../sidebar.php'; ?>

<div id="admin-content">
    <div class="wrap" style="max-width: 1200px; margin: 20px;">
        <h1 class="wp-heading-inline">Product Categories</h1>
        <a href="add-category.php" class="page-title-action">Add New</a>
        <hr style="margin: 20px 0; border: 0; border-top: 1px solid #ddd;">

        <?php if(isset($msg)): ?>
            <div class="notice"><p><?php echo $msg; ?></p></div>
        <?php endif; ?>
        <?php if(isset($error)): ?>
            <div class="notice notice-error"><p><?php echo $error; ?></p></div>
        <?php endif; ?>

        <table class="wp-list-table">
            <thead>
                <tr>
                    <th width="80">Image</th>
                    <th>Name</th>
                    <th>Brand</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <?php if(!empty($row['image'])): ?>
                                    <img src="../../<?php echo htmlspecialchars($row['image']); ?>" class="cat-img-thumb" alt="Img">
                                <?php else: ?>
                                    <span style="color:#ccc; font-size:11px;">No Img</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><a href="edit-category.php?id=<?php echo $row['id']; ?>" style="color:#2c3338; text-decoration:none; font-weight:600; font-size:14px;"><?php echo htmlspecialchars($row['name']); ?></a></strong>
                                <div class="row-actions">
                                    <span class="edit"><a href="edit-category.php?id=<?php echo $row['id']; ?>">Edit</a> | </span>
                                    <span class="delete"><a href="index.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this category?');">Delete</a></span>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($row['brand_name']); ?></td>
                            <td><?php echo substr(strip_tags($row['description']), 0, 100) . '...'; ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No categories found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
