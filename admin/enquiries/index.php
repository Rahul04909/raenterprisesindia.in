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
    $del_sql = "DELETE FROM product_quotes WHERE id = $id";
    if ($conn->query($del_sql) === TRUE) {
        $msg = "Enquiry deleted successfully.";
    } else {
        $error = "Error deleting enquiry: " . $conn->error;
    }
}

// Pagination Setup
$limit = 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$start = ($page - 1) * $limit;

// Fetch Enquiries with Product Info
$sql = "SELECT q.*, p.name as product_name 
        FROM product_quotes q 
        LEFT JOIN products p ON q.product_id = p.id 
        ORDER BY q.created_at DESC 
        LIMIT $start, $limit";
$result = $conn->query($sql);

// Get Total Count for Pagination
$count_sql = "SELECT COUNT(id) FROM product_quotes";
$count_res = $conn->query($count_sql);
$total_records = $count_res->fetch_row()[0];
$total_pages = ceil($total_records / $limit);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enquiries &lsaquo; RA Admin</title>
    <!-- Include Admin Styles -->
    <link rel="stylesheet" href="../../assets/css/admin-sidebar.css">
    <link rel="stylesheet" href="../../assets/css/admin-dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .wp-heading-inline {
            display: inline-block;
            margin-right: 15px;
            font-size: 23px;
            font-weight: 400;
            color: #23282d;
        }
        
        /* Table Styles */
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
        .wp-list-table tbody tr:last-child td {
            border-bottom: none;
        }
        .wp-list-table tr:hover td {
            background: #fcfcfc;
        }
        
        /* Pagination */
        .tablenav-pages {
            float: right;
            margin: 10px 0;
        }
        .pagination-links a, .pagination-links span {
            display: inline-block;
            padding: 5px 10px;
            border: 1px solid #ccc;
            background: #fff;
            text-decoration: none;
            color: #0073aa;
            margin-left: 5px;
            border-radius: 3px;
            font-size: 12px;
        }
        .pagination-links .current {
            background: #eee;
            color: #333;
            font-weight: bold;
        }
        
         /* Alert Messages */
        .notice {
            background: #fff;
            border-left: 4px solid #00a32a;
            box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
            margin: 5px 0 15px;
            padding: 1px 12px;
        }
        .notice-error {
            border-left-color: #d63638;
        }
    </style>
</head>
<body>

<?php include '../sidebar.php'; ?>

<div id="admin-content">
    <div class="wrap" style="max-width: 1200px; margin: 20px;">
        <h1 class="wp-heading-inline">Product Enquiries (Quotes)</h1>
        <hr style="margin: 20px 0; border: 0; border-top: 1px solid #ddd;">

        <?php if(isset($msg)): ?>
            <div class="notice">
                <p><?php echo $msg; ?></p>
            </div>
        <?php endif; ?>
        <?php if(isset($error)): ?>
            <div class="notice notice-error">
                <p><?php echo $error; ?></p>
            </div>
        <?php endif; ?>

        <table class="wp-list-table">
            <thead>
                <tr>
                    <th width="50">ID</th>
                    <th width="150">Date</th>
                    <th>Product</th>
                    <th>Customer Info</th>
                    <th>Requirement</th>
                    <th width="80">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>#<?php echo $row['id']; ?></td>
                            <td><?php echo date("d M Y H:i", strtotime($row['created_at'])); ?></td>
                            <td>
                                <?php if($row['product_name']): ?>
                                    <a href="../../product-details.php?id=<?php echo $row['product_id']; ?>" target="_blank" style="color:#0073aa; text-decoration:none;">
                                        <?php echo htmlspecialchars($row['product_name']); ?>
                                    </a>
                                <?php else: ?>
                                    <span style="color:#999;">Product Deleted</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars($row['name']); ?></strong><br>
                                <a href="mailto:<?php echo htmlspecialchars($row['email']); ?>"><?php echo htmlspecialchars($row['email']); ?></a><br>
                                <?php echo htmlspecialchars($row['phone']); ?><br>
                                <?php if(!empty($row['company_name'])) echo "Company: " . htmlspecialchars($row['company_name']); ?>
                            </td>
                            <td>
                                <strong>Qty:</strong> <?php echo $row['quantity']; ?><br>
                                <?php if(!empty($row['address'])): ?>
                                    <strong>Address:</strong> <?php echo nl2br(htmlspecialchars($row['address'])); ?><br>
                                <?php endif; ?>
                                <?php if(!empty($row['message'])): ?>
                                    <strong>Message:</strong> <?php echo nl2br(htmlspecialchars($row['message'])); ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="index.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this enquiry?');" style="color:#b32d2e; text-decoration:none;">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No enquiries found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <!-- Pagination -->
        <?php if($total_pages > 1): ?>
        <div class="tablenav-pages">
            <div class="pagination-links">
                <?php 
                for($i=1; $i<=$total_pages; $i++) {
                    $active = ($i == $page) ? 'current' : '';
                    echo "<a href='index.php?page=$i' class='$active'>$i</a>";
                }
                ?>
            </div>
        </div>
        <?php endif; ?>
        
    </div>
</div>

</body>
</html>
