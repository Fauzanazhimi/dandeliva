<?php
session_start();
require_once '../config/database.php';

// Check if logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Handle actions (add, delete logic simple)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_action'])) {
    if ($_POST['form_action'] == 'add_product') {
        $imageName = 'default_product.jpg';
        if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $imageName = time() . '_' . str_replace(' ', '_', $_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], '../assets/images/' . $imageName);
        }
        $stmt = $pdo->prepare("INSERT INTO products (name, price, stock, description, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$_POST['name'], $_POST['price'], $_POST['stock'], $_POST['description'], $imageName]);
        header("Location: dashboard.php?page=products&msg=Produk berhasil ditambahkan");
        exit;
    }
    elseif ($_POST['form_action'] == 'edit_product') {
        if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $imageName = time() . '_' . str_replace(' ', '_', $_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], '../assets/images/' . $imageName);
            $stmt = $pdo->prepare("UPDATE products SET name = ?, price = ?, stock = ?, description = ?, image = ? WHERE id = ?");
            $stmt->execute([$_POST['name'], $_POST['price'], $_POST['stock'], $_POST['description'], $imageName, $_POST['id']]);
        } else {
            $stmt = $pdo->prepare("UPDATE products SET name = ?, price = ?, stock = ?, description = ? WHERE id = ?");
            $stmt->execute([$_POST['name'], $_POST['price'], $_POST['stock'], $_POST['description'], $_POST['id']]);
        }
        header("Location: dashboard.php?page=products&msg=Produk berhasil diupdate");
        exit;
    }
    elseif ($_POST['form_action'] == 'add_article') {
        $imageName = 'default_article.jpg';
        if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $imageName = time() . '_' . str_replace(' ', '_', $_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], '../assets/images/' . $imageName);
        }
        $stmt = $pdo->prepare("INSERT INTO articles (title, content, image) VALUES (?, ?, ?)");
        $stmt->execute([$_POST['title'], $_POST['content'], $imageName]);
        header("Location: dashboard.php?page=articles&msg=Artikel berhasil ditambahkan");
        exit;
    }
    elseif ($_POST['form_action'] == 'edit_article') {
        if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $imageName = time() . '_' . str_replace(' ', '_', $_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], '../assets/images/' . $imageName);
            $stmt = $pdo->prepare("UPDATE articles SET title = ?, content = ?, image = ? WHERE id = ?");
            $stmt->execute([$_POST['title'], $_POST['content'], $imageName, $_POST['id']]);
        } else {
            $stmt = $pdo->prepare("UPDATE articles SET title = ?, content = ? WHERE id = ?");
            $stmt->execute([$_POST['title'], $_POST['content'], $_POST['id']]);
        }
        header("Location: dashboard.php?page=articles&msg=Artikel berhasil diupdate");
        exit;
    }
    elseif ($_POST['form_action'] == 'add_video') {
        $stmt = $pdo->prepare("INSERT INTO videos (title, description, youtube_link) VALUES (?, ?, ?)");
        $stmt->execute([$_POST['title'], $_POST['description'], $_POST['youtube_link']]);
        header("Location: dashboard.php?page=videos&msg=Video berhasil ditambahkan");
        exit;
    }
    elseif ($_POST['form_action'] == 'edit_video') {
        $stmt = $pdo->prepare("UPDATE videos SET title = ?, description = ?, youtube_link = ? WHERE id = ?");
        $stmt->execute([$_POST['title'], $_POST['description'], $_POST['youtube_link'], $_POST['id']]);
        header("Location: dashboard.php?page=videos&msg=Video berhasil diupdate");
        exit;
    }
    elseif ($_POST['form_action'] == 'update_order_status') {
        $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->execute([$_POST['status'], $_POST['id']]);
        header("Location: dashboard.php?page=orders&msg=Status pesanan berhasil diperbarui");
        exit;
    }
    // Note: Due to prompt requirements "Admin dapat: Tambah produk, Edit produk, Hapus produk, Tambah artikel edukasi, Tambah video", 
    // a basic CRUD is implemented below conditionally.
}

if ($action == 'delete' && isset($_GET['id']) && isset($_GET['type'])) {
    $id = (int)$_GET['id'];
    $type = $_GET['type'];
    if($type == 'product') {
        $pdo->query("DELETE FROM products WHERE id = $id");
        header("Location: dashboard.php?page=products"); exit;
    }
    if($type == 'article') {
        $pdo->query("DELETE FROM articles WHERE id = $id");
        header("Location: dashboard.php?page=articles"); exit;
    }
    if($type == 'video') {
        $pdo->query("DELETE FROM videos WHERE id = $id");
        header("Location: dashboard.php?page=videos"); exit;
    }
}

