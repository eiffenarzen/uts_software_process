<?php
require "../../config/koneksi.php";
require "../../includes/auth_helper.php";

adminOnly();

include "../../includes/header.php";

/** @var mysqli $conn */

/* ================= SIMPAN PRODUK ================= */

if (isset($_POST['simpan'])) {

    $nama  = trim($_POST['nama_produk']);
    $harga = $_POST['harga_jual'];
    $stok  = $_POST['stok'];

    /* ================= VALIDASI ================= */

    if (empty($nama) || $harga == "" || $stok == "") {

        echo "
        <script>
            alert('Semua field wajib diisi!');
        </script>
        ";

    } elseif (!is_numeric($harga) || $harga < 0) {

        echo "
        <script>
            alert('Harga harus angka positif!');
        </script>
        ";

    } elseif ($stok < 0) {

        echo "
        <script>
            alert('Stok tidak boleh negatif!');
        </script>
        ";

    } else {

        /* ================= CEK DUPLIKAT ================= */

        $cekProduk = mysqli_query($conn, "
            SELECT * FROM m_produk
            WHERE LOWER(nama_produk) = LOWER('$nama')
        ");

        if (mysqli_num_rows($cekProduk) > 0) {

            echo "
            <script>
                alert('Nama produk sudah ada!');
                window.location = 'produk.php';
            </script>
            ";

            exit;
        }

        /* ================= INSERT PRODUK ================= */

        mysqli_query($conn, "
            INSERT INTO m_produk(
                nama_produk,
                harga_jual,
                stok
            ) VALUES (
                '$nama',
                '$harga',
                '$stok'
            )
        ");

        $id_produk = mysqli_insert_id($conn);

        /* ================= LOG STOK ================= */

        mysqli_query($conn, "
            INSERT INTO t_log_stok(
                id_produk,
                jumlah,
                tipe,
                keterangan
            ) VALUES (
                '$id_produk',
                '$stok',
                'Masuk',
                'Saldo Awal'
            )
        ");

        echo "
        <script>
            alert('Produk berhasil ditambahkan!');
            window.location = 'produk.php';
        </script>
        ";
    }
}

/* ================= SEARCH ================= */

$cari = "";

if (isset($_GET['cari'])) {
    $cari = $_GET['cari'];
}

$data = mysqli_query($conn, "
    SELECT * FROM m_produk
    WHERE nama_produk LIKE '%$cari%'
    ORDER BY id_produk DESC
");
?>

<style>
    body {
        background: #f4fff7;
        color: #1f2937;
        padding: 25px;
        font-family: 'Segoe UI', sans-serif;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .container {
        max-width: 1200px;
        margin: auto;
    }

    .topbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        gap: 15px;
    }

    .topbar h1 {
        font-size: 32px;
        color: #15803d;
    }

    .back-btn {
        background: #16a34a;
        color: white;
        padding: 12px 18px;
        border-radius: 14px;
        text-decoration: none;
        font-weight: bold;
        transition: 0.3s;
        box-shadow: 0 4px 12px rgba(22, 163, 74, 0.2);
    }

    .back-btn:hover {
        background: #15803d;
        transform: translateY(-2px);
    }

    .card {
        background: white;
        padding: 25px;
        border-radius: 24px;
        margin-bottom: 25px;
        border: 1px solid #dcfce7;
        box-shadow: 0 10px 30px rgba(22, 163, 74, 0.08);
    }

    .card h2 {
        margin-bottom: 20px;
        color: #166534;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    input {
        width: 100%;
        padding: 14px;
        border-radius: 14px;
        border: 2px solid #dcfce7;
        background: #f0fdf4;
        color: #1f2937;
        font-size: 15px;
        transition: 0.3s;
    }

    input:focus {
        outline: none;
        border-color: #22c55e;
        background: white;
    }

    button {
        border: none;
        border-radius: 14px;
        background: #16a34a;
        color: white;
        font-weight: bold;
        cursor: pointer;
        transition: 0.3s;
        min-height: 50px;
        box-shadow: 0 4px 12px rgba(22, 163, 74, 0.2);
    }

    button:hover {
        background: #15803d;
        transform: translateY(-2px);
    }

    .table-wrap {
        overflow: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 18px;
        overflow: hidden;
    }

    table th {
        background: #16a34a;
        color: white;
    }

    table th,
    table td {
        padding: 16px;
        border-bottom: 1px solid #dcfce7;
        text-align: left;
    }

    table tr:hover {
        background: #f0fdf4;
    }

    .kritis {
        background: #fee2e2;
        color: #dc2626;
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: bold;
    }

    .aman {
        background: #dcfce7;
        color: #15803d;
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: bold;
    }

    .action-btn {
        display: flex;
        gap: 10px;
    }

    .edit-btn {
        background: #22c55e;
        color: white;
        padding: 10px 14px;
        border-radius: 12px;
        text-decoration: none;
        font-size: 13px;
        font-weight: bold;
    }

    .delete-btn {
        background: #ef4444;
        color: white;
        padding: 10px 14px;
        border-radius: 12px;
        text-decoration: none;
        font-size: 13px;
        font-weight: bold;
    }
</style>

<div class="container">

    <div class="topbar">

        <h1>
            <i class='bx bxs-package'></i>
            Manajemen Produk
        </h1>

        <a href="dashboard.php" class="back-btn">
            ← Dashboard
        </a>

    </div>

    <div class="card">

        <h2>Cari Produk</h2>

        <form method="GET">
            <div class="form-grid">

                <input
                    type="text"
                    name="cari"
                    placeholder="Cari nama produk..."
                    value="<?= $cari; ?>">

                <button>Cari</button>

            </div>
        </form>

    </div>

    <div class="card">

        <h2>Tambah Produk</h2>

        <form method="POST">

            <div class="form-grid">

                <input
                    type="text"
                    name="nama_produk"
                    placeholder="Nama Produk"
                    required>

                <input
                    type="number"
                    name="harga_jual"
                    placeholder="Harga Jual"
                    required>

                <input
                    type="number"
                    name="stok"
                    placeholder="Stok"
                    required>

                <button name="simpan">
                    Tambah Produk
                </button>

            </div>

        </form>

    </div>

    <div class="card">

        <h2>Daftar Produk</h2>

        <div class="table-wrap">

            <table>

                <tr>
                    <th>Nama</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>

                <?php while ($d = mysqli_fetch_assoc($data)) { ?>

                <tr>

                    <td><?= $d['nama_produk']; ?></td>

                    <td>
                        Rp <?= number_format($d['harga_jual']); ?>
                    </td>

                    <td><?= $d['stok']; ?></td>

                    <td>

                        <?php
                        if ($d['stok'] < 5) {
                            echo "<span class='kritis'>STOK KRITIS</span>";
                        } else {
                            echo "<span class='aman'>AMAN</span>";
                        }
                        ?>

                    </td>

                    <td>

                        <div class="action-btn">

                            <a
                                href="edit_produk.php?id=<?= $d['id_produk']; ?>"
                                class="edit-btn">
                                Edit
                            </a>

                            <a
                                href="hapus_produk.php?id=<?= $d['id_produk']; ?>"
                                class="delete-btn"
                                onclick="return confirm('Yakin hapus produk?')">
                                Hapus
                            </a>

                        </div>

                    </td>

                </tr>

                <?php } ?>

            </table>

        </div>

    </div>

</div>

<?php include "../../includes/footer.php"; ?>
