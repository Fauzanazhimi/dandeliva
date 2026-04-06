<?php 
require_once 'config/database.php';
include 'includes/header.php'; 

// Fetch all products
$stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container section-padding">
    <h1 class="section-title">Produk Dandeliva</h1>
    <p class="text-center mb-4 text-light" style="max-width:600px; margin:0 auto 50px;">Pilih varian Dandeliva yang sesuai dengan kebutuhan kesehatan Anda. Semua produk diformulasikan khusus untuk memberikan manfaat maksimal.</p>

    <div class="products-grid">
        <?php foreach($products as $product): ?>
        <div class="product-card">
            <?php 
            $img = $product['image'];
            if($img == 'default_product.jpg' || empty($img)) {
                $imgSrc = 'https://images.unsplash.com/photo-1584308666744-24d5e4a8b7dd?auto=format&fit=crop&q=80&w=400';
            } else {
                $imgSrc = 'assets/images/'.$img;
            }
            ?>
            <img src="<?php echo $imgSrc; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-img">
            <div class="product-info">
                <h3 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h3>
                <div class="product-price">Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></div>
                <p class="product-desc"><?php echo htmlspecialchars(substr($product['description'], 0, 100)) . '...'; ?></p>
                <div class="product-actions" style="margin-top:auto;">
                    <a href="product.php?id=<?php echo $product['id']; ?>" class="btn btn-outline" style="text-align:center;">Detail</a>
                    <form action="cart.php" method="POST" style="flex:1;">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <input type="hidden" name="qty" value="1">
                        <button type="submit" class="btn btn-primary" style="width:100%;">Add to Cart</button>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <?php if(empty($products)): ?>
    <div class="text-center">
        <p>Belum ada produk saat ini.</p>
    </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
