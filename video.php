<?php 
require_once 'config/database.php';
include 'includes/header.php'; 

// Fetch videos
$stmt = $pdo->query("SELECT * FROM videos ORDER BY id DESC");
$videos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container section-padding">
    <div class="text-center mb-4">
        <h1 class="section-title">Video Edukasi Kesehatan</h1>
        <p class="text-light" style="max-width:600px; margin:0 auto 40px;">Kumpulan video inspiratif dan edukatif seputar manfaat tanaman herbal dan panduan gaya hidup sehat dari Dandeliva.</p>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(350px, 1fr)); gap:40px;">
        <?php foreach($videos as $video): ?>
        <div style="background:var(--white); border-radius:16px; overflow:hidden; box-shadow:var(--shadow-md);">
            <div class="video-container" style="border-radius:16px 16px 0 0; box-shadow:none;">
                <?php
                // Enhance youtube link parsing to support short links and various formats
                $link = $video['youtube_link'];
                $embed_url = $link;
                if (preg_match('/youtu\.be\/([a-zA-Z0-9_\-]+)/i', $link, $matches)) {
                    $embed_url = 'https://www.youtube.com/embed/' . $matches[1];
                } elseif (preg_match('/youtube\.com.*(?:\?v=|\/v\/|\/embed\/)([a-zA-Z0-9_\-]+)/i', $link, $matches)) {
                    $embed_url = 'https://www.youtube.com/embed/' . $matches[1];
                } elseif (preg_match('/shorts\/([a-zA-Z0-9_\-]+)/i', $link, $matches)) {
                    $embed_url = 'https://www.youtube.com/embed/' . $matches[1];
                }
                ?>
                <iframe src="<?php echo htmlspecialchars($embed_url); ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
            <div style="padding:25px;">
                <h3 style="margin-bottom:15px; font-size:1.3rem;"><?php echo htmlspecialchars($video['title']); ?></h3>
                <p style="color:var(--text-light); font-size:0.95rem; line-height:1.6;"><?php echo htmlspecialchars($video['description']); ?></p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <?php if(empty($videos)): ?>
    <div class="text-center">
        <p>Belum ada video edukasi saat ini.</p>
    </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
