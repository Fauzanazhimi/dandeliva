<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Calculate cart count & Sync if logged in
if(isset($_SESSION['user_id']) && !isset($_SESSION['cart'])) {
    require_once 'config/database.php';
    $stmt_header = $pdo->prepare("SELECT product_id, quantity FROM cart WHERE user_id = ?");
    $stmt_header->execute([$_SESSION['user_id']]);
    $header_items = $stmt_header->fetchAll(PDO::FETCH_ASSOC);
    if($header_items) {
        $_SESSION['cart'] = [];
        foreach($header_items as $hi) {
            $_SESSION['cart'][$hi['product_id']] = $hi['quantity'];
        }
    }
}

$cart_count = 0;
if(isset($_SESSION['cart'])) {
    foreach($_SESSION['cart'] as $qty) {
        $cart_count += (int)$qty;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dandeliva - Healthy Functional Herbal Gummy</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="announcement-bar">
    <i class="fas fa-gift" style="margin-right:8px;"></i> Promo Spesial! Gratis Ongkir Minimal Order Rp 150.000 dengan kode DANDELIVA2026.
</div>

<header>
    <div class="container">
        <nav class="navbar">
            <a href="index.php" class="logo">
                <img src="assets/images/logo.png" alt="Dandeliva - Gummy Alami">
            </a>
            
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="shop.php">Shop</a></li>
                <li><a href="education.php">Artikel</a></li>
                <li><a href="wellness.php"><i class="fas fa-heartbeat" style="color:var(--primary-color); margin-right:5px;"></i>Care Hub</a></li>
                <li><a href="sadari.php"><i class="fas fa-ribbon" style="color:#e83e8c; margin-right:5px;"></i>SADARI</a></li>
                <li><a href="video.php">Video</a></li>
                <li><a href="about.php">Tentang</a></li>
                <li><a href="contact.php">Kontak</a></li>
            </ul>
            
            <div class="nav-icons" style="display: flex; align-items: center;">
                <a href="<?php echo isset($_SESSION['user_id']) ? 'wellness.php' : 'login.php'; ?>" class="user-icon" style="margin-right: 15px; font-size: 1.2rem; color: var(--text-color);">
                    <i class="fas fa-user-circle"></i>
                </a>
                <a href="cart.php" class="cart-icon">
                    <i class="fas fa-shopping-cart"></i>
                    <?php if($cart_count > 0): ?>
                    <span class="cart-count"><?php echo $cart_count; ?></span>
                    <?php endif; ?>
                </a>
                <button class="mobile-menu-btn" style="margin-left: 15px;">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </nav>
    </div>
</header>
