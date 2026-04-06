<?php 
require_once 'config/database.php';
session_start();

// Handle cart logic BEFORE outputting any HTML header
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $product_id = (int)$_POST['product_id'];
        $qty = (int)$_POST['qty'];
        $buy_now = isset($_POST['buy_now']) ? true : false;
        
        if ($qty > 0) {
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }
            if (isset($_SESSION['cart'][$product_id])) {
                $_SESSION['cart'][$product_id] += $qty;
            } else {
                $_SESSION['cart'][$product_id] = $qty;
            }

            // Sync to DB if logged in
            if (isset($_SESSION['user_id'])) {
                $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)");
                $stmt->execute([$_SESSION['user_id'], $product_id, $qty]);
            }
        }
        
        if ($buy_now) {
            header("Location: checkout.php");
            exit;
        } else {
            header("Location: cart.php");
            exit;
        }
    } 
    elseif ($action === 'update') {
        foreach ($_POST['qty'] as $id => $qty) {
            if ($qty <= 0) {
                unset($_SESSION['cart'][$id]);
                if (isset($_SESSION['user_id'])) {
                    $pdo->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?")->execute([$_SESSION['user_id'], $id]);
                }
            } else {
                $_SESSION['cart'][$id] = (int)$qty;
                if (isset($_SESSION['user_id'])) {
                    $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE quantity = VALUES(quantity)")->execute([$_SESSION['user_id'], $id, $qty]);
                }
            }
        }
        header("Location: cart.php");
        exit;
    } 
    elseif ($action === 'remove') {
        $product_id = (int)$_POST['product_id'];
        if (isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
        }
        if (isset($_SESSION['user_id'])) {
            $pdo->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?")->execute([$_SESSION['user_id'], $product_id]);
        }
        header("Location: cart.php");
        exit;
    }
}

// If logged in, ensure session cart matches DB
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT product_id, quantity FROM cart WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Reset session cart to DB state but only if there's data in DB
    // actually, we should just populate session from DB
    // This allows recovery after logout/login
    if ($items) {
        $db_cart = [];
        foreach ($items as $item) {
            $db_cart[$item['product_id']] = $item['quantity'];
        }
        $_SESSION['cart'] = $db_cart;
    }
}

// Below is the cart UI display
include 'includes/header.php'; 
?>

<div class="container section-padding">
    <h1 class="section-title">Keranjang Belanja</h1>

    <?php if(empty($_SESSION['cart'])): ?>
        <div class="text-center" style="background: var(--white); padding: 50px; border-radius: 16px; box-shadow: var(--shadow-sm);">
            <i class="fas fa-shopping-basket" style="font-size: 4rem; color: #ccc; margin-bottom: 20px;"></i>
            <h3>Keranjang Anda kosong</h3>
            <p class="text-light mb-4">Mari temukan produk kesehatan herbal terbaik untuk Anda.</p>
            <a href="shop.php" class="btn btn-primary">Mulai Belanja</a>
        </div>
    <?php else: ?>
        <form action="cart.php" method="POST">
            <input type="hidden" name="action" value="update">
            
            <div style="display:flex; flex-wrap:wrap; gap:30px;">
                <div style="flex:2; min-width:300px; background:var(--white); border-radius:16px; box-shadow:var(--shadow-md); padding:20px; overflow-x:auto;">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total_price = 0;
                            // Fetch all products in cart
                            $ids = implode(',', array_keys($_SESSION['cart']));
                            $stmt = $pdo->query("SELECT * FROM products WHERE id IN ($ids)");
                            $cart_products = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            foreach($cart_products as $product):
                                $qty = $_SESSION['cart'][$product['id']];
                                $subtotal = $product['price'] * $qty;
                                $total_price += $subtotal;
                                
                                $img = $product['image'];
                                if($img == 'default_product.jpg' || empty($img)) {
                                    $imgSrc = 'https://images.unsplash.com/photo-1584308666744-24d5e4a8b7dd?auto=format&fit=crop&q=80&w=150';
                                } else {
                                    $imgSrc = 'assets/images/products/'.$img;
                                }
                            ?>
                            <tr>
                                <td data-label="Produk">
                                    <div style="display:flex; align-items:center;">
                                        <img src="<?php echo $imgSrc; ?>" class="cart-item-img" alt="Produk">
                                        <?php echo htmlspecialchars($product['name']); ?>
                                    </div>
                                </td>
                                <td data-label="Harga">Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></td>
                                <td data-label="Jumlah">
                                    <input type="number" name="qty[<?php echo $product['id']; ?>]" value="<?php echo $qty; ?>" min="1" max="<?php echo $product['stock']; ?>" class="qty-input">
                                </td>
                                <td data-label="Subtotal">Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></td>
                                <td data-label="Aksi">
                                    <button form="remove-form-<?php echo $product['id']; ?>" class="btn" style="background:var(--error); color:white; padding: 5px 10px; font-size: 0.8rem;"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    
                    <div style="text-align:right;">
                        <button type="submit" class="btn btn-outline" style="padding: 8px 15px;">Update Keranjang</button>
                    </div>
                </div>
                
                <div style="flex:1; min-width:300px;">
                    <div class="cart-summary">
                        <h3>Ringkasan Belanja</h3>
                        <div style="display:flex; justify-content:space-between; margin-bottom:15px; color:var(--text-light);">
                            <span>Total Harga Produk</span>
                            <span>Rp <?php echo number_format($total_price, 0, ',', '.'); ?></span>
                        </div>
                        <hr style="border:none; border-top:1px solid var(--border-color); margin:15px 0;">
                        <div style="display:flex; justify-content:space-between; align-items:center;">
                            <strong>Total Bayar</strong>
                            <div class="total-price" style="margin:0;">Rp <?php echo number_format($total_price, 0, ',', '.'); ?></div>
                        </div>
                        
                        <a href="checkout.php" class="btn btn-primary" style="width:100%; margin-top:30px;">Lanjut ke Checkout</a>
                        <a href="shop.php" class="btn btn-outline" style="width:100%; margin-top:15px; border:none;">Lanjut Belanja</a>
                    </div>
                </div>
            </div>
        </form>
        
        <!-- Hidden forms for removal -->
        <?php foreach($cart_products as $product): ?>
        <form id="remove-form-<?php echo $product['id']; ?>" action="cart.php" method="POST" style="display:none;">
            <input type="hidden" name="action" value="remove">
            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
        </form>
        <?php endforeach; ?>

    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
