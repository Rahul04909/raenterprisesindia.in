<?php
// components/category-showcase.php
// Expects optional parameters: $showcase_title
// Assuming DB connection is already available via $conn from the including page

if(!isset($conn)) {
    // Fallback if component used standalone (though mostly it will be included)
    // include('dbms/dbms_config.php'); 
    // Ideally user includes it in index.php
}

$showcase_title = isset($showcase_title) ? $showcase_title : "Featured Products";

// 1. Fetch Top Brands (Limit 4)
// In a real scenario, you might want specific brands. Random/Limit for now.
$brands_sql = "SELECT * FROM brands ORDER BY id DESC LIMIT 4";
$brands_res = $conn->query($brands_sql);

// 2. Fetch Featured Categories (Limit 4)
$cats_sql = "SELECT product_categories.*, brands.name as brand_name 
             FROM product_categories 
             JOIN brands ON product_categories.brand_id = brands.id
             ORDER BY product_categories.id DESC LIMIT 4";
$cats_res = $conn->query($cats_sql);

// 3. Fetch Products (Limit 10) - Removed is_price_enabled restriction to show all
$prods_sql = "SELECT * FROM products ORDER BY id DESC LIMIT 10";
$prods_res = $conn->query($prods_sql);
?>

<link rel="stylesheet" href="assets/css/category-showcase.css">
<!-- FontAwesome assumed included in header -->

<section class="cat-showcase-section">
    <div class="cat-showcase-container">
        <!-- Header -->
        <div class="cat-showcase-header">
            <h2 class="cat-showcase-title"><?php echo htmlspecialchars($showcase_title); ?></h2>
            <a href="products.php" class="cat-showcase-btn">VIEW ALL</a>
        </div>

        <!-- Top Row: Brands & Categories -->
        <div class="cat-showcase-top-row">
            
            <!-- Left: Top Brands -->
            <div class="cat-brands-box">
                <div class="cat-brands-title">Top Brands & Related Categories</div>
                <div class="cat-brands-grid">
                    <?php if($brands_res->num_rows > 0): ?>
                        <?php while($brand = $brands_res->fetch_assoc()): ?>
                            <a href="products.php?brand=<?php echo $brand['id']; ?>" class="cat-brand-item">
                                <div class="cat-brand-logo-wrap">
                                    <?php if(!empty($brand['logo'])): ?>
                                        <img src="<?php echo htmlspecialchars($brand['logo']); ?>" alt="<?php echo htmlspecialchars($brand['name']); ?>">
                                    <?php else: ?>
                                        <i class="fas fa-tag" style="color:#ccc;"></i>
                                    <?php endif; ?>
                                </div>
                                <span><?php echo htmlspecialchars(substr($brand['name'], 0, 10)); ?></span>
                            </a>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div style="font-size:12px; color:#999;">No brands found</div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right: Featured Categories -->
            <div class="cat-feat-grid">
                 <?php if($cats_res->num_rows > 0): ?>
                    <?php while($cat = $cats_res->fetch_assoc()): ?>
                        <a href="products.php?cat=<?php echo $cat['id']; ?>" class="cat-feat-card">
                            <div class="cat-feat-img-box">
                                <?php if(!empty($cat['image'])): ?>
                                    <img src="<?php echo htmlspecialchars($cat['image']); ?>" class="cat-feat-img" alt="<?php echo htmlspecialchars($cat['name']); ?>">
                                <?php else: ?>
                                    <i class="fas fa-box-open" style="font-size:40px; color:#ddd;"></i>
                                <?php endif; ?>
                            </div>
                            <div>
                                <div class="cat-feat-name"><?php echo htmlspecialchars($cat['name']); ?></div>
                                <div class="cat-feat-link">Explore Now</div>
                            </div>
                        </a>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p style="text-align:center; grid-column:1/-1;">No categories found</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Bottom Row: Product Slider -->
        <div class="cat-products-row">
            <div class="cat-products-scroll">
                <?php if($prods_res->num_rows > 0): ?>
                    <?php while($prod = $prods_res->fetch_assoc()): 
                        // Calculate Discount
                        $off_per = 0;
                        if($prod['is_price_enabled'] && $prod['mrp'] > 0 && $prod['sale_price'] > 0 && $prod['mrp'] > $prod['sale_price']) {
                            $off_per = round((($prod['mrp'] - $prod['sale_price']) / $prod['mrp']) * 100);
                        }
                    ?>
                        <a href="product-details.php?id=<?php echo $prod['id']; ?>" class="cat-product-card">
                            <div class="cat-prod-img-wrap">
                                <?php if(!empty($prod['image'])): ?>
                                    <img src="<?php echo htmlspecialchars($prod['image']); ?>" alt="<?php echo htmlspecialchars($prod['name']); ?>">
                                <?php else: ?>
                                    <i class="fas fa-image" style="color:#eee; font-size:40px;"></i>
                                <?php endif; ?>
                            </div>
                            
                            <div class="cat-prod-rating">
                                <span class="cat-badge-star">4.5 <i class="fas fa-star"></i></span>
                                <span style="margin-left:5px;">(24 Reviews)</span>
                            </div>

                            <div class="cat-prod-title" title="<?php echo htmlspecialchars($prod['name']); ?>">
                                <?php echo htmlspecialchars($prod['name']); ?>
                            </div>

                            <div class="cat-prod-price-box">
                                <?php if($prod['is_price_enabled']): ?>
                                    <span class="cat-prod-price">₹<?php echo number_format($prod['sale_price']); ?></span>
                                    <?php if($off_per > 0): ?>
                                        <br>
                                        <span class="cat-prod-mrp">₹<?php echo number_format($prod['mrp']); ?></span>
                                        <span class="cat-prod-off"><?php echo $off_per; ?>% OFF</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="cat-prod-price" style="font-size: 14px; color: #0073aa;">Price on Request</span>
                                <?php endif; ?>
                            </div>
                        </a>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p style="padding:20px; color:#777;">No products available to display.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
