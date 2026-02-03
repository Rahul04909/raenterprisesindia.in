<?php
// components/products-by-category.php

// Ensure DB connection
if(!isset($conn)) {
    // include('dbms/dbms_config.php'); // Optional fallback
}

// 1. Fetch Categories
$cat_limit_sql = "SELECT * FROM product_categories ORDER BY id DESC"; // Fetch all or limit
$cat_limit_res = $conn->query($cat_limit_sql);

?>
<link rel="stylesheet" href="assets/css/products-by-category.css">

<?php 
if($cat_limit_res->num_rows > 0):
    while($cat_row = $cat_limit_res->fetch_assoc()):
        $cat_id = $cat_row['id'];
        $cat_name = $cat_row['name'];

        // 2. Fetch Products for this Category
        // Limit to 4 or 5 for a clean row
        $prod_limit_sql = "SELECT * FROM products WHERE category_id = $cat_id ORDER BY id DESC LIMIT 5";
        $prod_limit_res = $conn->query($prod_limit_sql);

        if($prod_limit_res->num_rows > 0):
?>
        <section class="cat-wise-section">
            <div class="cat-wise-container">
                <div class="cat-wise-row">
                    <!-- Header -->
                    <div class="cat-wise-header">
                        <div class="cat-wise-title">
                            Top Deals On <span><?php echo htmlspecialchars($cat_name); ?></span>
                        </div>
                        <a href="products.php?cat=<?php echo $cat_id; ?>" class="cat-wise-btn">VIEW ALL</a>
                    </div>
                    
                    <!-- Grid -->
                    <div class="cat-wise-grid">
                        <?php while($p = $prod_limit_res->fetch_assoc()): 
                            // Price Calculation
                            $off_per = 0;
                            if($p['is_price_enabled'] && $p['mrp'] > 0 && $p['sale_price'] > 0 && $p['mrp'] > $p['sale_price']) {
                                $off_per = round((($p['mrp'] - $p['sale_price']) / $p['mrp']) * 100);
                            }
                            // Image Fallback
                            $p_img = !empty($p['image']) ? $p['image'] : 'assets/images/placeholder.jpg';
                        ?>
                            <a href="product-details.php?id=<?php echo $p['id']; ?>" class="cat-wise-product">
                                <div class="cat-wise-img-wrap">
                                    <img src="<?php echo htmlspecialchars($p_img); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>">
                                </div>
                                
                                <div class="cat-wise-name" title="<?php echo htmlspecialchars($p['name']); ?>">
                                    <?php echo htmlspecialchars($p['name']); ?>
                                </div>
                                
                                <div class="cat-wise-price-box">
                                    <?php if($p['is_price_enabled']): ?>
                                        <div class="cat-wise-price">₹<?php echo number_format($p['sale_price']); ?></div>
                                        <?php if($off_per > 0): ?>
                                            <div>
                                                <span class="cat-wise-mrp">₹<?php echo number_format($p['mrp']); ?></span>
                                                <span class="cat-wise-off"><?php echo $off_per; ?>% OFF</span>
                                            </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <div class="cat-wise-req">Price on Request</div>
                                    <?php endif; ?>
                                </div>
                            </a>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </section>
<?php 
        endif; // End if products > 0
    endwhile; // End while categories
endif; // End if categories > 0
?>
