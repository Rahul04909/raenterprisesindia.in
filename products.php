<?php
include 'dbms/dbms_config.php';

// Initialize Filter Variables
$category_id = isset($_GET['cat']) ? intval($_GET['cat']) : 0;
$brand_ids = isset($_GET['brands']) ? $_GET['brands'] : []; // Array of brand IDs
$min_price = isset($_GET['min_price']) ? intval($_GET['min_price']) : 0;
$max_price = isset($_GET['max_price']) ? intval($_GET['max_price']) : 0;
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
$search_query = isset($_GET['q']) ? mysqli_real_escape_string($conn, $_GET['q']) : '';

// Build Query Logic
$where_clauses = [];
if ($category_id > 0) {
    $where_clauses[] = "category_id = $category_id";
}
if (!empty($brand_ids) && is_array($brand_ids)) {
    $ids = implode(',', array_map('intval', $brand_ids));
    $where_clauses[] = "brand_id IN ($ids)";
}
if ($min_price > 0) {
    $where_clauses[] = "sale_price >= $min_price";
}
if ($max_price > 0) {
    $where_clauses[] = "sale_price <= $max_price";
}
if (!empty($search_query)) {
    $where_clauses[] = "(name LIKE '%$search_query%' OR description LIKE '%$search_query%')";
}

$where_sql = "";
if (count($where_clauses) > 0) {
    $where_sql = "WHERE " . implode(' AND ', $where_clauses);
}

// Sort Logic
$order_sql = "ORDER BY id DESC"; // Default Newest
switch ($sort) {
    case 'price_low':
        $order_sql = "ORDER BY sale_price ASC";
        break;
    case 'price_high':
        $order_sql = "ORDER BY sale_price DESC";
        break;
    case 'oldest':
        $order_sql = "ORDER BY id ASC";
        break;
}

// Fetch Products
$sql = "SELECT * FROM products $where_sql $order_sql";
$result = $conn->query($sql);
$total_products = $result->num_rows;

// Fetch Categories with Data for Sidebar
$cat_sql = "SELECT c.*, COUNT(p.id) as product_count 
            FROM product_categories c 
            LEFT JOIN products p ON c.id = p.category_id 
            GROUP BY c.id ORDER BY c.name ASC";
$cat_res = $conn->query($cat_sql);

// Fetch Brands for Sidebar
$brand_sql = "SELECT * FROM brands ORDER BY name ASC";
$brand_res = $conn->query($brand_sql);

include 'includes/header.php';
?>

<link rel="stylesheet" href="assets/css/shop.css">

