<?php 
require_once 'config/database.php';
include 'includes/header.php'; 
?>

<div class="hero" style="min-height:40vh; background:url('https://images.unsplash.com/photo-1514995669114-6081e934b693?auto=format&fit=crop&q=80&w=1200') center/cover; position:relative;">
    <div style="position:absolute; top:0; left:0; width:100%; height:100%; background:rgba(58, 125, 68, 0.8);"></div>
    <div class="container text-center" style="position:relative; z-index:1;">
        <h1 style="color:var(--white); font-size:3rem; margin-bottom:15px;">Cerita Dandeliva</h1>
        <p style="color:var(--white); font-size:1.2rem; opacity:0.9; max-width:600px; margin:0 auto;">Inovasi pangan fungsional herbal yang merevolusi cara wanita modern menjaga kesehatan.</p>
    </div>
</div>

<div class="container section-padding">
    <div class="edu-content" style="margin-bottom:80px;">
        <div class="edu-text">
            <h2 class="section-title" style="text-align:left;">Visi Kami</h2>
            <p>Menjadi pelopor produk pangan fungsional herbal di Indonesia yang mengedukasi masyarakat, khususnya wanita, untuk menerapkan gaya hidup sehat dengan memanfaatkan kekayaan alam lokal secara inovatif, praktis, dan modern.</p>
            
            <h2 class="section-title" style="text-align:left; margin-top:40px;">Misi Kami</h2>
            <ul style="color:var(--text-light); padding-left:20px; line-height:1.8;">
                <li>Menghasilkan herbal gummy berkualitas tinggi dengan standar kebersihan dan keamanan maksimal.</li>
                <li>Meningkatkan kesadaran masyarakat akan pentingnya pangan fungsional melalui ragam edukasi yang mudah dipahami.</li>
                <li>Berkolaborasi dengan ahli gizi dan formulator herbal dalam memastikan manfaat setiap produk secara klinis.</li>
                <li>Menciptakan ekosistem gaya hidup sehat yang ramah, modern, dan mudah dijangkau semua kalangan.</li>
            </ul>
        </div>
        <div class="edu-image">
            <img src="https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&q=80&w=600" alt="Visi Misi Dandeliva" style="width:100%; border-radius:16px; box-shadow:var(--shadow-lg);">
        </div>
    </div>

    <div class="edu-content">
        <div class="edu-image" style="order: -1;">
            <img src="https://images.unsplash.com/photo-1611078489935-0cb964de46d6?auto=format&fit=crop&q=80&w=600" alt="Inovasi Produk" style="width:100%; border-radius:16px; box-shadow:var(--shadow-lg);">
        </div>
        <div class="edu-text">
            <h2 class="section-title" style="text-align:left;">Inovasi Produk & Fokus Edukasi</h2>
            <p>Berawal dari kepedulian terhadap tingginya aktivitas wanita modern yang kerap kali melupakan pentingnya asupan herbal, Dandeliva hadir. Kami mengubah stigma bahwa mengonsumsi jamu atau herbal itu pahit dan merepotkan menjadi sebuah pengalaman baru: makan gummy yang lezat, kenyal, sekaligus menyehatkan.</p>
            <p>Selain fokus pada penjualan, kami secara aktif merilis artikel, infografis, dan video edukasi untuk memastikan konsumen kami bukan hanya sekedar pembeli, melainkan menjadi komunitas yang cerdas serta paham betul tentang nutrisi yang masuk ke dalam tubuh mereka.</p>
            <a href="shop.php" class="btn btn-primary mt-4">Jelajahi Produk Kami</a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
