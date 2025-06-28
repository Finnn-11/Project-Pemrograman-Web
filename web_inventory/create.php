<?php
include 'includes/auth.php';
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $kode = $_POST['kode'];
    $stock = (int)$_POST['stock'];

    $stmt = $db->prepare("SELECT * FROM barang WHERE nama = ? AND kode = ?");
    $stmt->execute([$nama, $kode]);
    $existing = $stmt->fetch();

    if ($existing) {
        $update = $db->prepare("UPDATE barang SET stock = stock + ? WHERE id = ?");
        $update->execute([$stock, $existing['id']]);
    } else {
        $insert = $db->prepare("INSERT INTO barang (nama, kode, stock) VALUES (?, ?, ?)");
        $insert->execute([$nama, $kode, $stock]);
    }
    
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tambah Barang</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="content" align="center">
    <h2>Tambah Barang</h2>
    <form method="post">
        <input name="nama" placeholder="Nama Barang" required><br>
        <input name="kode" placeholder="Kode Barang" required><br>
        <input name="stock" type="number" placeholder="Stock" required><br>
        <button type="submit">Simpan</button>
    </form>
    <a href="dashboard.php">Kembali</a>
</div>
<?php include 'template/sidebar.php'; ?>
</body>
</html>
