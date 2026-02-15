<?php
// sitemap.php - Generates XML Sitemap with SEO URLs
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
        <loc>https://raenterprisesindia.in/products.html</loc>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc>https://raenterprisesindia.in/contact.html</loc>
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>
     <url>
        <loc>https://raenterprisesindia.in/about-us.html</loc>
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>

    <?php
    // 1. Categories -> /category/ID.html
    $cat_sql = "SELECT id, updated_at FROM product_categories ORDER BY id DESC";
    $cat_res = $conn->query($cat_sql);
    if ($cat_res->num_rows > 0) {
        while ($cat = $cat_res->fetch_assoc()) {
            $url = "https://raenterprisesindia.in/category/" . $cat['id'] . ".html";
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

    // 2. Brands -> /products.html?brands[]=ID (No clean URL rewrite defined for multiple brands yet, keeping query string for multi-select, or could do /brand/ID.html if single)
    // The user requirement didn't explicitly ask for brand rewrites like categories, but general .php->.html applies.
    // Let's stick to products.html with query for brands as it's a filter.
    // However, if we want specific brand landing pages, we'd need a rewrite. For now, let's use the .html version of products.
    $brand_sql = "SELECT id, created_at FROM brands ORDER BY id DESC";
    $brand_res = $conn->query($brand_sql);
    if ($brand_res->num_rows > 0) {
        while ($brand = $brand_res->fetch_assoc()) {
            $url = "https://raenterprisesindia.in/products.html?brands[]=" . $brand['id'];
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

    // 3. Products -> /product/ID.html
    $prod_sql = "SELECT id, updated_at FROM products ORDER BY id DESC";
    $prod_res = $conn->query($prod_sql);
    if ($prod_res->num_rows > 0) {
        while ($prod = $prod_res->fetch_assoc()) {
            $url = "https://raenterprisesindia.in/product/" . $prod['id'] . ".html";
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
