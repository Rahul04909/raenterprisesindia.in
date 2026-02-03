<?php
// product-details.php
include 'dbms/dbms_config.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if($id == 0) { header("Location: index.php"); exit; }

// Fetch Product Details
$sql = "SELECT p.*, b.name as brand_name, b.logo as brand_logo, c.name as cat_name 
        FROM products p 
        LEFT JOIN brands b ON p.brand_id = b.id 
        LEFT JOIN product_categories c ON p.category_id = c.id 
        WHERE p.id = $id";
$result = $conn->query($sql);
if($result->num_rows == 0) { header("Location: index.php"); exit; }
$prod = $result->fetch_assoc();

// Fetch Gallery
$gallery_sql = "SELECT * FROM product_images WHERE product_id = $id";
$gallery_res = $conn->query($gallery_sql);

// Handle Review Submission
$review_msg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_review'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $rating = intval($_POST['rating']);
    $review = mysqli_real_escape_string($conn, $_POST['review']);
    
    $ins_rev = "INSERT INTO product_reviews (product_id, name, email, rating, review) VALUES ($id, '$name', '$email', $rating, '$review')";
    if($conn->query($ins_rev) === TRUE) {
        $review_msg = "Thank you! Your review has been submitted.";
    } else {
        $review_msg = "Error submitting review.";
    }
}

// Fetch Reviews
$reviews_sql = "SELECT * FROM product_reviews WHERE product_id = $id ORDER BY created_at DESC";
$reviews_res = $conn->query($reviews_sql);
$avg_rating = 0;
$total_reviews = $reviews_res->num_rows;
if($total_reviews > 0) {
    $sum_rating = 0;
    while($r = $reviews_res->fetch_assoc()) { $sum_rating += $r['rating']; }
    $avg_rating = round($sum_rating / $total_reviews, 1);
    $reviews_res->data_seek(0); // Reset pointer
}

// Fetch Related Products (Same Category)
$related_sql = "SELECT * FROM products WHERE category_id = {$prod['category_id']} AND id != $id ORDER BY RAND() LIMIT 4";
$related_res = $conn->query($related_sql);

include 'includes/header.php';
?>

<link rel="stylesheet" href="assets/css/product-details.css">

