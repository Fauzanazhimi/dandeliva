<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
require_once 'config/database.php';

// Handle Wellness Log (Improved with Mood & Rewards)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['log_gummy'])) {
    $qty = (int)$_POST['gummy_qty'];
    $mood = $_POST['mood'] ?? 'Biasa Saja';
    $date = date('Y-m-d');
    $user_id = $_SESSION['user_id'];
    
    // 1. Log the consumption with Mood
    $stmt = $pdo->prepare("INSERT INTO gummy_logs (user_id, log_date, consumed_qty, mood) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE consumed_qty = consumed_qty + ?, mood = ?");
    $stmt->execute([$user_id, $date, $qty, $mood, $qty, $mood]);
    
    // 2. Decrease Stock
    $pdo->prepare("UPDATE users SET gummy_stock = GREATEST(0, gummy_stock - ?) WHERE id = ?")->execute([$qty, $user_id]);
    
    // 3. Reward System: Completed daily target (2 gummies)
    $check = $pdo->prepare("SELECT consumed_qty, points_earned FROM gummy_logs WHERE user_id = ? AND log_date = ?");
    $check->execute([$user_id, $date]);
    $log = $check->fetch();
    
    if ($log && $log['consumed_qty'] >= 2 && !$log['points_earned']) {
        // Award 15 Glow Points (increased for better engagement)
        $pdo->prepare("UPDATE users SET points = points + 15 WHERE id = ?")->execute([$user_id]);
        $pdo->prepare("UPDATE gummy_logs SET points_earned = 1 WHERE user_id = ? AND log_date = ?")->execute([$user_id, $date]);
        $msg = "gummy_saved&reward=1";
    } else {
        $msg = "gummy_saved";
    }
    
    header("Location: wellness.php?msg=$msg");
    exit;
}

// Handle Refill Stock
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['refill_gummy'])) {
    $pdo->prepare("UPDATE users SET gummy_stock = 60 WHERE id = ?")->execute([$_SESSION['user_id']]);
    header("Location: wellness.php?msg=refill_done");
    exit;
}

// Handle Health Log (Upgraded for Innovations)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['log_health'])) {
    $start_date = !empty($_POST['cycle_date']) ? $_POST['cycle_date'] : date('Y-m-d');
    $duration = !empty($_POST['duration']) ? (int)$_POST['duration'] : 5;
    $cycle_length = !empty($_POST['cycle_length']) ? (int)$_POST['cycle_length'] : 28;
    $symptoms = isset($_POST['symptoms']) ? implode(', ', $_POST['symptoms']) : '';
    
    $stmt = $pdo->prepare("INSERT INTO period_logs (user_id, start_date, duration, cycle_length, symptoms) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $start_date, $duration, $cycle_length, $symptoms]);
    
    header("Location: wellness.php?msg=health_saved&tab=health");
    exit;
}

// Handle Community Post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['log_community'])) {
    $content = trim($_POST['content']);
    if (!empty($content)) {
        $stmt = $pdo->prepare("INSERT INTO community_posts (user_id, content) VALUES (?, ?)");
        $stmt->execute([$_SESSION['user_id'], $content]);
    }
    header("Location: wellness.php?msg=community_saved&tab=community");
    exit;
}

// Handle Consultation Post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['log_consultation'])) {
    $message = trim($_POST['message']);
    $age = (int)$_POST['age'];
    $condition_status = $_POST['condition_status'];
    if (!empty($message)) {
        $stmt = $pdo->prepare("INSERT INTO consultations (user_id, message, age, condition_status) VALUES (?, ?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $message, $age, $condition_status]);
    }
    header("Location: wellness.php?msg=consultation_saved&tab=consultation");
    exit;
}

// Handle Community Comment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['log_comment'])) {
    $content = trim($_POST['content']);
    $post_id = (int)$_POST['post_id'];
    if (!empty($content)) {
        $stmt = $pdo->prepare("INSERT INTO community_comments (post_id, user_id, content) VALUES (?, ?, ?)");
        $stmt->execute([$post_id, $_SESSION['user_id'], $content]);
    }
    header("Location: wellness.php?tab=community");
    exit;
}

