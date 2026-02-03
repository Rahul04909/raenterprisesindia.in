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

// Fetch Brands
$brands_res = $conn->query("SELECT id, name FROM brands ORDER BY name ASC");
// Fetch Categories
$cats_res = $conn->query("SELECT id, name FROM product_categories ORDER BY name ASC");
$categories = [];
while($c = $cats_res->fetch_assoc()) { $categories[] = $c; }

// Fetch Product Data
$sql = "SELECT * FROM products WHERE id = $id";
$result = $conn->query($sql);
if($result->num_rows == 0) { die("Product not found"); }
$row = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $brand_id = intval($_POST['brand_id']);
    $category_id = intval($_POST['category_id']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $is_price_enabled = isset($_POST['is_price_enabled']) ? 1 : 0;
    
    $mrp = ($is_price_enabled) ? floatval($_POST['mrp']) : 0.00;
    $sale_price = ($is_price_enabled) ? floatval($_POST['sale_price']) : 0.00;
    
    $meta_title = mysqli_real_escape_string($conn, $_POST['meta_title']);
    $meta_description = mysqli_real_escape_string($conn, $_POST['meta_description']);
    $meta_keywords = mysqli_real_escape_string($conn, $_POST['meta_keywords']);
    $schema_markup = mysqli_real_escape_string($conn, $_POST['schema_markup']);
    
    // File Upload
    $target_dir = "../../assets/uploads/products/";
    $image_path = $row['image'];
    
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }
        
        $file_name = time() . "_" . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $file_name;
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        
        if($check !== false) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image_path = "assets/uploads/products/" . $file_name;
            } else {
                $error = "Error uploading file.";
            }
        } else {
             $error = "File is not an image.";
        }
    }
    
    if (empty($error)) {
        $update_sql = "UPDATE products SET 
            brand_id=$brand_id, category_id=$category_id, name='$name', image='$image_path', description='$description', 
            is_price_enabled=$is_price_enabled, mrp=$mrp, sale_price=$sale_price, 
            meta_title='$meta_title', meta_description='$meta_description', meta_keywords='$meta_keywords', schema_markup='$schema_markup' 
            WHERE id=$id";
        
        if ($conn->query($update_sql) === TRUE) {
            $msg = "Product updated successfully. <a href='index.php'>Go Back</a>";
            // Refresh
            $result = $conn->query("SELECT * FROM products WHERE id = $id");
            $row = $result->fetch_assoc();
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product &lsaquo; RA Admin</title>
    <!-- Include Admin Styles -->
    <link rel="stylesheet" href="../assets/css/admin-sidebar.css">
    <link rel="stylesheet" href="../assets/css/admin-dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .form-wrap { background:#fff; padding: 20px; border: 1px solid #c3c4c7; box-shadow: 0 1px 1px rgba(0,0,0,0.04); }
        .form-field { margin-bottom: 20px; }
        .form-field label { display: block; font-weight: 600; margin-bottom: 5px; color: #23282d; }
        .form-field input[type="text"], .form-field input[type="number"], .form-field select, .form-field textarea {
            width: 100%; padding: 8px; border: 1px solid #8c8f94; border-radius: 4px; box-sizing: border-box;
        }
        .form-field textarea { height: 100px; font-family: inherit; }
        .submit-btn {
            background: #0073aa; border-color: #0073aa; color: #fff; padding: 10px 20px; text-decoration: none;
            border-radius: 3px; cursor: pointer; border-style: solid; border-width: 1px; font-size: 14px; font-weight: 600;
        }
        .submit-btn:hover { background: #006799; border-color: #006799; }
        
        .meta-box { background: #fff; border: 1px solid #ccd0d4; margin-top: 20px; }
        .meta-box-header { padding: 10px 15px; border-bottom: 1px solid #ccd0d4; background: #fcfcfc; font-weight: 600; }
        .meta-box-body { padding: 15px; }
        
        .layout-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; }
        @media (max-width: 900px) { .layout-grid { grid-template-columns: 1fr; } }
        
        .price-section { padding: 15px; background: #f9f9f9; border: 1px solid #ddd; margin-top: 10px; display: none; }
        .price-section.active { display: block; }
        
        .current-img { max-width: 200px; margin-top: 10px; border: 1px solid #ddd; padding: 5px; }
    </style>
    <script src="../../vendor/ckeditor/ckeditor/ckeditor.js"></script>
</head>
<body>

<?php include '../sidebar.php'; ?>

<div id="admin-content">
    <div class="wrap" style="max-width: 1200px; margin: 20px;">
        <h1 style="font-weight: 400; font-size: 23px;">Edit Product</h1>
        
        <?php if(!empty($msg)): ?>
            <div style="background:#fff; border-left: 4px solid #00a32a; padding: 10px; margin-bottom: 20px; box-shadow: 0 1px 1px rgba(0,0,0,0.1);"><?php echo $msg; ?></div>
        <?php endif; ?>
        <?php if(!empty($error)): ?>
            <div style="background:#fff; border-left: 4px solid #d63638; padding: 10px; margin-bottom: 20px; box-shadow: 0 1px 1px rgba(0,0,0,0.1);"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="" method="post" enctype="multipart/form-data">
            <div class="layout-grid">
                <!-- Main Column -->
                <div>
                    <div class="form-wrap">
                        <div class="form-field">
                            <label for="name">Product Name</label>
                            <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($row['name']); ?>" required>
                        </div>
                        
                        <div class="form-field">
                            <label for="description">Description (Auto-updates Schema on Change)</label>
                            <textarea name="description" id="description"><?php echo htmlspecialchars($row['description']); ?></textarea>
                            <script>
                                var editor = CKEDITOR.replace('description');
                            </script>
                        </div>
                    </div>

                    <!-- Price Settings -->
                    <div class="meta-box">
                        <div class="meta-box-header">Product Data</div>
                        <div class="meta-box-body">
                            <div class="form-field">
                                <label style="display: inline-block; cursor: pointer;">
                                    <input type="checkbox" name="is_price_enabled" id="is_price_enabled" value="1" <?php if($row['is_price_enabled']) echo 'checked'; ?> onchange="togglePrice()"> 
                                    Enable Price &amp; Stock Management
                                </label>
                            </div>
                            
                            <div id="price-fields" class="price-section <?php if($row['is_price_enabled']) echo 'active'; ?>">
                                <div style="display: flex; gap: 15px;">
                                    <div class="form-field" style="flex: 1;">
                                        <label for="mrp">Regular Price (MRP) ₹</label>
                                        <input type="number" step="0.01" name="mrp" id="mrp" value="<?php echo $row['mrp']; ?>">
                                    </div>
                                    <div class="form-field" style="flex: 1;">
                                        <label for="sale_price">Sale Price ₹</label>
                                        <input type="number" step="0.01" name="sale_price" id="sale_price" value="<?php echo $row['sale_price']; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Schema Markup -->
                    <div class="meta-box">
                        <div class="meta-box-header">Schema Markup (JSON-LD)</div>
                        <div class="meta-box-body">
                            <p style="font-size: 12px; color: #666; margin-top: 0;">You can manually edit this.</p>
                            <div class="form-field">
                                <textarea name="schema_markup" id="schema_markup" style="height: 200px; font-family: monospace; font-size: 12px; white-space: pre;"><?php echo htmlspecialchars($row['schema_markup']); ?></textarea>
                            </div>
                            <button type="button" onclick="generateSchema()" style="font-size: 12px; padding: 4px 8px; border: 1px solid #ddd; background: #f0f0f1; cursor: pointer;">Regenerate Schema (Overwrites edits)</button>
                        </div>
                    </div>
                    
                    <!-- SEO Settings -->
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
                </div>

                <!-- Sidebar Column -->
                <div>
                    <div class="meta-box" style="margin-top: 0;">
                        <div class="meta-box-header">Update</div>
                        <div class="meta-box-body">
                            <button type="submit" class="submit-btn" style="width: 100%;">Update Product</button>
                        </div>
                    </div>

                    <div class="meta-box">
                        <div class="meta-box-header">Organization</div>
                        <div class="meta-box-body">
                            <div class="form-field">
                                <label for="brand_id">Brand</label>
                                <select name="brand_id" id="brand_id">
                                    <option value="">-- Select Brand --</option>
                                    <?php 
                                    $brands_res->data_seek(0);
                                    while($b = $brands_res->fetch_assoc()): ?>
                                        <option value="<?php echo $b['id']; ?>" <?php if($b['id'] == $row['brand_id']) echo 'selected'; ?>><?php echo htmlspecialchars($b['name']); ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            
                            <div class="form-field">
                                <label for="category_id">Category</label>
                                <select name="category_id" id="category_id">
                                    <option value="">-- Select Category --</option>
                                    <?php foreach($categories as $c): ?>
                                        <option value="<?php echo $c['id']; ?>" <?php if($c['id'] == $row['category_id']) echo 'selected'; ?>><?php echo htmlspecialchars($c['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="meta-box">
                        <div class="meta-box-header">Product Image</div>
                        <div class="meta-box-body">
                            <input type="file" name="image" id="image">
                            <?php if(!empty($row['image'])): ?>
                                <img src="../../<?php echo htmlspecialchars($row['image']); ?>" class="current-img">
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function togglePrice() {
        var isChecked = document.getElementById('is_price_enabled').checked;
        var priceSection = document.getElementById('price-fields');
        if(isChecked) {
            priceSection.classList.add('active');
        } else {
            priceSection.classList.remove('active');
        }
    }

    function generateSchema() {
        var name = document.getElementById('name').value;
        var description = "";
        
        if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances.description) {
            description = CKEDITOR.instances.description.getData().replace(/<[^>]*>?/gm, ''); 
        } else {
             var descElem = document.getElementById('description');
             if(descElem) description = descElem.value;
        }

        var isPriceEnabled = document.getElementById('is_price_enabled').checked;
        var mrp = document.getElementById('mrp').value;
        var salePrice = document.getElementById('sale_price').value;
        
        var schema = {
            "@context": "https://schema.org/",
            "@type": "Product",
            "name": name,
            "description": description,
            "sku": "",  
            "brand": { "@type": "Brand", "name": "" }
        };

        if (isPriceEnabled && salePrice) {
            schema.offers = {
                "@type": "Offer",
                "url": window.location.href, 
                "priceCurrency": "INR",
                "price": salePrice,
                "priceValidUntil": "2025-12-31",
                "availability": "https://schema.org/InStock"
            };
        }

        var schemaElem = document.getElementById('schema_markup');
        if(schemaElem) {
             schemaElem.value = JSON.stringify(schema, null, 4);
        }
    }
</script>

</body>
</html>
