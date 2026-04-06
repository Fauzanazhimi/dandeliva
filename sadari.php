<?php 
require_once 'config/database.php';
include 'includes/header.php'; 
?>

<!-- SADARI Hero -->
<section class="sadari-hero" style="background-color: #FFF0F5; padding: 80px 0; overflow: hidden; position: relative;">
    <div class="container" style="display: flex; align-items: center; gap: 40px; flex-wrap: wrap;">
        <div class="hero-content" style="flex: 1; min-width: 300px;">
            <div style="display: inline-flex; align-items: center; background: rgba(232, 62, 140, 0.1); padding: 8px 16px; border-radius: 50px; color: #e83e8c; font-weight: 600; margin-bottom: 20px;">
                <i class="fas fa-ribbon" style="margin-right: 8px;"></i> Bentuk Kepedulian Dandeliva
            </div>
            <h1 class="hero-title" style="color: #d81b60; font-size: 3rem; line-height: 1.2; margin-bottom: 20px;">Deteksi Dini adalah Perlindungan Terbaik</h1>
            <p class="hero-tagline" style="color: #666; font-size: 1.1rem; line-height: 1.6; margin-bottom: 30px;">Kanker payudara bisa dicegah penyebarannya dengan deteksi dini. Pelajari panduan 6 Langkah SADARI (Periksa Payudara Sendiri) yang bisa Anda lakukan di rumah, hanya butuh 5 menit setiap bulannya.</p>
            <a href="#sadari-steps" class="btn btn-primary" style="background-color: #e83e8c; border: none; padding: 15px 30px; font-size: 1.1rem;">Mulai Panduan <i class="fas fa-arrow-down" style="margin-left: 8px;"></i></a>
        </div>
        <div class="hero-image" style="flex: 1; min-width: 300px; text-align: center;">
            <img src="assets/images/sadari_hero.png" alt="Ilustrasi Edukasi SADARI" style="max-width: 100%; border-radius: 20px; box-shadow: 0 15px 35px rgba(232, 62, 140, 0.15);">
        </div>
    </div>
</section>