// Handle Community Like
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['log_like'])) {
    $post_id = (int)$_POST['post_id'];
    $stmt = $pdo->prepare("SELECT id FROM community_likes WHERE post_id = ? AND user_id = ?");
    $stmt->execute([$post_id, $_SESSION['user_id']]);
    if ($stmt->fetch()) {
        $stmt = $pdo->prepare("DELETE FROM community_likes WHERE post_id = ? AND user_id = ?");
        $stmt->execute([$post_id, $_SESSION['user_id']]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO community_likes (post_id, user_id) VALUES (?, ?)");
        $stmt->execute([$post_id, $_SESSION['user_id']]);
    }
    header("Location: wellness.php?tab=community");
    exit;
}

include 'includes/header.php'; 

// Fetch current user stats
$today = date('Y-m-d');
$gummy_today = $pdo->prepare("SELECT consumed_qty FROM gummy_logs WHERE user_id = ? AND log_date = ?");
$gummy_today->execute([$_SESSION['user_id'], $today]);
$gummy_res = $gummy_today->fetch();
$gummy_count = $gummy_res ? $gummy_res['consumed_qty'] : 0;
// Max 2 gummies per day for bar
$gummy_percent = min(100, ($gummy_count / 2) * 100);

// Fetch weekly gummy stats
$weekly_stats = [];
for($i=6; $i>=0; $i--) {
    $d = date('Y-m-d', strtotime("-$i days"));
    $st = $pdo->prepare("SELECT consumed_qty FROM gummy_logs WHERE user_id = ? AND log_date = ?");
    $st->execute([$_SESSION['user_id'], $d]);
    $res = $st->fetch();
    $weekly_stats[] = [
        'day' => date('D', strtotime($d)),
        'qty' => $res ? $res['consumed_qty'] : 0
    ];
}

// Fetch User Rewards, Stock & Preferences
$user_info = $pdo->prepare("SELECT points, gummy_stock, reminder_time, ritual_goal FROM users WHERE id = ?");
$user_info->execute([$_SESSION['user_id']]);
$user_data = $user_info->fetch();
$points = $user_data['points'] ?? 0;
$stock = $user_data['gummy_stock'] ?? 0;
$p_time = $user_data['reminder_time'] ?? '08:00';
$p_goal = $user_data['ritual_goal'] ?? 'Menjaga Mood';

// Fetch Today's Consumption Timeline
$timeline = $pdo->prepare("SELECT created_at, mood FROM gummy_logs WHERE user_id = ? AND log_date = ? ORDER BY created_at ASC");
$timeline->execute([$_SESSION['user_id'], $today]);
$consumed_times = $timeline->fetchAll(PDO::FETCH_ASSOC);

// Prediction Logic for Female Health
$last_period = $pdo->prepare("SELECT * FROM period_logs WHERE user_id = ? ORDER BY start_date DESC LIMIT 1");
$last_period->execute([$_SESSION['user_id']]);
$record = $last_period->fetch();

$prediction = null;
if ($record) {
    $last_start = strtotime($record['start_date']);
    $cycle_len = $record['cycle_length'] ?: 28;
    $next_period = $last_start + ($cycle_len * 86400);
    $ovulation_start = $next_period - (14 * 86400);
    
    $prediction = [
        'next_date' => date('d M Y', $next_period),
        'ovulation' => date('d M Y', $ovulation_start),
        'days_away' => round(($next_period - time()) / 86400)
    ];
}
?>

<!-- Wellness Hub Hero -->
<section class="wellness-hero" style="background: linear-gradient(135deg, #E8F5E9 0%, #F9FBE7 100%); padding: 60px 0; overflow: hidden; position: relative;">
    <div class="container" style="display: flex; align-items: center; gap: 40px; flex-wrap: wrap;">
        <div class="hero-content" style="flex: 1; min-width: 300px;">
            <div style="display: flex; gap: 15px; margin-bottom: 20px; align-items: center; flex-wrap: wrap;">
                <div style="display: inline-flex; align-items: center; background: rgba(58, 125, 68, 0.1); padding: 8px 16px; border-radius: 50px; color: var(--primary-color); font-weight: 600;">
                    <i class="fas fa-heartbeat" style="margin-right: 8px;"></i> Dandeliva Care
                </div>
                <a href="logout.php" class="btn btn-outline" style="padding: 6px 15px; font-size: 0.85rem; border-color: #e74c3c; color: #e74c3c;"><i class="fas fa-sign-out-alt"></i> Keluar</a>
            </div>
            <h1 class="hero-title" style="color: var(--primary-color); font-size: 2.8rem; line-height: 1.2; margin-bottom: 20px;">Halo, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Pengguna'); ?>! <br>Pusat Kesejahteraan Wanita</h1>
            <p class="hero-tagline" style="color: #666; font-size: 1.1rem; line-height: 1.6; margin-bottom: 0;">Lacak konsumsi harian Anda, pantau siklus kewanitaan, dapatkan edukasi pola hidup sehat, konsultasi ringan, dan terhubung dengan komunitas Dandeliva.</p>
        </div>
        <div class="hero-image" style="flex: 1; min-width: 300px; text-align: center;">
            <img src="assets/images/wellness_hero.png" alt="Wellness Dashboard" style="max-width: 100%; border-radius: 20px; box-shadow: 0 15px 35px rgba(58, 125, 68, 0.15);">
        </div>
    </div>
</section>

<!-- Wellness Dashboard Area -->
<section class="wellness-dashboard section-padding" style="background-color: #fafafa;">
    <div class="container">
        <div class="dashboard-wrapper">
            
            <!-- Sidebar Navigation -->
            <aside class="dashboard-sidebar">
                <?php if(isset($_GET['msg'])): ?>
                    <?php if($_GET['msg'] == 'gummy_saved' && isset($_GET['reward'])): ?>
                        <div style="background: linear-gradient(45deg, #FFD700, #FFA500); color: white; padding: 15px; border-radius: 12px; margin-bottom: 20px; box-shadow: 0 4px 15px rgba(255, 165, 0, 0.3); text-align: center; animation: pulse 2s infinite;">
                            <i class="fas fa-crown"></i> <strong>Hebat!</strong> Target tercapai. <br>+10 Glow Points ditambahkan!
                        </div>
                    <?php elseif($_GET['msg'] == 'refill_done'): ?>
                        <div style="background:#e3f2fd; color:#1976d2; padding:10px; border-radius:8px; margin-bottom:15px; font-size:0.9rem;">
                            <i class="fas fa-box-open"></i> Stok gummy berhasil diperbarui (60 butir).
                        </div>
                    <?php else: ?>
                        <div style="background:#e8f5e9; color:#2e7d32; padding:10px; border-radius:8px; margin-bottom:15px; font-size:0.9rem;">
                            <i class="fas fa-check-circle"></i> Data berhasil disimpan.
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
                <ul class="dashboard-menu">
                    <li class="<?php echo (!isset($_GET['tab']) || ($_GET['tab'] != 'health' && $_GET['tab'] != 'community')) ? 'active' : ''; ?>" data-tab="tab-gummy-tracker">
                        <i class="fas fa-leaf"></i> Wellness Journey
                    </li>
                    <li class="<?php echo (isset($_GET['tab']) && $_GET['tab'] == 'health') ? 'active' : ''; ?>" data-tab="tab-health-tracker">
                        <i class="fas fa-venus"></i> Catatan Wanita
                    </li>
                    <li data-tab="tab-consultation">
                        <i class="fas fa-user-md"></i> Konsultasi
                    </li>
                    <li class="<?php echo (isset($_GET['tab']) && $_GET['tab'] == 'community') ? 'active' : ''; ?>" data-tab="tab-community">
                        <i class="fas fa-comments"></i> Komunitas
                    </li>
                </ul>
            </aside>

            <!-- Dashboard Content -->
            <div class="dashboard-content">

                <!-- 1. Gummy Wellness Journey Tab -->
                <div class="dashboard-tab <?php echo (!isset($_GET['tab']) || ($_GET['tab'] != 'health' && $_GET['tab'] != 'community')) ? 'active' : ''; ?>" id="tab-gummy-tracker">
                    <div class="tab-header">
                        <h2>Dandeliva Wellness Journey</h2>
                        <p>Track asupan camilan fungsional harian dan pantau perkembangan merasa sehat Anda (Mood Tracker).</p>
                    </div>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
                        <!-- Consumption Today -->
                        <div class="tracker-card" style="margin-bottom: 0; position: relative; overflow: hidden; border-left: 5px solid var(--primary-color);">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                                <div class="tracker-date"><?php echo date('l, d M'); ?></div>
                                <div style="font-size: 0.8rem; color: #888;"><i class="fas fa-history"></i> Log Hari Ini</div>
                            </div>
                            
                            <!-- Timeline Checklist -->
                            <div class="ritual-timeline" style="margin: 20px 0;">
                                <?php if(empty($consumed_times)): ?>
                                    <div style="text-align: center; padding: 20px; background: #fbfbfb; border-radius: 12px; border: 1px dashed #eee;">
                                        <p style="font-size: 0.85rem; color: #999;">Belum ada momen tercatat hari ini.</p>
                                    </div>
                                <?php else: ?>
                                    <div style="display: flex; flex-direction: column; gap: 10px;">
                                        <?php foreach($consumed_times as $idx => $item): ?>
                                            <div style="display: flex; align-items: center; gap: 12px; background: #f0f7f1; padding: 10px 15px; border-radius: 10px;">
                                                <div style="width: 24px; height: 24px; background: var(--primary-color); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.7rem;">
                                                    <i class="fas fa-check"></i>
                                                </div>
                                                <div style="flex: 1;">
                                                    <div style="font-weight: 600; font-size: 0.9rem;">Asupan ke-<?php echo $idx+1; ?></div>
                                                    <div style="font-size: 0.75rem; color: #666;"><?php echo date('H:i', strtotime($item['created_at'])); ?> • Mood: <?php echo htmlspecialchars($item['mood'] ?? 'Biasa Saja'); ?></div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="progress-bar-container" style="height: 12px; background: #eee; border-radius: 10px; margin: 20px 0;">
                                <div class="progress-bar" style="width: <?php echo $gummy_percent; ?>%; height: 100%; background: linear-gradient(90deg, var(--primary-color), #8bc34a); border-radius: 10px; transition: width 0.5s ease-out;"></div>
                            </div>
                            
                            <div class="tracker-actions" style="margin-top: 25px; display: flex; flex-direction: column; gap: 15px;">
                                <form method="POST" action="">
                                    <input type="hidden" name="log_gummy" value="1">
                                    <input type="hidden" name="gummy_qty" value="1">
                                    
                                    <label style="display: block; font-size: 0.8rem; color: #777; margin-bottom: 8px;">Apa perasaanmu saat ini?</label>
                                    <div style="display: flex; gap: 10px; margin-bottom: 15px; flex-wrap: wrap;">
                                        <label class="mood-selector">
                                            <input type="radio" name="mood" value="😊 Senang" checked>
                                            <span class="mood-icon">😊</span>
                                        </label>
                                        <label class="mood-selector">
                                            <input type="radio" name="mood" value="😴 Lelah">
                                            <span class="mood-icon">😴</span>
                                        </label>
                                        <label class="mood-selector">
                                            <input type="radio" name="mood" value="🤯 Stres">
                                            <span class="mood-icon">🤯</span>
                                        </label>
                                        <label class="mood-selector">
                                            <input type="radio" name="mood" value="🤩 Bersemangat">
                                            <span class="mood-icon">🤩</span>
                                        </label>
                                    </div>
                                    
                                    <div style="display: flex; gap: 10px;">
                                        <button type="submit" class="btn btn-primary" style="flex: 2; padding: 12px; font-weight: 700;"><i class="fas fa-plus"></i> Log Gummy</button>
                                        <button type="button" class="btn btn-outline" style="flex: 1; padding: 12px;" onclick="document.getElementById('reminderModal').style.display='flex'">
                                            <i class="fas fa-cog"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Stats & Insights -->
                        <div class="tracker-card" style="margin-bottom: 0; background: linear-gradient(135deg, #ffffff 0%, #f9f9f9 100%); border-left: 5px solid #FFD700;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px;">
                                <div>
                                    <h4 style="margin: 0; color: #444; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px;">Status Anda</h4>
                                </div>
                                <div style="background: #FFF9C4; color: #FBC02D; padding: 4px 8px; border-radius: 6px; font-size: 0.75rem; font-weight: 700;">
                                    PREMIUM CARE
                                </div>
                            </div>

                            <div style="display: flex; gap: 20px; align-items: center; margin-bottom: 20px;">
                                <div style="flex: 1; text-align: center; background: white; padding: 15px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
                                    <div style="color: #FFD700; font-size: 1.5rem; margin-bottom: 5px;"><i class="fas fa-coins"></i></div>
                                    <div style="font-size: 1.3rem; font-weight: 700; color: var(--primary-color);"><?php echo $points; ?></div>
                                    <div style="font-size: 0.75rem; color: #888;">Glow Points</div>
                                </div>
                                <div style="flex: 1; text-align: center; background: white; padding: 15px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
                                    <div style="color: #64B5F6; font-size: 1.5rem; margin-bottom: 5px;"><i class="fas fa-cookie"></i></div>
                                    <div style="font-size: 1.3rem; font-weight: 700; color: <?php echo $stock < 10 ? '#e74c3c' : '#444'; ?>;"><?php echo $stock; ?></div>
                                    <div style="font-size: 0.75rem; color: #888;">Sisa Gummy</div>
                                </div>
                            </div>

                            <div style="background: white; border-radius: 10px; padding: 12px; border: 1px solid #eee;">
                                <div style="font-size: 0.75rem; color: #888; margin-bottom: 4px;">PENGINGAT SEHAT</div>
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <span style="font-weight: 600; color: #444; font-size: 0.9rem;"><i class="far fa-bell" style="color:#FBC02D;"></i> Pukul <?php echo date('H:i', strtotime($p_time)); ?></span>
                                    <span style="font-size: 0.75rem; background: #f0f0f0; padding: 2px 8px; border-radius: 4px;"><?php echo $p_goal; ?></span>
                                </div>
                            </div>

                            <?php if($stock < 10): ?>
                                <div style="background: #FFEBEE; color: #C62828; padding: 10px; border-radius: 8px; font-size: 0.8rem; margin-bottom: 15px;">
                                    <i class="fas fa-exclamation-triangle"></i> Stok menipis! Pesan lagi agar rutin terpenuhi.
                                </div>
                            <?php endif; ?>

                            <form method="POST" action="">
                                <input type="hidden" name="refill_gummy" value="1">
                                <button type="submit" class="btn btn-outline" style="width: 100%; font-size: 0.85rem; padding: 8px;"><i class="fas fa-sync-alt"></i> Buka Botol Baru (60 Butir)</button>
                            </form>
                        </div>
                    </div>

                    <!-- Personalized Smart Tip -->
                    <div style="margin-top: 25px; background: #E8F5E9; border-radius: 16px; padding: 20px; display: flex; gap: 20px; align-items: center; border: 1px dashed var(--primary-color);">
                        <div style="width: 60px; height: 60px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: var(--primary-color); box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                            <i class="fas fa-lightbulb"></i>
                        </div>
                        <div>
                            <h4 style="margin: 0; color: var(--primary-color); font-size: 1rem;">Mengapa Mencatat Mood?</h4>
                            <p style="margin: 5px 0 0; font-size: 0.9rem; color: #555;">
                                Dandeliva membantu menyeimbangkan hormon dan mood. Dengan mencatat perasaanmu setiap makan gummy, kamu bisa melihat pola kesehatan mentalmu secara mingguan.
                            </p>
                        </div>
                    </div>
                    
                    <h3 style="margin-top: 40px; margin-bottom: 20px;">Riwayat Konsumsi 7 Hari Terakhir</h3>
                    <div class="weekly-stats" style="display: flex; gap: 10px; justify-content: space-between;">
                        <?php 
                        foreach($weekly_stats as $stat): 
                            $h = min(100, ($stat['qty']/2*100)); // max height
                        ?>
                        <div class="day-stat" style="text-align: center; flex: 1;">
                            <div class="day-bar" style="height: 100px; background: #eee; border-radius: 8px; position: relative; overflow: hidden;">
                                <div style="position: absolute; bottom: 0; left: 0; width: 100%; height: <?php echo $h; ?>%; background: var(--primary-color); transition: height 1s ease;"></div>
                            </div>
                            <div style="margin-top: 10px; font-weight: 500; font-size: 0.9rem;">
                                <?php echo $stat['day']; ?><br>
                                <small style="color:#888;"><?php echo $stat['qty']; ?></small>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- 2. Health Tracker Tab -->
                <div class="dashboard-tab <?php echo (isset($_GET['tab']) && $_GET['tab'] == 'health') ? 'active' : ''; ?>" id="tab-health-tracker">
                    <div class="tab-header">
                        <h2>Tracker Kesehatan Wanita</h2>
                        <p>Pantau siklus bulanan dan kenali kondisi tubuh Anda lebih baik.</p>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px;">
                        
                        <!-- Input Log -->
                        <div class="tracker-card" style="padding: 25px;">
                            <form method="POST" action="">
                                <input type="hidden" name="log_health" value="1">
                                <h3 style="margin-bottom:20px; color:var(--primary-color);"><i class="fas fa-edit"></i> Catat Siklus Baru</h3>
                                
                                <div class="form-group">
                                    <label class="form-label" style="font-size:0.85rem;">Tanggal Mulai Haid</label>
                                    <input type="date" name="cycle_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                                
                                <div style="display:flex; gap:15px; margin-bottom:20px;">
                                    <div style="flex:1;">
                                        <label class="form-label" style="font-size:0.85rem;">Durasi (Hari)</label>
                                        <input type="number" name="duration" class="form-control" value="5" min="1" max="14">
                                    </div>
                                    <div style="flex:1;">
                                        <label class="form-label" style="font-size:0.85rem;">Siklus (Hari)</label>
                                        <input type="number" name="cycle_length" class="form-control" value="28" min="20" max="45">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" style="font-size:0.85rem;">Gejala yang Dirasakan</label>
                                    <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                                        <?php $symps = ['Kram', 'Mood Swing', 'Pusing', 'Lelah', 'Jerawat']; 
                                        foreach($symps as $s): ?>
                                            <label style="background:#f5f5f5; padding:4px 12px; border-radius:15px; font-size:0.8rem; cursor:pointer; border:1px solid #eee;">
                                                <input type="checkbox" name="symptoms[]" value="<?php echo $s; ?>"> <?php echo $s; ?>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary" style="width:100%; margin-top:10px;">Simpan & Prediksi</button>
                            </form>
                        </div>

                        <!-- Prediction Panel -->
                        <div class="tracker-card" style="background: linear-gradient(to bottom, #fff, #fff5f8); border: 2px solid #ffebf0; padding: 25px;">
                            <h3 style="margin-bottom:20px; color:#d81b60;"><i class="fas fa-magic"></i> AI Health Prediction</h3>
                            
                            <?php if($prediction): ?>
                                <div style="text-align:center; margin-bottom:25px;">
                                    <div style="font-size:0.9rem; color:#888; margin-bottom:5px;">Estimasi Haid Berikutnya</div>
                                    <div style="font-size:1.8rem; font-weight:700; color:#d81b60; font-family:'Outfit';"><?php echo $prediction['next_date']; ?></div>
                                    <div style="display:inline-block; padding:4px 15px; background:#ffd1dc; color:#d81b60; border-radius:20px; font-size:0.8rem; font-weight:600; margin-top:10px;">
                                        <?php 
                                            echo $prediction['days_away'] > 0 
                                            ? "Sekitar ".$prediction['days_away']." Hari Lagi" 
                                            : "Sedang Berlangsung / Terlambat"; 
                                        ?>
                                    </div>
                                </div>

                                <div style="background:white; border-radius:12px; padding:15px; border:1px solid #ffebf0; margin-bottom:15px;">
                                    <div style="display:flex; justify-content:space-between; align-items:center;">
                                        <span style="font-size:0.9rem; color:#444;"><i class="fas fa-seedling" style="color:#2ecc71;"></i> Masa Subur (Ovulasi)</span>
                                        <span style="font-weight:600; color:#444;"><?php echo $prediction['ovulation']; ?></span>
                                    </div>
                                </div>

                                <div style="font-size:0.85rem; color:#666; font-style:italic;">
                                    <i class="fas fa-info-circle"></i> Catatan: Prediksi ini bersifat estimasi berdasarkan data rata-rata siklus Anda. Jaga pola makan dan istirahat yang cukup.
                                </div>
                            <?php else: ?>
                                <div style="text-align:center; padding:40px 20px;">
                                    <i class="fas fa-calendar-times" style="font-size:3rem; color:#ddd; margin-bottom:15px;"></i>
                                    <p style="color:#888;">Belum ada data siklus. Silakan catat haid terakhir Anda untuk memulai prediksi otomatis.</p>
                                </div>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>
                </div>

                <!-- 4. Consultation Tab -->
                <div class="dashboard-tab <?php echo (isset($_GET['tab']) && $_GET['tab'] == 'consultation') ? 'active' : ''; ?>" id="tab-consultation">
                    <div class="tab-header">
                        <h2>Konsultasi Ringan</h2>
                        <p>Dapatkan rekomendasi pola hidup atau tanyakan kebingungan Anda kepada AI & tim ahli kesehatan Dandeliva.</p>
                    </div>

                    <div style="background: white; padding: 30px; border-radius: 16px; box-shadow: var(--shadow-sm);">
                        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'consultation_saved'): ?>
                            <div class="alert" style="background:var(--success); color:white; padding:15px; border-radius:8px; margin-bottom:20px; transition:opacity 0.3s ease;">
                                <i class="fas fa-check-circle"></i> Pertanyaan telah dikirimkan! Rekomendasi akan dikirimkan ke email/No HP Anda.
                            </div>
                        <?php endif; ?>
                        <form method="POST" action="">
                            <input type="hidden" name="log_consultation" value="1">
                            <div class="form-group">
                                <label class="form-label">Apa yang ingin Anda konsultasikan/keluhkan hari ini?</label>
                                <textarea name="message" class="form-control" rows="4" placeholder="Contoh: Saya sering merasa lelah di sore hari dan jadwal haid saya sering mundur..." required></textarea>
                            </div>
                            <div class="form-group" style="display: flex; gap: 20px; flex-wrap: wrap;">
                                <div style="flex: 1; min-width: 200px;">
                                    <label class="form-label">Usia</label>
                                    <input type="number" name="age" class="form-control" placeholder="Tahun" required>
                                </div>
                                <div style="flex: 1; min-width: 200px;">
                                    <label class="form-label">Kondisi Saat Ini</label>
                                    <select name="condition_status" class="form-control">
                                        <option value="Normal/Sehat">Normal/Sehat</option>
                                        <option value="Sedang Promil">Sedang Promil</option>
                                        <option value="Menyusui">Menyusui</option>
                                        <option value="Penyembuhan Pasca Sakit">Penyembuhan Pasca Sakit</option>
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary" style="width: 100%;">Minta Rekomendasi Terpersonalisasi <i class="fas fa-paper-plane" style="margin-left:8px;"></i></button>
                        </form>
                    </div>
                </div>

                <!-- 5. Community Tab -->
                <div class="dashboard-tab <?php echo (isset($_GET['tab']) && $_GET['tab'] == 'community') ? 'active' : ''; ?>" id="tab-community">
                    <div class="tab-header">
                        <h2>Komunitas Dandelivers</h2>
                        <p>Forum diskusi anonim & ruang berbagi inspirasi antar wanita pengguna Dandeliva.</p>
                    </div>

                    <form method="POST" action="" style="margin-bottom: 20px;">
                        <input type="hidden" name="log_community" value="1">
                        <textarea name="content" class="form-control" rows="3" placeholder="Bagikan progress pencapaian kesehatanmu, atau tanyakan pengalaman pengguna lain hari ini..." required></textarea>
                        <div style="text-align: right; margin-top: 10px;">
                            <button type="submit" class="btn btn-primary" style="padding: 8px 16px; font-size: 0.9rem;">Posting Cerita <i class="fas fa-commenting"></i></button>
                        </div>
                    </form>

                    <div class="forum-feed" style="display: flex; flex-direction: column; gap: 15px;">
                        <?php
                        // Fetch posts and user names
                        $posts = $pdo->query("SELECT cp.*, u.name FROM community_posts cp LEFT JOIN users u ON cp.user_id = u.id ORDER BY cp.id DESC")->fetchAll(PDO::FETCH_ASSOC);
                        foreach($posts as $post):
                            $isNutritionist = (strpos($post['content'], 'mengingatkan kembali') !== false || $post['user_id'] == null); // Basic mock for nutritionist badge if system user
                            if(strpos($post['content'], 'Halo semua') !== false) {
                                $isNutritionist = false; // Mock override
                            }
                            $authorName = $post['name'] ? $post['name'] : 'Pengguna Anonim #'.rand(100, 999);
                            if ($post['user_id'] == null && strpos($post['content'], 'Sore bunda') !== false) {
                                $authorName = "Dandeliva Nutritionist <i class='fas fa-check-circle'></i>";
                                $isNutritionist = true;
                            }
                        ?>
                        <?php
                            // Like count
                            $l_stmt = $pdo->prepare("SELECT COUNT(*) FROM community_likes WHERE post_id = ?");
                            $l_stmt->execute([$post['id']]);
                            $likeCount = $l_stmt->fetchColumn();

                            // Check if I liked
                            $il_stmt = $pdo->prepare("SELECT id FROM community_likes WHERE post_id = ? AND user_id = ?");
                            $il_stmt->execute([$post['id'], $_SESSION['user_id']]);
                            $iLiked = $il_stmt->fetch();

                            // Comments
                            $c_stmt = $pdo->prepare("SELECT cc.*, u.name FROM community_comments cc LEFT JOIN users u ON cc.user_id = u.id WHERE cc.post_id = ? ORDER BY cc.id ASC");
                            $c_stmt->execute([$post['id']]);
                            $comments = $c_stmt->fetchAll(PDO::FETCH_ASSOC);
                            $commentCount = count($comments);
                        ?>
                        <div class="forum-post" style="background: white; padding: 20px; border-radius: 12px; box-shadow: var(--shadow-sm);">
                            <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 10px;">
                                <div style="width: 40px; height: 40px; border-radius: 50%; background: <?php echo $isNutritionist ? 'var(--primary-light)' : '#e0e0e0'; ?>; display: flex; align-items: center; justify-content: center;"><i class="fas <?php echo $isNutritionist ? 'fa-star' : 'fa-user'; ?>" style="color:#fff;"></i></div>
                                <div>
                                    <h5 style="margin: 0; <?php echo $isNutritionist ? 'color: var(--primary-color);' : ''; ?>"><?php echo $authorName; ?></h5>
                                    <span style="font-size: 0.8rem; color: #888;"><?php echo date('d M Y, H:i', strtotime($post['created_at'])); ?></span>
                                </div>
                            </div>
                            <p style="font-size: 0.95rem; margin-bottom: 15px;"><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                            <div style="border-top: 1px solid #eee; padding-top: 10px; display: flex; gap: 15px; font-size: 0.9rem; color: #666;">
                                <form method="POST" action="" style="display:inline;">
                                    <input type="hidden" name="log_like" value="1">
                                    <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                                    <button type="submit" style="background:none; border:none; color: <?php echo $iLiked ? 'var(--primary-color)' : '#666'; ?>; cursor:pointer; font-size:0.9rem;">
                                        <i class="<?php echo $iLiked ? 'fas' : 'far'; ?> fa-heart"></i> <?php echo $likeCount; ?> Suka
                                    </button>
                                </form>
                                <span style="cursor:pointer;" onclick="document.getElementById('comments_<?php echo $post['id']; ?>').style.display = document.getElementById('comments_<?php echo $post['id']; ?>').style.display == 'none' ? 'block' : 'none';"><i class="far fa-comment"></i> <?php echo $commentCount; ?> Balasan</span>
                            </div>

                            <!-- Comments Section -->
                            <div id="comments_<?php echo $post['id']; ?>" style="display: <?php echo $commentCount > 0 ? 'block' : 'none'; ?>; margin-top: 15px; background: #fafafa; padding: 15px; border-radius: 8px;">
                                <?php foreach($comments as $comment): 
                                    $commentAuthor = $comment['name'] ? $comment['name'] : 'Pengguna Anonim';
                                ?>
                                    <div style="margin-bottom: 10px; border-bottom: 1px solid #eee; padding-bottom: 10px;">
                                        <strong><?php echo htmlspecialchars($commentAuthor); ?></strong> <small style="color:#888;"><?php echo date('H:i', strtotime($comment['created_at'])); ?></small>
                                        <div style="font-size: 0.9rem; margin-top: 4px;"><?php echo nl2br(htmlspecialchars($comment['content'])); ?></div>
                                    </div>
                                <?php endforeach; ?>

                                <!-- Reply Form -->
                                <form method="POST" action="" style="margin-top: 10px; display: flex; gap: 10px;">
                                    <input type="hidden" name="log_comment" value="1">
                                    <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                                    <input type="text" name="content" class="form-control" placeholder="Tulis balasan..." style="padding: 8px; font-size: 0.9rem; height: auto;" required>
                                    <button type="submit" class="btn btn-primary" style="padding: 8px 15px;"><i class="fas fa-paper-plane"></i></button>
                                </form>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<!-- Reminder Setting Modal -->
