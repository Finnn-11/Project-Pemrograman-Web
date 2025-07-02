<?php
include 'includes/auth.php';
include 'includes/db.php';

$search = '';
$barang = [];

if (isset($_GET['cari']) && $_GET['cari'] !== '') {
    $search = $_GET['cari'];
    $stmt = $db->prepare("SELECT 
        b.id, b.nama, b.kode, b.stock,
        IFNULL(SUM(p.jumlah), 0) AS total_terjual,
        CASE 
            WHEN AVG(p.jumlah) > 0 THEN ROUND(b.stock / AVG(p.jumlah), 0)
            ELSE NULL
        END AS perkiraan_habis
        FROM barang b
        LEFT JOIN penjualan p ON b.kode = p.kode
        WHERE b.nama LIKE ?
        GROUP BY b.id, b.nama, b.kode, b.stock");
    $stmt->execute(["%$search%"]);
    $barang = $stmt->fetchAll();
} else {
    $barang = $db->query("SELECT 
        b.id, b.nama, b.kode, b.stock,
        IFNULL(SUM(p.jumlah), 0) AS total_terjual,
        CASE 
            WHEN AVG(p.jumlah) > 0 THEN ROUND(b.stock / AVG(p.jumlah), 0)
            ELSE NULL
        END AS perkiraan_habis
        FROM barang b
        LEFT JOIN penjualan p ON b.kode = p.kode
        GROUP BY b.id, b.nama, b.kode, b.stock")->fetchAll();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
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
    <i class="fa-solid fa-box" id="top-i"></i>
    <h2>Inventory Barang Sembako</h2>

    <form method="get" style="margin-bottom: 20px;">
        <input type="text" name="cari" placeholder="Cari nama barang..." value="<?= htmlspecialchars($search) ?>" style="padding: 8px; border-radius: 6px; width: 60%;">
        <button type="submit" style="padding: 8px 16px; background-color: #00bbbb; border: none; color: white; border-radius: 6px;">Cari</button>
        <a href="dashboard.php" style="margin-left: 10px; color: #00bbbb;">Reset</a>
    </form>

    <div class="scroll-table-container">
        <table>
            <tr><th>ID</th><th>Nama</th><th>Kode</th><th>Stock</th><th>Total Terjual</th><th>Perkiraan Habis</th><th>Aksi</th></tr>
            <?php if (count($barang) > 0): ?>
                <?php foreach ($barang as $b): ?>
                    <tr>
                        <td><?= $b['id'] ?></td>
                        <td><?= $b['nama'] ?></td>
                        <td><?= $b['kode'] ?></td>
                        <td><?= $b['stock'] ?></td>
                        <td><?= $b['total_terjual'] ?></td>
                        <td><?= $b['perkiraan_habis'] !== null ? $b['perkiraan_habis'] . ' hari' : '-' ?></td>
                        <td>
                            <a href="edit.php?id=<?= $b['id'] ?>">Edit</a> |
                            <a href="delete.php?id=<?= $b['id'] ?>" onclick="return confirm('Yakin hapus?')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7">Tidak ada data ditemukan.</td></tr>
            <?php endif; ?>
        </table>
    </div>
</div>
<?php include 'template/sidebar.php'; ?>
</body>
</html>
