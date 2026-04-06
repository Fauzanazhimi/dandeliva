<?php 
require_once 'config/database.php';
include 'includes/header.php'; 

// Fetch articles
$stmt = $pdo->query("SELECT * FROM articles ORDER BY id DESC");
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="hero" style="min-height:40vh; background:var(--primary-color); color:var(--white);">
    <div class="container text-center">
        <h1 style="color:var(--white); font-size:3rem; margin-bottom:15px;">Pusat Edukasi Dandeliva</h1>
        <p style="font-size:1.2rem; opacity:0.9; max-width:600px; margin:0 auto;">Temukan berbagai artikel menarik seputar kesehatan wanita, tanaman herbal, dan gaya hidup sehat modern.</p>
    </div>
</div>

<div class="container section-padding">
    <div class="articles-grid">
        <?php foreach($articles as $article): ?>
        <div class="article-card">
            <?php 
            $img = $article['image'];
            if($img == 'default_article.jpg' || empty($img)) {
                $imgSrc = 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&q=80&w=400';
            } else {
                $imgSrc = 'assets/images/'.$img;
            }
            $date = date('d M Y', strtotime($article['created_at']));
            ?>
            <img src="<?php echo $imgSrc; ?>" alt="<?php echo htmlspecialchars($article['title']); ?>" class="article-img">
            <div class="article-content">
                <div class="article-date"><i class="far fa-calendar-alt"></i> <?php echo $date; ?></div>
                <h3 class="article-title"><?php echo htmlspecialchars($article['title']); ?></h3>
                <p class="article-excerpt"><?php echo htmlspecialchars(substr($article['content'], 0, 120)) . '...'; ?></p>
                <a href="#" class="btn btn-outline" style="padding:8px 15px; font-size:0.9rem;">Baca Selengkapnya</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <?php if(empty($articles)): ?>
    <div class="text-center">
        <p>Belum ada artikel edukasi saat ini.</p>
    </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