<section class="pd-section">
    <div class="pd-container">
        <!-- Breadcrumb -->
        <div class="pd-breadcrumb">
            <a href="index.php">Home</a> <span>/</span> 
            <a href="products.php?cat=<?php echo $prod['category_id']; ?>"><?php echo htmlspecialchars($prod['cat_name']); ?></a> <span>/</span>
            <?php echo htmlspecialchars($prod['name']); ?>
        </div>
        
        <div class="pd-main-grid">
            <!-- Left: Gallery -->
            <div class="pd-gallery">
                <div class="pd-main-image-wrap">
                    <?php 
                    $main_img = !empty($prod['image']) ? $prod['image'] : 'assets/images/placeholder.jpg'; // Fallback needed
                    ?>
                    <img src="<?php echo htmlspecialchars($main_img); ?>" id="mainImage" class="pd-main-image" alt="<?php echo htmlspecialchars($prod['name']); ?>">
                </div>
                
                <div class="pd-thumb-row">
                    <!-- Main Image Thumb -->
                    <img src="<?php echo htmlspecialchars($main_img); ?>" class="pd-thumb active" onclick="changeImage(this.src, this)">
                    
                    <?php while($img = $gallery_res->fetch_assoc()): ?>
                        <img src="<?php echo htmlspecialchars($img['image_path']); ?>" class="pd-thumb" onclick="changeImage(this.src, this)">
                    <?php endwhile; ?>
                </div>
            </div>
            
            <!-- Right: Info -->
            <div class="pd-info">
                <?php if($prod['brand_name']): ?>
                    <div class="pd-brand" style="display:flex; align-items:center; gap:10px; margin-bottom:10px;">
                        <?php if(!empty($prod['brand_logo'])): ?>
                            <img src="<?php echo htmlspecialchars($prod['brand_logo']); ?>" style="height:30px; width:auto;" alt="<?php echo htmlspecialchars($prod['brand_name']); ?>">
                        <?php endif; ?>
                        <span><?php echo htmlspecialchars($prod['brand_name']); ?></span>
                    </div>
                <?php endif; ?>
                
                <h1 class="pd-title"><?php echo htmlspecialchars($prod['name']); ?></h1>
                
                <div class="pd-rating">
                    <div style="background:#388e3c; color:#fff; padding:2px 5px; border-radius:3px; font-weight:bold; font-size:12px;">
                        <?php echo $avg_rating > 0 ? $avg_rating : 'New'; ?> <i class="fas fa-star" style="font-size:10px; margin-left:2px; color:#fff;"></i>
                    </div>
                    <span>(<?php echo $total_reviews; ?> Reviews)</span>
                </div>
                
                <div class="pd-price-wrap">
                    <?php if($prod['is_price_enabled']): 
                        $off_per = 0;
                        if($prod['mrp'] > 0 && $prod['sale_price'] > 0 && $prod['mrp'] > $prod['sale_price']) {
                            $off_per = round((($prod['mrp'] - $prod['sale_price']) / $prod['mrp']) * 100);
                        }
                    ?>
                        <span class="pd-price">₹<?php echo number_format($prod['sale_price']); ?></span>
                        <?php if($off_per > 0): ?>
                            <span class="pd-price-mrp">₹<?php echo number_format($prod['mrp']); ?></span>
                            <span class="pd-price-off"><?php echo $off_per; ?>% off</span>
                        <?php endif; ?>
                    <?php else: ?>
                        <span class="pd-price" style="font-size:20px; color:#0073aa;">Price on Request</span>
                    <?php endif; ?>
                </div>

                <div class="pd-social-share" style="margin-bottom: 20px;">
                    <span style="font-weight:600; font-size:13px; margin-right:5px;">Share:</span>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"); ?>" target="_blank" class="pd-social-icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"); ?>&text=<?php echo urlencode($prod['name']); ?>" target="_blank" class="pd-social-icon"><i class="fab fa-twitter"></i></a>
                    <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"); ?>" target="_blank" class="pd-social-icon"><i class="fab fa-linkedin-in"></i></a>
                </div>
                
                <div class="pd-actions">
                    <a href="contact.php?subject=Quote for <?php echo urlencode($prod['name']); ?>" class="pd-btn pd-btn-quote">
                        <i class="fas fa-paper-plane" style="margin-right:5px;"></i> Get a Quote
                    </a>
                    <a href="https://wa.me/?text=Check out this product: <?php echo urlencode("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"); ?>" target="_blank" class="pd-btn pd-btn-whatsapp">
                        <i class="fab fa-whatsapp" style="font-size:20px;"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Tabs Section -->
        <div class="pd-tabs-section">
            <div class="pd-tabs-head">
                <div class="pd-tab active" onclick="openTab('desc', this)">Description</div>
                <div class="pd-tab" onclick="openTab('reviews', this)">Reviews (<?php echo $total_reviews; ?>)</div>
            </div>
            
            <div id="desc" class="pd-tab-content active">
                <?php echo $prod['description']; ?>
            </div>
            
            <div id="reviews" class="pd-tab-content">
                <?php if(!empty($review_msg)): ?>
                    <div style="background:#d4edda; color:#155724; padding:10px; border-radius:4px; margin-bottom:15px;"><?php echo $review_msg; ?></div>
                <?php endif; ?>
                
                <div class="pd-reviews-list">
                    <?php if($reviews_res->num_rows > 0): ?>
                        <?php while($rw = $reviews_res->fetch_assoc()): ?>
                            <div class="pd-review-item">
                                <div class="pd-review-head">
                                    <span class="pd-review-name"><?php echo htmlspecialchars($rw['name']); ?></span>
                                    <span class="pd-review-rating"><?php echo $rw['rating']; ?> <i class="fas fa-star"></i></span>
                                </div>
                                <div style="font-size:12px; color:#999; margin-bottom:5px;"><?php echo date("d M Y", strtotime($rw['created_at'])); ?></div>
                                <div style="color:#555;"><?php echo nl2br(htmlspecialchars($rw['review'])); ?></div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>No reviews yet. Be the first to review!</p>
                    <?php endif; ?>
                </div>
                
                <!-- Review Form -->
                <div class="pd-review-form">
                    <h3 style="font-size:18px; margin-bottom:15px;">Write a Review</h3>
                    <form action="" method="post">
                        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:15px;">
                            <div class="pd-form-group">
                                <label>Name</label>
                                <input type="text" name="name" required>
                            </div>
                            <div class="pd-form-group">
                                <label>Email</label>
                                <input type="email" name="email" required>
                            </div>
                        </div>
                        <div class="pd-form-group">
                            <label>Rating</label>
                            <select name="rating" required>
                                <option value="5">5 Stars (Excellent)</option>
                                <option value="4">4 Stars (Good)</option>
                                <option value="3">3 Stars (Average)</option>
                                <option value="2">2 Stars (Poor)</option>
                                <option value="1">1 Star (Terrible)</option>
                            </select>
                        </div>
                        <div class="pd-form-group">
                            <label>Review</label>
                            <textarea name="review" rows="4" required></textarea>
                        </div>
                        <button type="submit" name="submit_review" class="pd-btn pd-btn-quote" style="border:none; width:auto; font-size:14px;">Submit Review</button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Related Products -->
        <?php if($related_res->num_rows > 0): ?>
        <div class="pd-related-section">
            <h2 class="cat-showcase-title">Related Products</h2>
            <div class="pd-main-grid" style="grid-template-columns: repeat(4, 1fr); gap:15px; padding:20px; box-shadow:none; background:transparent;">
                <?php while($rel = $related_res->fetch_assoc()): ?>
                     <a href="product-details.php?id=<?php echo $rel['id']; ?>" style="text-decoration:none; background:#fff; padding:10px; border-radius:4px; border:1px solid #eee; display:flex; flex-direction:column;">
                        <div style="height:150px; display:flex; align-items:center; justify-content:center; margin-bottom:10px;">
                             <img src="<?php echo htmlspecialchars($rel['image']); ?>" style="max-height:100%; max-width:100%;">
                        </div>
                        <div style="font-size:14px; font-weight:600; color:#333; margin-bottom:5px; height: 40px; overflow: hidden;"><?php echo htmlspecialchars($rel['name']); ?></div>
                        <div style="color:#0073aa; font-weight:bold;">
                            <?php echo $rel['is_price_enabled'] ? '₹'.number_format($rel['sale_price']) : 'View Details'; ?>
                        </div>
                     </a>
                <?php endwhile; ?>
            </div>
        </div>
        <?php endif; ?>
        
    </div>
</section>

<script>
    function changeImage(src, el) {
        document.getElementById('mainImage').src = src;
        document.querySelectorAll('.pd-thumb').forEach(t => t.classList.remove('active'));
        el.classList.add('active');
    }

    function openTab(tabId, el) {
        document.querySelectorAll('.pd-tab-content').forEach(c => c.classList.remove('active'));
        document.querySelectorAll('.pd-tab').forEach(t => t.classList.remove('active'));
        document.getElementById(tabId).classList.add('active');
        el.classList.add('active');
    }
</script>

<?php include 'includes/footer.php'; ?>
