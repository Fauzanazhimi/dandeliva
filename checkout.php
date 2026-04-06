<?php 
require_once 'config/database.php';
include 'includes/header.php'; 

// Cek apakah cart kosong
if(empty($_SESSION['cart'])) {
    echo "<div class='container section-padding'><h2 class='text-center'>Keranjang Anda kosong!</h2><p class='text-center'><a href='shop.php'>Kembali belanja</a></p></div>";
    include 'includes/footer.php';
    exit;
}

// Calculate Total
$ids = implode(',', array_keys($_SESSION['cart']));
$stmt = $pdo->query("SELECT * FROM products WHERE id IN ($ids)");
$cart_products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total_price = 0;
foreach($cart_products as $product) {
    // calculate total. 
    // It's possible cart holds something no longer in DB, proper checking is needed in prod
    $qty = $_SESSION['cart'][$product['id']];
    $total_price += $product['price'] * $qty;
}

$success = false;
$order_id = 0;

// Handle Checkout processing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['customer_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    
    // Insert to DB
    $insertStmt = $pdo->prepare("INSERT INTO orders (customer_name, email, phone, address, total_price) VALUES (?, ?, ?, ?, ?)");
    $insertStmt->execute([$name, $email, $phone, $address, $total_price]);
    
    $order_id = $pdo->lastInsertId();
    $success = true;
    
    // Clear cart
    unset($_SESSION['cart']);
}

?>

<div class="container section-padding">
    <?php if($success): ?>
        <div class="text-center" style="background:var(--white); padding:50px; border-radius:16px; box-shadow:var(--shadow-sm); max-width:600px; margin:0 auto;">
            <i class="fas fa-check-circle" style="font-size: 5rem; color: var(--success); margin-bottom: 20px;"></i>
            <h2>Pesanan Berhasil!</h2>
            <p>Terima kasih <strong><?php echo htmlspecialchars($name); ?></strong>. Pesanan Anda dengan ID #<?php echo $order_id; ?> sedang kami proses.</p>
            <p>Total Tagihan: <strong>Rp <?php echo number_format($total_price, 0, ',', '.'); ?></strong></p>
            <div style="margin-top: 30px;">
                <a href="index.php" class="btn btn-outline">Kembali ke Home</a>
                <a href="shop.php" class="btn btn-primary">Belanja Lagi</a>
            </div>
        </div>
    <?php else: ?>
        <h1 class="section-title">Checkout</h1>
        
        <div style="display:flex; flex-wrap:wrap; gap:40px;">
            <div style="flex:2; min-width:300px;">
                <div style="background:var(--white); padding:30px; border-radius:16px; box-shadow:var(--shadow-sm);">
                    <h3 style="margin-bottom:20px;">Informasi Pengiriman</h3>
                    <form action="checkout.php" method="POST">
                        <div class="form-group">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="customer_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nomor WhatsApp</label>
                            <input type="text" name="phone" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea name="address" class="form-control" rows="4" required></textarea>
                            <small class="text-light">Sertakan detail alamat seperti RT/RW, kelurahan, kecamatan, dan kode pos.</small>
                        </div>
                        <button type="submit" class="btn btn-primary mt-4" style="width:100%; padding:15px; font-size:1.1rem;">Selesaikan Pesanan</button>
                    </form>
                </div>
            </div>
            
            <div style="flex:1; min-width:300px;">
                <div style="background:var(--white); padding:30px; border-radius:16px; box-shadow:var(--shadow-sm); position:sticky; top:100px;">
                    <h3 style="margin-bottom:20px;">Ringkasan Order</h3>
                    
                    <?php foreach($cart_products as $product):
                        $qty = $_SESSION['cart'][$product['id']];
                    ?>
                    <div style="display:flex; justify-content:space-between; margin-bottom:15px; border-bottom:1px solid #eee; padding-bottom:10px;">
                        <div>
                            <div style="font-weight:500; font-size:0.95rem;"><?php echo htmlspecialchars($product['name']); ?></div>
                            <small class="text-light"><?php echo $qty; ?> x Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></small>
                        </div>
                        <div style="font-weight:600;">
                            Rp <?php echo number_format($product['price'] * $qty, 0, ',', '.'); ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    
                    <div style="display:flex; justify-content:space-between; margin-top:20px; font-size:1.2rem; font-weight:bold; color:var(--primary-color);">
                        <span>Total:</span>
                        <span>Rp <?php echo number_format($total_price, 0, ',', '.'); ?></span>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
