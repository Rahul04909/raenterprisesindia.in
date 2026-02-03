<?php
// Include Database Configuration
include 'dbms/dbms_config.php';

// Include the header
include 'includes/header.php';
// Include the hero slider
include 'components/hero.php';
?>

<!-- Main Content (Currently Empty/Hidden) -->
<main>
    <!-- Content will go here -->
    <?php include 'components/team-members.php'; ?>
    <?php include 'components/category-showcase.php'; ?>
    <?php include 'components/products-by-category.php'; ?>
    <?php include 'components/our-clients.php'; ?>
</main>

<?php
// Include the footer
include 'includes/footer.php';
?>

</body>
</html>