// Stats
$productCount = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$articleCount = $pdo->query("SELECT COUNT(*) FROM articles")->fetchColumn();
$orderCount = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Dandeliva</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<div class="admin-wrapper">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            Dandeliva Panel
        </div>
        <ul class="sidebar-menu">
            <li><a href="?page=dashboard" class="<?php echo $page == 'dashboard' ? 'active' : ''; ?>"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="?page=customers" class="<?php echo $page == 'customers' ? 'active' : ''; ?>"><i class="fas fa-users"></i> Monitoring Customer</a></li>
            <li><a href="?page=products" class="<?php echo $page == 'products' ? 'active' : ''; ?>"><i class="fas fa-box"></i> Kelola Produk</a></li>
            <li><a href="?page=articles" class="<?php echo $page == 'articles' ? 'active' : ''; ?>"><i class="fas fa-newspaper"></i> Kelola Artikel</a></li>
            <li><a href="?page=videos" class="<?php echo $page == 'videos' ? 'active' : ''; ?>"><i class="fas fa-video"></i> Kelola Video</a></li>
            <li><a href="?page=orders" class="<?php echo $page == 'orders' ? 'active' : ''; ?>"><i class="fas fa-shopping-cart"></i> Pesanan</a></li>
            <li><a href="?page=consultations" class="<?php echo $page == 'consultations' ? 'active' : ''; ?>"><i class="fas fa-stethoscope"></i> Konsultasi</a></li>
        </ul>
    </aside>

    <!-- Main Content -->
    <div class="content-wrapper">
        <div class="topbar">
            <div>
                <h3><i class="fas fa-bars"></i> Halo, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</h3>
            </div>
            <div>
                <a href="../index.php" target="_blank" class="btn btn-outline" style="padding:5px 15px; font-size:0.9rem;">Lihat Website</a>
                <a href="logout.php" class="btn btn-primary" style="padding:5px 15px; font-size:0.9rem; background:var(--error); border:none;">Logout</a>
            </div>
        </div>

        <div class="main-content">
            <?php if(isset($_GET['msg'])): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($_GET['msg']); ?></div>
            <?php endif; ?>

            <?php if($page == 'dashboard'): ?>
                <!-- Dashboard -->
                <h2 style="margin-bottom:20px;">Dashboard Overview</h2>
                <div class="dashboard-cards">
                    <div class="stat-card">
                        <div>
                            <h3>Total Produk</h3>
                            <div class="value"><?php echo $productCount; ?></div>
                        </div>
                        <i class="fas fa-box icon"></i>
                    </div>
                    <div class="stat-card">
                        <div>
                            <h3>Total Artikel</h3>
                            <div class="value"><?php echo $articleCount; ?></div>
                        </div>
                        <i class="fas fa-newspaper icon"></i>
                    </div>
                    <div class="stat-card">
                        <div>
                            <h3>Total Pesanan</h3>
                            <div class="value"><?php echo $orderCount; ?></div>
                        </div>
                        <i class="fas fa-shopping-cart icon"></i>
                    </div>
                </div>
                
                <div style="background:white; padding:20px; border-radius:8px; box-shadow:0 2px 4px rgba(0,0,0,0.05);">
                    <h3>Pesanan Terbaru</h3>
                    <div class="table-responsive mt-4">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Pelanggan</th>
                                    <th>Tanggal</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $orders = $pdo->query("SELECT * FROM orders ORDER BY id DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
                                foreach($orders as $order):
                                ?>
                                <tr>
                                    <td>#<?php echo $order['id']; ?></td>
                                    <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                                    <td><?php echo date('d M Y, H:i', strtotime($order['created_at'])); ?></td>
                                    <td>Rp <?php echo number_format($order['total_price'], 0, ',', '.'); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php elseif($page == 'customers'): ?>
                <!-- Customers Target Tracking -->
                <h2 style="margin-bottom:20px;">Monitoring Target & Aktivitas Customer</h2>
                <div class="dashboard-cards" style="margin-bottom:30px;">
                    <div class="stat-card">
                        <div>
                            <h3>Total Customer</h3>
                            <div class="value"><?php echo $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn(); ?></div>
                        </div>
                        <i class="fas fa-users icon"></i>
                    </div>
                </div>

                <div style="background:white; padding:20px; border-radius:8px; box-shadow:0 2px 4px rgba(0,0,0,0.05); margin-bottom: 30px;">
                    <h3>Log Konsumsi Gummy Harian</h3>
                    <div class="table-responsive mt-4">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Customer</th>
                                    <th>Konsumsi Gummy</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $glogs = $pdo->query("SELECT g.log_date, g.consumed_qty, u.name as customer_name FROM gummy_logs g JOIN users u ON g.user_id = u.id ORDER BY g.log_date DESC, g.id DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
                                foreach($glogs as $gl):
                                ?>
                                <tr>
                                    <td><?php echo date('d M Y', strtotime($gl['log_date'])); ?></td>
                                    <td><?php echo htmlspecialchars($gl['customer_name']); ?></td>
                                    <td><strong><?php echo $gl['consumed_qty']; ?></strong> / 2 pcs</td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div style="background:white; padding:20px; border-radius:8px; box-shadow:0 2px 4px rgba(0,0,0,0.05);">
                    <h3>Log Kesehatan Wanita Terbaru</h3>
                    <div class="table-responsive mt-4">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Tanggal Haid Tercatat</th>
                                    <th>Keluhan / Gejala</th>
                                    <th>Waktu Log</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $hlogs = $pdo->query("SELECT h.cycle_date, h.symptoms, h.created_at, u.name as customer_name FROM health_logs h JOIN users u ON h.user_id = u.id ORDER BY h.id DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
                                foreach($hlogs as $hl):
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($hl['customer_name']); ?></td>
                                    <td><?php echo $hl['cycle_date'] ? date('d M Y', strtotime($hl['cycle_date'])) : '-'; ?></td>
                                    <td><?php echo $hl['symptoms'] ? htmlspecialchars($hl['symptoms']) : '-'; ?></td>
                                    <td><?php echo date('d M Y, H:i', strtotime($hl['created_at'])); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            <?php elseif($page == 'products'): ?>
                <!-- Products -->
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
                    <h2>Kelola Produk</h2>
                    <button onclick="document.getElementById('addProductForm').style.display='block'" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Produk</button>
                </div>
                
                <div id="addProductForm" style="display:none; background:white; padding:30px; border-radius:8px; margin-bottom:30px; box-shadow:0 2px 4px rgba(0,0,0,0.05);">
                    <h3>Tambah Produk Baru</h3>
                    <form action="dashboard.php" method="POST" class="mt-4" enctype="multipart/form-data">
                        <input type="hidden" name="form_action" value="add_product">
                        <div class="form-group">
                            <label class="form-label">Nama Produk</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Gambar Produk</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>
                        <div style="display:flex; gap:20px;">
                            <div class="form-group" style="flex:1;">
                                <label class="form-label">Harga (Rp)</label>
                                <input type="number" name="price" class="form-control" required>
                            </div>
                            <div class="form-group" style="flex:1;">
                                <label class="form-label">Stok</label>
                                <input type="number" name="stock" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="description" class="form-control" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Produk</button>
                        <button type="button" onclick="document.getElementById('addProductForm').style.display='none'" class="btn btn-outline">Batal</button>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Produk</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $products = $pdo->query("SELECT * FROM products ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
                            foreach($products as $p):
                            ?>
                            <tr>
                                <td><?php echo $p['id']; ?></td>
                                <td><?php echo htmlspecialchars($p['name']); ?></td>
                                <td>Rp <?php echo number_format($p['price'], 0, ',', '.'); ?></td>
                                <td><?php echo $p['stock']; ?></td>
                                <td>
                                    <button class="btn" style="padding:5px 10px; background:#f39c12; color:white; font-size:0.8rem;" onclick="editProduct(<?php echo $p['id']; ?>, '<?php echo addslashes(htmlspecialchars($p['name'])); ?>', <?php echo $p['price']; ?>, <?php echo $p['stock']; ?>, '<?php echo addslashes(htmlspecialchars(str_replace(array("\r", "\n"), array('', '\n'), $p['description']))); ?>')"><i class="fas fa-edit"></i> Edit</button>
                                    <a href="dashboard.php?action=delete&type=product&id=<?php echo $p['id']; ?>" class="btn" style="padding:5px 10px; background:var(--error); color:white; font-size:0.8rem;" onclick="return confirm('Yakin hapus produk ini?');"><i class="fas fa-trash"></i> Hapus</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Edit Product Modal/Form -->
                <div id="editProductForm" style="display:none; background:white; padding:30px; border-radius:8px; margin-top:30px; box-shadow:0 2px 4px rgba(0,0,0,0.05);">
                    <h3>Edit Produk</h3>
                    <form action="dashboard.php" method="POST" class="mt-4" enctype="multipart/form-data">
                        <input type="hidden" name="form_action" value="edit_product">
                        <input type="hidden" name="id" id="edit_product_id">
                        <div class="form-group">
                            <label class="form-label">Nama Produk</label>
                            <input type="text" name="name" id="edit_product_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Update Gambar Produk</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <small style="color:#666;">Biarkan kosong jika tidak ingin mengubah gambar.</small>
                        </div>
                        <div style="display:flex; gap:20px;">
                            <div class="form-group" style="flex:1;">
                                <label class="form-label">Harga (Rp)</label>
                                <input type="number" name="price" id="edit_product_price" class="form-control" required>
                            </div>
                            <div class="form-group" style="flex:1;">
                                <label class="form-label">Stok</label>
                                <input type="number" name="stock" id="edit_product_stock" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="description" id="edit_product_description" class="form-control" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Produk</button>
                        <button type="button" onclick="document.getElementById('editProductForm').style.display='none'" class="btn btn-outline">Batal</button>
                    </form>
                </div>
                
                <script>
                function editProduct(id, name, price, stock, description) {
                    document.getElementById('edit_product_id').value = id;
                    document.getElementById('edit_product_name').value = name;
                    document.getElementById('edit_product_price').value = price;
                    document.getElementById('edit_product_stock').value = stock;
                    document.getElementById('edit_product_description').value = description.replace(/\\n/g, '\n');
                    document.getElementById('editProductForm').style.display = 'block';
                    document.getElementById('editProductForm').scrollIntoView({ behavior: 'smooth' });
                }
                </script>

            <?php elseif($page == 'articles'): ?>
                <!-- Articles -->
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
                    <h2>Kelola Artikel Edukasi</h2>
                    <button onclick="document.getElementById('addArticleForm').style.display='block'" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Artikel</button>
                </div>
                
                <div id="addArticleForm" style="display:none; background:white; padding:30px; border-radius:8px; margin-bottom:30px; box-shadow:0 2px 4px rgba(0,0,0,0.05);">
                    <h3>Tambah Artikel Baru</h3>
                    <form action="dashboard.php" method="POST" class="mt-4" enctype="multipart/form-data">
                        <input type="hidden" name="form_action" value="add_article">
                        <div class="form-group">
                            <label class="form-label">Judul Artikel</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Gambar Banner</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Konten</label>
                            <textarea name="content" class="form-control" rows="8" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Artikel</button>
                        <button type="button" onclick="document.getElementById('addArticleForm').style.display='none'" class="btn btn-outline">Batal</button>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Judul</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $arts = $pdo->query("SELECT * FROM articles ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
                            foreach($arts as $a):
                            ?>
                            <tr>
                                <td><?php echo $a['id']; ?></td>
                                <td><?php echo htmlspecialchars($a['title']); ?></td>
                                <td><?php echo date('d M Y', strtotime($a['created_at'])); ?></td>
                                <td>
                                    <button class="btn" style="padding:5px 10px; background:#f39c12; color:white; font-size:0.8rem;" onclick="editArticle(<?php echo $a['id']; ?>, '<?php echo addslashes(htmlspecialchars($a['title'])); ?>', '<?php echo addslashes(htmlspecialchars(str_replace(array("\r", "\n"), array('', '\n'), $a['content']))); ?>')"><i class="fas fa-edit"></i> Edit</button>
                                    <a href="dashboard.php?action=delete&type=article&id=<?php echo $a['id']; ?>" class="btn" style="padding:5px 10px; background:var(--error); color:white; font-size:0.8rem;" onclick="return confirm('Yakin hapus?');"><i class="fas fa-trash"></i> Hapus</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Edit Article Modal/Form -->
                <div id="editArticleForm" style="display:none; background:white; padding:30px; border-radius:8px; margin-top:30px; box-shadow:0 2px 4px rgba(0,0,0,0.05);">
                    <h3>Edit Artikel</h3>
                    <form action="dashboard.php" method="POST" class="mt-4" enctype="multipart/form-data">
                        <input type="hidden" name="form_action" value="edit_article">
                        <input type="hidden" name="id" id="edit_article_id">
                        <div class="form-group">
                            <label class="form-label">Judul Artikel</label>
                            <input type="text" name="title" id="edit_article_title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Update Gambar Banner</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <small style="color:#666;">Biarkan kosong jika tidak ingin mengubah gambar.</small>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Konten</label>
                            <textarea name="content" id="edit_article_content" class="form-control" rows="8" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Artikel</button>
                        <button type="button" onclick="document.getElementById('editArticleForm').style.display='none'" class="btn btn-outline">Batal</button>
                    </form>
                </div>
                
                <script>
                function editArticle(id, title, content) {
                    document.getElementById('edit_article_id').value = id;
                    document.getElementById('edit_article_title').value = title;
                    document.getElementById('edit_article_content').value = content.replace(/\\n/g, '\n');
                    document.getElementById('editArticleForm').style.display = 'block';
                    document.getElementById('editArticleForm').scrollIntoView({ behavior: 'smooth' });
                }
                </script>
                
            <?php elseif($page == 'videos'): ?>
                <!-- Videos -->
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
                    <h2>Kelola Video Edukasi</h2>
                    <button onclick="document.getElementById('addVideoForm').style.display='block'" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Video</button>
                </div>
                
                <div id="addVideoForm" style="display:none; background:white; padding:30px; border-radius:8px; margin-bottom:30px; box-shadow:0 2px 4px rgba(0,0,0,0.05);">
                    <h3>Tambah Video Baru</h3>
                    <form action="dashboard.php" method="POST" class="mt-4">
                        <input type="hidden" name="form_action" value="add_video">
                        <div class="form-group">
                            <label class="form-label">Judul Video</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Link YouTube (URL/Embed)</label>
                            <input type="text" name="youtube_link" class="form-control" placeholder="https://www.youtube.com/watch?v=..." required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Video</button>
                        <button type="button" onclick="document.getElementById('addVideoForm').style.display='none'" class="btn btn-outline">Batal</button>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Judul</th>
                                <th>Link YouTube</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $vids = $pdo->query("SELECT * FROM videos ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
                            foreach($vids as $v):
                            ?>
                            <tr>
                                <td><?php echo $v['id']; ?></td>
                                <td><?php echo htmlspecialchars($v['title']); ?></td>
                                <td><a href="<?php echo htmlspecialchars($v['youtube_link']); ?>" target="_blank">Lihat</a></td>
                                <td>
                                    <button class="btn" style="padding:5px 10px; background:#f39c12; color:white; font-size:0.8rem;" onclick="editVideo(<?php echo $v['id']; ?>, '<?php echo addslashes(htmlspecialchars($v['title'])); ?>', '<?php echo addslashes(htmlspecialchars($v['youtube_link'])); ?>', '<?php echo addslashes(htmlspecialchars(str_replace(array("\r", "\n"), array('', '\n'), $v['description']))); ?>')"><i class="fas fa-edit"></i> Edit</button>
                                    <a href="dashboard.php?action=delete&type=video&id=<?php echo $v['id']; ?>" class="btn" style="padding:5px 10px; background:var(--error); color:white; font-size:0.8rem;" onclick="return confirm('Yakin hapus?');"><i class="fas fa-trash"></i> Hapus</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Edit Video Modal/Form -->
                <div id="editVideoForm" style="display:none; background:white; padding:30px; border-radius:8px; margin-top:30px; box-shadow:0 2px 4px rgba(0,0,0,0.05);">
                    <h3>Edit Video</h3>
                    <form action="dashboard.php" method="POST" class="mt-4">
                        <input type="hidden" name="form_action" value="edit_video">
                        <input type="hidden" name="id" id="edit_video_id">
                        <div class="form-group">
                            <label class="form-label">Judul Video</label>
                            <input type="text" name="title" id="edit_video_title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Link YouTube (URL/Embed)</label>
                            <input type="text" name="youtube_link" id="edit_video_link" class="form-control" placeholder="https://www.youtube.com/watch?v=..." required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="description" id="edit_video_description" class="form-control" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Video</button>
                        <button type="button" onclick="document.getElementById('editVideoForm').style.display='none'" class="btn btn-outline">Batal</button>
                    </form>
                </div>

                <script>
                function editVideo(id, title, url, description) {
                    document.getElementById('edit_video_id').value = id;
                    document.getElementById('edit_video_title').value = title;
                    document.getElementById('edit_video_link').value = url;
                    document.getElementById('edit_video_description').value = description.replace(/\\n/g, '\n');
                    document.getElementById('editVideoForm').style.display = 'block';
                    document.getElementById('editVideoForm').scrollIntoView({ behavior: 'smooth' });
                }
                </script>

            <?php elseif($page == 'orders'): ?>
                <!-- Orders -->
                <h2 style="margin-bottom:20px;">Daftar Pesanan</h2>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Pelanggan</th>
                                <th>Kontak</th>
                                <th>Total Harga</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $orders = $pdo->query("SELECT * FROM orders ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
                            foreach($orders as $o):
                                $status_color = '#95a5a6';
                                if($o['status'] == 'processing') $status_color = '#3498db';
                                if($o['status'] == 'completed') $status_color = '#2ecc71';
                                if($o['status'] == 'cancelled') $status_color = '#e74c3c';
                            ?>
                            <tr>
                                <td>#<?php echo $o['id']; ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($o['customer_name']); ?></strong><br>
                                    <small><?php echo htmlspecialchars($o['address']); ?></small>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($o['phone']); ?><br>
                                    <small><?php echo htmlspecialchars($o['email']); ?></small>
                                </td>
                                <td>Rp <?php echo number_format($o['total_price'], 0, ',', '.'); ?></td>
                                <td>
                                    <span style="display:inline-block; padding:4px 10px; border-radius:15px; background:<?php echo $status_color; ?>; color:white; font-size:0.75rem; text-transform:uppercase; font-weight:600;">
                                        <?php echo $o['status'] ?: 'pending'; ?>
                                    </span>
                                </td>
                                <td><?php echo date('d M Y, H:i', strtotime($o['created_at'])); ?></td>
                                <td>
                                    <form action="dashboard.php" method="POST" style="display:flex; gap:5px; align-items:center;">
                                        <input type="hidden" name="form_action" value="update_order_status">
                                        <input type="hidden" name="id" value="<?php echo $o['id']; ?>">
                                        <select name="status" style="padding:4px; border-radius:4px; font-size:0.8rem; border:1px solid #ddd;" onchange="this.form.submit()">
                                            <option value="pending" <?php if($o['status'] == 'pending') echo 'selected'; ?>>Pending</option>
                                            <option value="processing" <?php if($o['status'] == 'processing') echo 'selected'; ?>>Proses</option>
                                            <option value="completed" <?php if($o['status'] == 'completed') echo 'selected'; ?>>Selesai</option>
                                            <option value="cancelled" <?php if($o['status'] == 'cancelled') echo 'selected'; ?>>Batal</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php elseif($page == 'consultations'): ?>
                <!-- Consultations Monitoring -->
                <h2 style="margin-bottom:20px;">Daftar Konsultasi Masuk</h2>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Customer (Usia/Kondisi)</th>
                                <th>Keluhan/Pertanyaan</th>
                                <th>Kontak Tersedia</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $consults = $pdo->query("SELECT c.*, u.name, u.email, u.phone FROM consultations c LEFT JOIN users u ON c.user_id = u.id ORDER BY c.id DESC")->fetchAll(PDO::FETCH_ASSOC);
                            foreach($consults as $c):
                            ?>
                            <tr>
                                <td><?php echo date('d M Y, H:i', strtotime($c['created_at'])); ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($c['name']); ?></strong><br>
                                    <small><?php echo htmlspecialchars($c['age']); ?> Thn - <?php echo htmlspecialchars($c['condition_status']); ?></small>
                                </td>
                                <td><?php echo nl2br(htmlspecialchars($c['message'])); ?></td>
                                <td>
                                    <?php echo htmlspecialchars($c['email']); ?><br>
                                    <small><?php echo htmlspecialchars($c['phone']); ?></small><br>
                                    <a href="mailto:<?php echo htmlspecialchars($c['email']); ?>" class="btn" style="padding:4px 8px; font-size:0.7rem; margin-top:5px; display:inline-block;"><i class="fas fa-envelope"></i> Balas Email</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<script>
    // simple fadeout for alerts
    setTimeout(function() {
        var alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            alert.style.opacity = '0';
            setTimeout(function() { alert.style.display = 'none'; }, 300);
        });
    }, 3000);
</script>

</body>
</html>
