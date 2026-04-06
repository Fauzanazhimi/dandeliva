<?php 
require_once 'config/database.php';
include 'includes/header.php'; 

$msg = "";
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic form submission without DB insert, just a message
    $msg = "<div style='background:var(--success); color:white; padding:15px; border-radius:8px; margin-bottom:20px; text-align:center;'>Pesan Anda telah berhasil dikirim! Tim kami akan segera merespon.</div>";
}
?>

<div class="container section-padding">
    <div class="text-center mb-4">
        <h1 class="section-title">Hubungi Kami</h1>
        <p class="text-light" style="max-width:600px; margin:0 auto;">Punya pertanyaan seputar produk, kolaborasi, atau ingin konsultasi kesehatan herbal? Jangan ragu untuk menghubungi tim Dandeliva.</p>
    </div>

    <?php echo $msg; ?>

    <div style="display:flex; flex-wrap:wrap; gap:40px;">
        <div style="flex:1; min-width:300px;">
            <div style="background:var(--white); padding:40px; border-radius:16px; box-shadow:var(--shadow-md);">
                <form action="contact.php" method="POST">
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" placeholder="Masukkan nama Anda" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="email@contoh.com" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Subjek</label>
                        <input type="text" name="subject" class="form-control" placeholder="Tanya produk / Kerjasama" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Pesan</label>
                        <textarea name="message" class="form-control" rows="6" placeholder="Tuliskan pesan Anda di sini..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width:100%; padding:15px; font-size:1.1rem;">Kirim Pesan <i class="fas fa-paper-plane" style="margin-left:10px;"></i></button>
                </form>
            </div>
        </div>
        
        <div style="flex:1; min-width:300px;">
            <div style="background:var(--secondary-color); padding:40px; border-radius:16px; height:100%;">
                <h3 style="margin-bottom:30px; color:var(--primary-color);">Informasi Kontak</h3>
                
                <div style="display:flex; align-items:flex-start; margin-bottom:25px; gap:20px;">
                    <div style="width:50px; height:50px; background:var(--white); border-radius:50%; display:flex; align-items:center; justify-content:center; color:var(--primary-color); font-size:1.2rem; flex-shrink:0; box-shadow:var(--shadow-sm);">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div>
                        <h4 style="margin-bottom:5px;">Email</h4>
                        <p style="color:var(--text-light);">hello@dandeliva.com</p>
                    </div>
                </div>
                
                <div style="display:flex; align-items:flex-start; margin-bottom:25px; gap:20px;">
                    <div style="width:50px; height:50px; background:var(--white); border-radius:50%; display:flex; align-items:center; justify-content:center; color:var(--primary-color); font-size:1.2rem; flex-shrink:0; box-shadow:var(--shadow-sm);">
                        <i class="fab fa-whatsapp"></i>
                    </div>
                    <div>
                        <h4 style="margin-bottom:5px;">WhatsApp</h4>
                        <p style="color:var(--text-light);">+62 812 3456 7890</p>
                        <a href="#" class="btn btn-outline mt-4" style="padding:5px 15px; font-size:0.8rem; background:white;">Chat Admin</a>
                    </div>
                </div>
                
                <div style="display:flex; align-items:flex-start; margin-bottom:25px; gap:20px;">
                    <div style="width:50px; height:50px; background:var(--white); border-radius:50%; display:flex; align-items:center; justify-content:center; color:var(--primary-color); font-size:1.2rem; flex-shrink:0; box-shadow:var(--shadow-sm);">
                        <i class="fab fa-instagram"></i>
                    </div>
                    <div>
                        <h4 style="margin-bottom:5px;">Instagram</h4>
                        <p style="color:var(--text-light);">@dandeliva.id</p>
                        <a href="#" style="color:var(--primary-color); font-weight:600; font-size:0.9rem;">Follow Kami</a>
                    </div>
                </div>
                
                <div style="display:flex; align-items:flex-start; margin-bottom:25px; gap:20px;">
                    <div style="width:50px; height:50px; background:var(--white); border-radius:50%; display:flex; align-items:center; justify-content:center; color:var(--primary-color); font-size:1.2rem; flex-shrink:0; box-shadow:var(--shadow-sm);">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div>
                        <h4 style="margin-bottom:5px;">Lokasi</h4>
                        <p style="color:var(--text-light); line-height:1.6;">Gedung Dandeliva, Lt. 3<br>Jl. Kesehatan Raya No. 12<br>Jakarta Selatan, 12190</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
