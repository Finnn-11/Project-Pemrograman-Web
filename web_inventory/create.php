<?php
include 'includes/auth.php';
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $kode = $_POST['kode'];
    $stock = $_POST['stock'];
    $tanggal = date('Y-m-d');

    // Cek apakah barang sudah ada
    $cek = $db->prepare("SELECT * FROM barang WHERE nama = ? AND kode = ?");
    $cek->execute([$nama, $kode]);
    $existing = $cek->fetch();

    if ($existing) {
        $update = $db->prepare("UPDATE barang SET stock = stock + ? WHERE nama = ? AND kode = ?");
        $update->execute([$stock, $nama, $kode]);
    } else {
        $stmt = $db->prepare("INSERT INTO barang (nama, kode, stock) VALUES (?, ?, ?)");
        $stmt->execute([$nama, $kode, $stock]);
    }

    // Simpan riwayat
    $riwayat = $db->prepare("INSERT INTO barang_masuk (tanggal, nama, kode, jumlah) VALUES (?, ?, ?, ?)");
    $riwayat->execute([$tanggal, $nama, $kode, $stock]);
}

$riwayatData = $db->query("SELECT * FROM barang_masuk ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tambah Barang</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .riwayat table {
            width: 80%;
            max-width: 800px;
            margin: 0 auto;
        }
    </style>
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

<div class="riwayat" align="center">
    <h2>Riwayat Barang Masuk</h2>
    <table>
        <tr>
            <th>Tanggal</th>
            <th>Nama Barang</th>
            <th>Kode Barang</th>
            <th>Jumlah Masuk</th>
        </tr>
        <?php foreach ($riwayatData as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['tanggal']) ?></td>
            <td><?= htmlspecialchars($row['nama']) ?></td>
            <td><?= htmlspecialchars($row['kode']) ?></td>
            <td><?= htmlspecialchars($row['jumlah']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
<?php include 'template/sidebar.php'; ?>
</body>
</html>