<div id="reminderModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; width: 95%; max-width: 400px; padding: 30px; border-radius: 20px; position: relative; animation: fadeIn 0.3s ease;">
        <button onclick="document.getElementById('reminderModal').style.display='none'" style="position: absolute; top: 15px; right: 15px; border: none; background: none; font-size: 1.2rem; cursor: pointer; color: #999;"><i class="fas fa-times"></i></button>
        <h3 style="margin-bottom: 10px; color: var(--primary-color);"><i class="fas fa-bell"></i> Pengingat Sehat</h3>
        <p style="font-size: 0.9rem; color: #666; margin-bottom: 25px;">Atur waktu notifikasi agar Anda tidak lupa menikmati asupan nutrisi harian.</p>
        
        <form method="POST" action="">
            <input type="hidden" name="set_reminder" value="1">
            <div class="form-group">
                <label class="form-label">Waktu Notifikasi</label>
                <input type="time" name="reminder_time" class="form-control" value="<?php echo $p_time; ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Tujuan Utama Anda</label>
                <select name="ritual_goal" class="form-control">
                    <option value="Detoks Tubuh" <?php echo $p_goal == 'Detoks Tubuh' ? 'selected' : ''; ?>>Detoks Tubuh</option>
                    <option value="Menjaga Mood" <?php echo $p_goal == 'Menjaga Mood' ? 'selected' : ''; ?>>Menjaga Mood</option>
                    <option value="Kesehatan Kulit" <?php echo $p_goal == 'Kesehatan Kulit' ? 'selected' : ''; ?>>Kesehatan Kulit</option>
                </select>
            </div>
            <p style="font-size: 0.75rem; color: #888; font-style: italic; margin-bottom: 20px;">*Tentu saja, Anda bebas menikmati Dandeliva kapan saja sesuai keinginan.</p>
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px;">Simpan Pengaturan</button>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<script>
// Tab Switching Logic
document.querySelectorAll('.dashboard-menu li').forEach(item => {
    item.addEventListener('click', () => {
        // Remove active from all menu items
        document.querySelectorAll('.dashboard-menu li').forEach(li => li.classList.remove('active'));
        // Add active to clicked item
        item.classList.add('active');
        
        // Hide all tabs
        document.querySelectorAll('.dashboard-tab').forEach(tab => tab.classList.remove('active'));
        // Show target tab
        const tabId = item.getAttribute('data-tab');
        document.getElementById(tabId).classList.add('active');
        
        // Update URL without refreshing (optional but nice)
        const tabName = tabId.replace('tab-', '');
        history.replaceState(null, '', `wellness.php?tab=${tabName}`);
    });
});

function toggleTagUI(checkbox) {
    let label = checkbox.parentElement;
    if (checkbox.checked) {
        label.style.background = 'var(--primary-color)';
        label.style.color = 'white';
    } else {
        label.style.background = '#f5f5f5';
        label.style.color = 'inherit';
    }
}
</script>
