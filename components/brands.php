<?php
// Ensure database connection is available
// Assuming $conn is available from the parent page including this component
// If not, we might need to include it, but usually components are included in pages that already have db config.
// Ideally, we should check if $conn is set.

if (!isset($conn)) {
    // Fallback or error handling if needed. For now assuming it's included by parent.
    // However, to be safe and standalone-ish for testing, we might want to check.
    // But standard practice in this project seems to be including dbms_config.php in the main page.
}

// Fetch Brands
$brands_sql = "SELECT name, logo FROM brands ORDER BY id DESC";
$brands_result = $conn->query($brands_sql);
$brands = [];
if ($brands_result && $brands_result->num_rows > 0) {
    while($row = $brands_result->fetch_assoc()) {
        $brands[] = $row;
    }
}
?>

<section class="clients-section">
    <div class="container">
        <div class="section-title">
            <h2>Our Brands</h2>
            <p>We are proud to work with these industry leaders.</p>
        </div>
        
        <?php if (!empty($brands)): ?>
        <div class="ticker-wrap">
            <div class="ticker-content">
                <?php
                // Function to output client logos
                function renderBrandLogos($brands) {
                    foreach ($brands as $brand) {
                        if (!empty($brand['logo'])) {
                            echo '<div class="client-logo">';
                            echo '<img src="' . htmlspecialchars($brand['logo']) . '" alt="' . htmlspecialchars($brand['name']) . '">';
                            echo '</div>';
                        }
                    }
                }

                // Output the set multiple times to ensure it fills the width and loops smoothly
                // We repeat it 4 times here to be safe on wide screens, matching our-clients.php logic
                renderBrandLogos($brands);
                renderBrandLogos($brands);
                renderBrandLogos($brands);
                renderBrandLogos($brands);
                ?>
            </div>
        </div>
        <?php else: ?>
            <p class="text-center">No brands to display currently.</p>
        <?php endif; ?>
    </div>
</section>
