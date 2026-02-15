<?php
// sitemap.php - Generates XML Sitemap dynamically
include 'dbms/dbms_config.php';

// Set headers
header("Content-Type: application/xml; charset=utf-8");

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <!-- Static Pages -->
    <url>
        <loc>https://raenterprisesindia.in/</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>https://raenterprisesindia.in/products.php</loc>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc>https://raenterprisesindia.in/contact.php</loc>
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>
    <url>
        <loc>https://raenterprisesindia.in/about-us.php</loc>
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>

    <?php
    // 1. Categories
    $cat_sql = "SELECT id, updated_at FROM product_categories ORDER BY id DESC";
    $cat_res = $conn->query($cat_sql);
    if ($cat_res->num_rows > 0) {
        while ($cat = $cat_res->fetch_assoc()) {
            $url = "https://raenterprisesindia.in/products.php?cat=" . $cat['id'];
            $date = date('c', strtotime($cat['updated_at'] ?? date('Y-m-d H:i:s')));
            echo "
    <url>
        <loc>{$url}</loc>
        <lastmod>{$date}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>";
        }
    }

    // 2. Brands
    $brand_sql = "SELECT id, created_at FROM brands ORDER BY id DESC";
    $brand_res = $conn->query($brand_sql);
    if ($brand_res->num_rows > 0) {
        while ($brand = $brand_res->fetch_assoc()) {
            $url = "https://raenterprisesindia.in/products.php?brands[]=" . $brand['id'];
            $url = htmlspecialchars($url);
            $date = date('c', strtotime($brand['created_at'] ?? date('Y-m-d H:i:s')));
            echo "
    <url>
        <loc>{$url}</loc>
        <lastmod>{$date}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>";
        }
    }

    // 3. Products
    $prod_sql = "SELECT id, updated_at FROM products ORDER BY id DESC";
    $prod_res = $conn->query($prod_sql);
    if ($prod_res->num_rows > 0) {
        while ($prod = $prod_res->fetch_assoc()) {
            $url = "https://raenterprisesindia.in/product-details.php?id=" . $prod['id'];
            $date = date('c', strtotime($prod['updated_at'] ?? date('Y-m-d H:i:s')));
            echo "
    <url>
        <loc>{$url}</loc>
        <lastmod>{$date}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>";
        }
    }
    ?>
</urlset>