<section class="shop-page-section">
    <div class="shop-container">
        
        <!-- Mobile Sidebar Overlay -->
        <div class="shop-sidebar-overlay" onclick="toggleSidebar()"></div>

        <!-- Sidebar -->
        <aside class="shop-sidebar" id="shopSidebar">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;" class="hide-desktop">
                 <h3 style="margin:0;">Filters</h3>
                 <span onclick="toggleSidebar()" style="cursor:pointer; font-size:20px;">&times;</span>
            </div>

            <br><small>URL Rewritten via .htaccess</small>
            <form action="/products.html" method="GET" id="filterForm">
                
                <!-- Keep search query if exists -->
                <?php if(!empty($search_query)): ?>
                    <input type="hidden" name="q" value="<?php echo htmlspecialchars($search_query); ?>">
                <?php endif; ?>

                <!-- Categories -->
                <div class="shop-widget">
                    <h4 class="shop-widget-title">Categories</h4>
                    <ul class="shop-cat-list">
                        <li>
                            <a href="/products.html" class="shop-cat-link <?php echo ($category_id == 0) ? 'active' : ''; ?>">
                                <span>All Products</span>
                            </a>
                        </li>
                        <?php while($cat = $cat_res->fetch_assoc()): ?>
                            <li>
                                <a href="/category/<?php echo $cat['id']; ?>.html" class="shop-cat-link <?php echo ($category_id == $cat['id']) ? 'active' : ''; ?>">
                                    <span><?php echo htmlspecialchars($cat['name']); ?></span>
                                    <span class="shop-cat-count"><?php echo $cat['product_count']; ?></span>
                                </a>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                </div>

                <!-- Price Filter -->
                <div class="shop-widget">
                    <h4 class="shop-widget-title">Price Range</h4>
                    <div class="price-inputs">
                        <input type="number" name="min_price" placeholder="Min" value="<?php echo $min_price > 0 ? $min_price : ''; ?>">
                        <input type="number" name="max_price" placeholder="Max" value="<?php echo $max_price > 0 ? $max_price : ''; ?>">
                    </div>
                </div>

                <!-- Brands -->
                <div class="shop-widget">
                    <h4 class="shop-widget-title">Brands</h4>
                    <ul class="shop-brand-list">
                        <?php while($brand = $brand_res->fetch_assoc()): 
                            $checked = (in_array($brand['id'], $brand_ids)) ? 'checked' : '';
                        ?>
                            <li>
                                <label class="custom-checkbox">
                                    <input type="checkbox" name="brands[]" value="<?php echo $brand['id']; ?>" <?php echo $checked; ?>>
                                    <?php echo htmlspecialchars($brand['name']); ?>
                                </label>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                </div>

                <input type="hidden" name="cat" value="<?php echo $category_id; ?>">
                <button type="submit" class="apply-filter-btn">Apply Filters</button>
            </form>
        </aside>

        <!-- Main Content -->
        <div class="shop-content">
            
            <button class="shop-filter-toggle" onclick="toggleSidebar()">
                <i class="fas fa-filter"></i> Refine Search
            </button>

            <div class="shop-header">
                <div class="shop-result-count">
                    Showing <strong><?php echo $total_products; ?></strong> products
                </div>
                <div class="shop-sort-wrap">
                    <label style="font-size:14px; color:#555;">Sort By:</label>
                    <select class="shop-sort-select" onchange="updateSort(this.value)">
                        <option value="newest" <?php echo ($sort == 'newest') ? 'selected' : ''; ?>>Newest First</option>
                        <option value="price_low" <?php echo ($sort == 'price_low') ? 'selected' : ''; ?>>Price: Low to High</option>
                        <option value="price_high" <?php echo ($sort == 'price_high') ? 'selected' : ''; ?>>Price: High to Low</option>
                        <option value="oldest" <?php echo ($sort == 'oldest') ? 'selected' : ''; ?>>Oldest First</option>
                    </select>
                </div>
            </div>

            <div class="shop-grid">
                <?php if($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): 
                        // Calc discount
                        $off_per = 0;
                        if($row['is_price_enabled'] && $row['mrp'] > 0 && $row['sale_price'] > 0 && $row['mrp'] > $row['sale_price']) {
                            $off_per = round((($row['mrp'] - $row['sale_price']) / $row['mrp']) * 100);
                        }
                    ?>
                        <div class="shop-card">
                            <a href="/product/<?php echo $row['id']; ?>.html" class="shop-card-img-wrap">
                                <?php if(!empty($row['image'])): ?>
                                    <img src="<?php echo htmlspecialchars($row['image']); ?>" class="shop-card-img" alt="<?php echo htmlspecialchars($row['name']); ?>">
                                <?php else: ?>
                                    <i class="fas fa-image" style="font-size:40px; color:#eee;"></i>
                                <?php endif; ?>
                            </a>
                            <div class="shop-card-cat">
                                <?php 
                                    // Normally we would join category name in main query to display here, 
                                    // but for minimal query change, let's keep it simple or skip it.
                                    // Or we can just show "Brand" if available?
                                    // Let's show ID or generic 'Product' for now if cat name not fetched.
                                    echo "Product";
                                ?>
                            </div>
                            <h3 class="shop-card-title">
                                <a href="/product/<?php echo $row['id']; ?>.html" style="text-decoration:none; color:inherit;">
                                    <?php echo htmlspecialchars($row['name']); ?>
                                </a>
                            </h3>
                            <div class="shop-card-price-box">
                                <?php if($row['is_price_enabled']): ?>
                                    <div class="shop-card-price">₹<?php echo number_format($row['sale_price']); ?></div>
                                    <?php if($off_per > 0): ?>
                                        <div class="shop-card-mrp">₹<?php echo number_format($row['mrp']); ?></div>
                                        <div class="shop-card-off"><?php echo $off_per; ?>% OFF</div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div class="shop-card-price" style="font-size:16px; color:#0073aa;">Price on Request</div>
                                <?php endif; ?>
                            </div>
                            <a href="/product/<?php echo $row['id']; ?>.html" class="shop-card-btn">View Details</a>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div style="grid-column: 1 / -1; padding: 40px; text-align: center; background: #fff; border-radius: 8px;">
                        <i class="fas fa-search" style="font-size: 40px; color: #ddd; margin-bottom: 20px;"></i>
                        <h3>No products found</h3>
                        <p style="color: #777;">Try adjusting your filters or search query.</p>
                        <a href="/products.html" class="apply-filter-btn" style="display:inline-block; width:auto; text-decoration:none; margin-top:15px;">Clear Filters</a>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</section>

<script>
    function toggleSidebar() {
        document.getElementById('shopSidebar').classList.toggle('active');
        document.querySelector('.shop-sidebar-overlay').classList.toggle('active');
    }

    function updateSort(sortValue) {
        const urlParams = new URLSearchParams(window.location.search);
        urlParams.set('sort', sortValue);
        window.location.search = urlParams.toString();
    }
</script>

<?php include 'includes/footer.php'; ?>
