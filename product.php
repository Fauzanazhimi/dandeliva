<?php 
require_once 'config/database.php';
include 'includes/header.php'; 

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$product) {
    echo "<div class='container section-padding'><h2 class='text-center'>Produk tidak ditemukan.</h2></div>";
    include 'includes/footer.php';
    exit;
}
?>

<div class="container section-padding">
    <div style="display: flex; flex-wrap: wrap; gap: 50px; background: var(--white); padding: 40px; border-radius: 16px; box-shadow: var(--shadow-md);">
        <div style="flex: 1; min-width: 300px;">
            <?php 
            $img = $product['image'];
            if($img == 'default_product.jpg' || empty($img)) {
                $imgSrc = 'https://images.unsplash.com/photo-1584308666744-24d5e4a8b7dd?auto=format&fit=crop&q=80&w=800';
            } else {
                $imgSrc = 'assets/images/'.$img;
            }
            ?>
            <img src="<?php echo $imgSrc; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="width: 100%; max-height: 500px; object-fit: contain; background-color: var(--bg-color); border-radius: 12px; box-shadow: var(--shadow-sm);">
        </div>
        
        <div style="flex: 1; min-width: 300px;">
            <h1 style="font-size: 2.5rem; margin-bottom: 10px;"><?php echo htmlspecialchars($product['name']); ?></h1>
            <div class="product-price" style="font-size: 2rem; margin-bottom: 20px;">Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></div>
            
            <form action="cart.php" method="POST" style="margin-bottom: 30px; display:flex; gap:15px;">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                
                <input type="number" name="qty" value="1" min="1" max="<?php echo $product['stock']; ?>" class="form-control" style="width: 80px; text-align:center;">
                
                <button type="submit" class="btn btn-outline" style="flex:1;"><i class="fas fa-shopping-cart"></i> Tambah ke Keranjang</button>
                <button type="submit" name="buy_now" value="1" class="btn btn-primary" style="flex:1;">Beli Sekarang</button>
            </form>
            
            <p style="color: var(--success); font-weight: 500; margin-bottom: 20px;">
                <i class="fas fa-check-circle"></i> Stok Tersedia: <?php echo $product['stock']; ?>
            </p>

            <div style="margin-top: 40px;">
                <h3>Deskripsi Produk</h3>
                <p style="color: var(--text-light); white-space: pre-line;"><?php echo htmlspecialchars($product['description']); ?></p>
            </div>
            
            <div style="margin-top: 30px;">
                <h3>Komposisi Utama</h3>
                <ul style="color: var(--text-light); padding-left: 20px;">
                    <li>Ekstrak Daun Dandelion Murni</li>
                    <li>Pati Gummy Alami (Pectin)</li>
                    <li>Pemanis Alami (Stevia/Madu)</li>
                    <li>Vitamin C tambahan</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
