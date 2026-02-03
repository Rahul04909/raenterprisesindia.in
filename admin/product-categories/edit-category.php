<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}
include '../../dbms/dbms_config.php';

$msg = "";
$error = "";
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch Brands for Dropdown
$brands_result = $conn->query("SELECT id, name FROM brands ORDER BY name ASC");

// Fetch Existing Category Data
$sql = "SELECT * FROM product_categories WHERE id = $id";
$result = $conn->query($sql);
if ($result->num_rows == 0) { die("Category not found."); }
$row = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $brand_id = intval($_POST['brand_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $meta_title = mysqli_real_escape_string($conn, $_POST['meta_title']);
    $meta_description = mysqli_real_escape_string($conn, $_POST['meta_description']);
    $meta_keywords = mysqli_real_escape_string($conn, $_POST['meta_keywords']);
    
    // File Upload Handling
    $target_dir = "../../assets/uploads/categories/";
    $image_path = $row['image']; // Default to existing
    
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }

        $file_name = time() . "_" . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $file_name;
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        
        if($check !== false) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image_path = "assets/uploads/categories/" . $file_name;
            } else {
                $error = "Sorry, there was an error uploading your file.";
            }
        } else {
             $error = "File is not an image.";
        }
    }
    
    if (empty($error)) {
        $sql = "UPDATE product_categories SET 
                brand_id=$brand_id, 
                name='$name', 
                image='$image_path', 
                description='$description', 
                meta_title='$meta_title', 
                meta_description='$meta_description', 
                meta_keywords='$meta_keywords' 
                WHERE id=$id";
        
        if ($conn->query($sql) === TRUE) {
            $msg = "Category updated successfully. <a href='index.php'>Go Back</a>";
            // Refresh data
            $result = $conn->query("SELECT * FROM product_categories WHERE id = $id");
            $row = $result->fetch_assoc();
        } else {
            $error = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product Category &lsaquo; RA Admin</title>
    <!-- Include Admin Styles -->
    <link rel="stylesheet" href="../assets/css/admin-sidebar.css">
    <link rel="stylesheet" href="../assets/css/admin-dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .form-wrap {
            background:#fff;
            padding: 20px;
            border: 1px solid #c3c4c7;
            box-shadow: 0 1px 1px rgba(0,0,0,0.04);
            max-width: 800px;
        }
        .form-field { margin-bottom: 20px; }
        .form-field label { display: block; font-weight: 600; margin-bottom: 5px; color: #23282d; }
        .form-field input[type="text"], 
        .form-field select,
        .form-field textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #8c8f94;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .form-field textarea { height: 100px; font-family: inherit; }
        .submit-btn {
            background: #0073aa;
            border-color: #0073aa;
            color: #fff;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 3px;
            cursor: pointer;
            border-style: solid;
            border-width: 1px;
            font-size: 13px;
            font-weight: 600;
        }
        .submit-btn:hover { background: #006799; border-color: #006799; }
        
        .meta-box { background: #fff; border: 1px solid #ccd0d4; margin-top: 20px; }
        .meta-box-header { padding: 10px 15px; border-bottom: 1px solid #ccd0d4; background: #fcfcfc; font-weight: 600; }
        .meta-box-body { padding: 15px; }
        
        .current-img { margin-top: 10px; max-width: 150px; border: 1px solid #ddd; padding: 5px; }
    </style>
    <script src="../../vendor/ckeditor/ckeditor/ckeditor.js"></script>
</head>
<body>

<?php include '../sidebar.php'; ?>

<div id="admin-content">
    <div class="wrap" style="max-width: 800px; margin: 20px;">
        <h1 style="font-weight: 400; font-size: 23px;">Edit Product Category</h1>
        
        <?php if(!empty($msg)): ?>
            <div style="background:#fff; border-left: 4px solid #00a32a; padding: 10px; margin-bottom: 20px; box-shadow: 0 1px 1px rgba(0,0,0,0.1);"><?php echo $msg; ?></div>
        <?php endif; ?>
        <?php if(!empty($error)): ?>
            <div style="background:#fff; border-left: 4px solid #d63638; padding: 10px; margin-bottom: 20px; box-shadow: 0 1px 1px rgba(0,0,0,0.1);"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-wrap">
                <div class="form-field">
                    <label for="brand_id">Select Brand</label>
                    <select name="brand_id" id="brand_id" required>
                        <option value="">-- Choose Brand --</option>
                        <?php 
                        $brands_result->data_seek(0); // Reset pointer
                        while($brand = $brands_result->fetch_assoc()): 
                        ?>
                            <option value="<?php echo $brand['id']; ?>" <?php if($brand['id'] == $row['brand_id']) echo 'selected'; ?>><?php echo htmlspecialchars($brand['name']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-field">
                    <label for="name">Category Name</label>
                    <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($row['name']); ?>" required>
                </div>
                
                <div class="form-field">
                    <label for="image">Category Image</label>
                    <input type="file" name="image" id="image">
                    <?php if(!empty($row['image'])): ?>
                        <br><img src="../../<?php echo htmlspecialchars($row['image']); ?>" class="current-img">
                    <?php endif; ?>
                </div>
                
                <div class="form-field">
                    <label for="description">Description</label>
                    <textarea name="description" id="description"><?php echo htmlspecialchars($row['description']); ?></textarea>
                    <script>CKEDITOR.replace('description');</script>
                </div>
            </div>

            <!-- SEO Meta Box -->
            <div class="meta-box">
                <div class="meta-box-header">SEO Settings</div>
                <div class="meta-box-body">
                    <div class="form-field">
                        <label for="meta_title">Meta Title</label>
                        <input type="text" name="meta_title" id="meta_title" value="<?php echo htmlspecialchars($row['meta_title']); ?>">
                    </div>
                    <div class="form-field">
                        <label for="meta_description">Meta Description</label>
                        <textarea name="meta_description" id="meta_description" style="height: 60px;"><?php echo htmlspecialchars($row['meta_description']); ?></textarea>
                    </div>
                    <div class="form-field">
                        <label for="meta_keywords">Meta Keywords</label>
                        <input type="text" name="meta_keywords" id="meta_keywords" value="<?php echo htmlspecialchars($row['meta_keywords']); ?>" placeholder="keyword1, keyword2">
                    </div>
                </div>
            </div>

            <div style="margin-top: 20px;">
                <button type="submit" class="submit-btn">Update Category</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>
