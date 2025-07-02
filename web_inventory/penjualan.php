<?php
include 'includes/auth.php';
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kode = $_POST['kode'];
    $jumlah = (int)$_POST['jumlah'];

    $stmtNama = $db->prepare("SELECT nama FROM barang WHERE kode = ?");
    $stmtNama->execute([$kode]);
    $barang = $stmtNama->fetch();
    $nama = $barang ? $barang['nama'] : 'Tidak ditemukan';

    $stmt = $db->prepare("INSERT INTO penjualan (nama, kode, jumlah) VALUES (?, ?, ?)");
    $stmt->execute([$nama, $kode, $jumlah]);

    $updateStok = $db->prepare("UPDATE barang SET stock = stock - ? WHERE kode = ?");
    $updateStok->execute([$jumlah, $kode]);
}

$data = $db->query("SELECT * FROM penjualan ORDER BY ROWID DESC")->fetchAll();
$avg = $db->query("SELECT p.nama, p.kode, AVG(p.jumlah) AS rata_jumlah, IFNULL(b.stock, 0) AS stok 
                   FROM penjualan p
                   LEFT JOIN barang b ON p.kode = b.kode
                   GROUP BY p.nama, p.kode")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Penjualan</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <style>
        .scroll-table-container {
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th, table td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: center;
        }
        table th {
            background-color: #00bbbb;
            color: white;
            position: sticky;
            top: 0;
        }
    </style>
</head>
<body>
<div class="content" align="center">
    <i class="fa-solid fa-cash-register" id="top-i"></i>
    <h2>Input Penjualan</h2>
    <form method="post">
        <input type="text" name="kode" placeholder="Kode Barang" required><br>
        <input type="number" name="jumlah" placeholder="Jumlah Terjual" required><br>
        <button type="submit">Simpan</button>
    </form>

    <h3>Riwayat Penjualan</h3>
    <div class="scroll-table-container">
        <table>
            <tr><th>Nama</th><th>Kode</th><th>Jumlah</th></tr>
            <?php foreach ($data as $d): ?>
                <tr>
                    <td><?= $d['nama'] ?></td>
                    <td><?= $d['kode'] ?></td>
                    <td><?= $d['jumlah'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <h3 style="margin-top: 2rem;">Rata-rata Penjualan dan Perkiraan Stok Habis</h3>
    <div class="scroll-table-container">
        <table>
            <tr><th>Nama</th><th>Kode</th><th>Rata-rata Penjualan</th><th>Stok Sekarang</th><th>Perkiraan Habis</th></tr>
            <?php foreach ($avg as $a): ?>
                <tr>
                    <td><?= $a['nama'] ?></td>
                    <td><?= $a['kode'] ?></td>
                    <td><?= round($a['rata_jumlah'], 2) ?></td>
                    <td><?= $a['stok'] ?></td>
                    <td>
                        <?php
                            if ($a['rata_jumlah'] > 0) {
                                echo floor($a['stok'] / $a['rata_jumlah']) . " hari";
                            } else {
                                echo "-";
                            }
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>
<?php include 'template/sidebar.php'; ?>
</body>
</html>
