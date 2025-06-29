<?php
include 'includes/auth.php';
include 'includes/db.php';

$id = $_GET['id'];
$stmt = $db->prepare("SELECT * FROM barang WHERE id = ?");
$stmt->execute([$id]);
$barang = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $kode = $_POST['kode'];
    $stock = $_POST['stock'];

    $update = $db->prepare("UPDATE barang SET nama = ?, kode = ?, stock = ? WHERE id = ?");
    $update->execute([$nama, $kode, $stock, $id]);
    header("Location: dashboard.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit Barang</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="content" align="center">
    <h2>Edit Barang</h2>
    <form method="post">
        <input name="nama" value="<?= $barang['nama'] ?>" required><br>
        <input name="kode" value="<?= $barang['kode'] ?>" required><br>
        <input name="stock" type="number" value="<?= $barang['stock'] ?>" required><br>
        <button type="submit">Simpan Perubahan</button>
    </form>
    <a href="dashboard.php">Kembali</a>
</div>
<?php include 'template/sidebar.php'; ?>
</body>
</html>