<!-- SADARI Steps / Screening Guide -->
<section id="sadari-steps" class="section-padding" style="background-color: #fafafa;">
    <div class="container">
        <div class="text-center mb-4">
            <h2 class="section-title" style="color: #3A7D44; margin-bottom: 10px;">6 Langkah Mudah SADARI</h2>
            <p style="color: #666; max-width: 600px; margin: 0 auto 50px;">Lakukan pemeriksaan ini 7-10 hari setelah hari pertama menstruasi Anda, saat payudara tidak terlalu sensitif atau bengkak.</p>
        </div>

        <div class="steps-container" style="max-width: 900px; margin: 0 auto;">
            <!-- Step 1 -->
            <div class="sadari-step" style="display: flex; background: #fff; padding: 30px; border-radius: 16px; margin-bottom: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); align-items: center; gap: 30px; flex-wrap: wrap;">
                <div class="step-number" style="background: #e83e8c; color: white; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 1.5rem; font-weight: bold; flex-shrink: 0;">1</div>
                <div class="step-info" style="flex: 1; min-width: 250px;">
                    <h3 style="color: #333; margin-bottom: 10px;">Berdiri di Depan Cermin</h3>
                    <p style="color: #666;">Buka pakaian dada. Perhatikan payudara Anda lurus di depan cermin dengan kedua lengan di sisi tubuh. Amati perubahan bentuk, ukuran, warna kulit, atau jika ada kerutan dan cekungan.</p>
                </div>
                <div class="step-icon" style="font-size: 4rem; color: #ffb6c1; flex-shrink: 0; text-align: center; width: 80px;">
                    <i class="fas fa-portrait"></i>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="sadari-step" style="display: flex; background: #fff; padding: 30px; border-radius: 16px; margin-bottom: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); align-items: center; gap: 30px; flex-wrap: wrap;">
                <div class="step-number" style="background: #e83e8c; color: white; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 1.5rem; font-weight: bold; flex-shrink: 0;">2</div>
                <div class="step-info" style="flex: 1; min-width: 250px;">
                    <h3 style="color: #333; margin-bottom: 10px;">Angkat Kedua Lengan</h3>
                    <p style="color: #666;">Angkat kedua tangan Anda tinggi di atas kepala. Amati lagi payudara Anda dan cari apakah ada perubahan, terutama di bagian bawah payudara.</p>
                </div>
                <div class="step-icon" style="font-size: 4rem; color: #ffb6c1; flex-shrink: 0; text-align: center; width: 80px;">
                    <i class="fas fa-child"></i>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="sadari-step" style="display: flex; background: #fff; padding: 30px; border-radius: 16px; margin-bottom: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); align-items: center; gap: 30px; flex-wrap: wrap;">
                <div class="step-number" style="background: #e83e8c; color: white; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 1.5rem; font-weight: bold; flex-shrink: 0;">3</div>
                <div class="step-info" style="flex: 1; min-width: 250px;">
                    <h3 style="color: #333; margin-bottom: 10px;">Letakkan Tangan di Pinggang</h3>
                    <p style="color: #666;">Tekan kuat-kuat tangan Anda ke pinggul untuk mengencangkan otot dada. Perhatikan lagi dengan cermat adanya kejanggalan atau asimetri yang tidak biasa.</p>
                </div>
                <div class="step-icon" style="font-size: 4rem; color: #ffb6c1; flex-shrink: 0; text-align: center; width: 80px;">
                    <i class="fas fa-street-view"></i>
                </div>
            </div>

            <!-- Step 4 -->
            <div class="sadari-step" style="display: flex; background: #fff; padding: 30px; border-radius: 16px; margin-bottom: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); align-items: center; gap: 30px; flex-wrap: wrap;">
                <div class="step-number" style="background: #e83e8c; color: white; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 1.5rem; font-weight: bold; flex-shrink: 0;">4</div>
                <div class="step-info" style="flex: 1; min-width: 250px;">
                    <h3 style="color: #333; margin-bottom: 10px;">Tekan Puting Lembut</h3>
                    <p style="color: #666;">Gunakan ibu jari dan telunjuk untuk menekan puting susu secara perlahan. Perhatikan apakah keluar cairan yang tidak normal (putih kekuningan, bening, atau darah).</p>
                </div>
                <div class="step-icon" style="font-size: 4rem; color: #ffb6c1; flex-shrink: 0; text-align: center; width: 80px;">
                    <i class="fas fa-hand-holding-water"></i>
                </div>
            </div>

            <!-- Step 5 -->
            <div class="sadari-step" style="display: flex; background: #fff; padding: 30px; border-radius: 16px; margin-bottom: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); align-items: center; gap: 30px; flex-wrap: wrap;">
                <div class="step-number" style="background: #e83e8c; color: white; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 1.5rem; font-weight: bold; flex-shrink: 0;">5</div>
                <div class="step-info" style="flex: 1; min-width: 250px;">
                    <h3 style="color: #333; margin-bottom: 10px;">Teknik Perabaan (Berbaring)</h3>
                    <p style="color: #666;">Berbaring dengan bantal di bawah pundak kanan dan tangan kanan di belakang kepala. Gunakan 3 jari tengah kiri untuk meraba payudara kanan dengan gerakan memutar dari luar ke dalam puting. Rasakan apakah ada benjolan. Ulangi pada sisi sebelah.</p>
                </div>
                <div class="step-icon" style="font-size: 4rem; color: #ffb6c1; flex-shrink: 0; text-align: center; width: 80px;">
                    <i class="fas fa-bed"></i>
                </div>
            </div>

            <!-- Step 6 -->
            <div class="sadari-step" style="display: flex; background: #fff; padding: 30px; border-radius: 16px; margin-bottom: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); align-items: center; gap: 30px; flex-wrap: wrap;">
                <div class="step-number" style="background: #e83e8c; color: white; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 1.5rem; font-weight: bold; flex-shrink: 0;">6</div>
                <div class="step-info" style="flex: 1; min-width: 250px;">
                    <h3 style="color: #333; margin-bottom: 10px;">Teknik Perabaan (Mandi)</h3>
                    <p style="color: #666;">Langkah yang sama (meraba) juga sangat baik dilakukan saat mandi, karena sabun/air membantu kulit menjadi lebih licin sehingga benjolan kecil lebih mudah terasa di bawah permukaan kulit.</p>
                </div>
                <div class="step-icon" style="font-size: 4rem; color: #ffb6c1; flex-shrink: 0; text-align: center; width: 80px;">
                    <i class="fas fa-shower"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Warning & Disclaimer-->
<section class="section-padding" style="background-color: #fff; text-align: center;">
    <div class="container">
        <div style="background: #fff8eb; border: 1px solid #ffe8cc; padding: 40px; border-radius: 16px; max-width: 800px; margin: 0 auto;">
            <i class="fas fa-exclamation-triangle" style="font-size: 2.5rem; color: #ffb020; margin-bottom: 15px;"></i>
            <h3 style="color: #333; margin-bottom: 15px;">Kapan Harus ke Dokter?</h3>
            <p style="color: #555; margin-bottom: 20px;">Jika Anda menemukan benjolan (sekecil apapun), perubahan tekstur kulit seperti kulit jeruk, puting masuk ke dalam, nyeri terus-menerus, atau keluar cairan cairan mencurigakan dari puting, <strong>segera konsultasikan ke dokter kandungan atau bedah onkologi.</strong></p>
            <p style="font-size: 0.9rem; color: #888; font-style: italic;">*Halaman ini hanya bersifat edukasi informatif. Dandeliva Gummy mendukung kesadaran wanita modern akan kesehatannya secara menyeluruh.</p>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
