<?php
session_start();
require_once 'config/database.php';

if(isset($_SESSION['user_id'])){
    header("Location: wellness.php");
    exit;
}

$error = '';
$success = '';

// Handle Login
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if($user && password_verify($password, $user['password'])){
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];

        // Load persisted cart from DB
        $stmt_cart = $pdo->prepare("SELECT product_id, quantity FROM cart WHERE user_id = ?");
        $stmt_cart->execute([$user['id']]);
        $db_items = $stmt_cart->fetchAll(PDO::FETCH_ASSOC);
        
        if ($db_items) {
            $_SESSION['cart'] = [];
            foreach ($db_items as $item) {
                $_SESSION['cart'][$item['product_id']] = $item['quantity'];
            }
        }

        header("Location: wellness.php");
        exit;
    } else {
        $error = "Email atau password salah!";
    }
}

// Handle Registration
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if($stmt->fetch()){
        $error = "Email sudah terdaftar!";
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)");
        if($stmt->execute([$name, $email, $phone, $password])){
            $success = "Registrasi berhasil! Silakan login di bawah.";
        } else {
            $error = "Terjadi kesalahan sistem, silakan coba lagi.";
        }
    }
}

include 'includes/header.php';
?>

<section class="section-padding" style="background-color: #fafafa; min-height: 80vh; display: flex; align-items: center; justify-content: center;">
    <div class="container" style="max-width: 500px;">
        <div style="background: white; padding: 40px; border-radius: 16px; box-shadow: var(--shadow-sm);">
            
            <div style="text-align: center; margin-bottom: 30px;">
                <h2 style="color: var(--primary-color);">Masuk ke Dandeliva</h2>
                <p style="color: #666;">Akses Care Hub dan fitur terpersonalisasi lainnya.</p>
            </div>

            <?php if($error): ?>
                <div style="background: #ffebee; color: #c62828; padding: 10px 15px; border-radius: 8px; margin-bottom: 20px;">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <?php if($success): ?>
                <div style="background: #e8f5e9; color: #2e7d32; padding: 10px 15px; border-radius: 8px; margin-bottom: 20px;">
                    <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <div class="auth-tabs" style="display: flex; border-bottom: 2px solid #eee; margin-bottom: 25px;">
                <div class="auth-tab active" id="tab-btn-login" style="flex: 1; text-align: center; padding: 10px; cursor: pointer; border-bottom: 2px solid var(--primary-color); color: var(--primary-color); font-weight: 600;" onclick="switchTab('login')">Masuk</div>
                <div class="auth-tab" id="tab-btn-register" style="flex: 1; text-align: center; padding: 10px; cursor: pointer; color: #888; font-weight: 600;" onclick="switchTab('register')">Daftar Baru</div>
            </div>

            <!-- Login Form -->
            <form id="form-login" method="POST" action="">
                <input type="hidden" name="login" value="1">
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px;">Masuk</button>
            </form>

            <!-- Register Form -->
            <form id="form-register" method="POST" action="" style="display: none;">
                <input type="hidden" name="register" value="1">
                <div class="form-group">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Nomor WhatsApp</label>
                    <input type="text" name="phone" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required minlength="6">
                </div>
                <button type="submit" class="btn btn-outline" style="width: 100%; margin-top: 10px;">Daftar Sekarang</button>
            </form>

        </div>
    </div>
</section>

<script>
function switchTab(tab) {
    if(tab === 'login') {
        document.getElementById('form-login').style.display = 'block';
        document.getElementById('form-register').style.display = 'none';
        document.getElementById('tab-btn-login').style.borderBottom = '2px solid var(--primary-color)';
        document.getElementById('tab-btn-login').style.color = 'var(--primary-color)';
        document.getElementById('tab-btn-register').style.borderBottom = 'none';
        document.getElementById('tab-btn-register').style.color = '#888';
    } else {
        document.getElementById('form-login').style.display = 'none';
        document.getElementById('form-register').style.display = 'block';
        document.getElementById('tab-btn-register').style.borderBottom = '2px solid var(--primary-color)';
        document.getElementById('tab-btn-register').style.color = 'var(--primary-color)';
        document.getElementById('tab-btn-login').style.borderBottom = 'none';
        document.getElementById('tab-btn-login').style.color = '#888';
    }
}
</script>

<?php include 'includes/footer.php'; ?>
