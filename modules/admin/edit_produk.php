<?php
require "../../config/koneksi.php";
require "../../includes/auth_helper.php";
require "../../includes/header.php";

/** @var mysqli $conn */

if (!isset($_SESSION['role'])) {
    header("Location: ../auth/login.php");
    exit;
}

if ($_SESSION['role'] != "Admin") {
    die("Akses ditolak");
}

$id = $_GET['id'];

$data = mysqli_fetch_assoc(
    mysqli_query($conn, "
        SELECT * FROM m_produk
        WHERE id_produk='$id'
    ")
);

if (isset($_POST['update'])) {

    $nama  = trim($_POST['nama_produk']);
    $harga = $_POST['harga_jual'];
    $stok  = $_POST['stok'];

    /* ================= KETERANGAN ================= */

    $jenis_keterangan  = $_POST['jenis_keterangan'];
    $custom_keterangan = trim($_POST['custom_keterangan']);

    if ($jenis_keterangan == "Lainnya") {
        $keterangan = $custom_keterangan;
    } else {
        $keterangan = $jenis_keterangan;
    }

    /* ================= VALIDASI ================= */

    if (empty($nama) || $harga == "" || $stok == "") {

        echo "
        <script>
            alert('Semua field wajib diisi!');
        </script>
        ";

    } elseif ($harga < 0) {

        echo "
        <script>
            alert('Harga tidak boleh negatif!');
        </script>
        ";

    } elseif ($stok < 0) {

        echo "
        <script>
            alert('Stok tidak boleh negatif!');
        </script>
        ";

    } elseif ($jenis_keterangan == "Lainnya" && empty($custom_keterangan)) {

        echo "
        <script>
            alert('Keterangan custom wajib diisi!');
        </script>
        ";

    } else {

        /* ================= UPDATE PRODUK ================= */

        mysqli_query($conn, "
            UPDATE m_produk
            SET
                nama_produk = '$nama',
                harga_jual  = '$harga',
                stok        = '$stok'
            WHERE id_produk = '$id'
        ");

        /* ================= LOG STOK ================= */

        mysqli_query($conn, "
            INSERT INTO t_log_stok(
                id_produk,
                jumlah,
                tipe,
                keterangan
            ) VALUES (
                '$id',
                '$stok',
                'Masuk',
                '$keterangan'
            )
        ");

        echo "
        <script>
            alert('Produk berhasil diupdate');
            window.location = 'produk.php';
        </script>
        ";
    }
}
?>

<style>
    .page-title {
        font-size: 32px;
        color: #16a34a;
        margin-bottom: 25px;
        font-weight: bold;
    }

    .card {
        max-width: 650px;
        margin: auto;
        background: white;
        padding: 35px;
        border-radius: 28px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.06);
    }

    .card h1 {
        margin-bottom: 30px;
        text-align: center;
        color: #16a34a;
        font-size: 32px;
    }

    .input-group {
        margin-bottom: 22px;
    }

    .input-group label {
        display: block;
        margin-bottom: 10px;
        font-weight: 600;
        color: #334155;
        font-size: 15px;
    }

    input,
    select,
    textarea {
        width: 100%;
        padding: 15px;
        border-radius: 16px;
        border: 1px solid #d1d5db;
        background: #f8fafc;
        color: #0f172a;
        font-size: 15px;
        transition: 0.3s;
    }

    input:focus,
    select:focus,
    textarea:focus {
        outline: none;
        border: 1px solid #16a34a;
        background: white;
        box-shadow: 0 0 0 4px rgba(22, 163, 74, 0.15);
    }

    textarea {
        min-height: 110px;
        resize: none;
    }

    .btn {
        width: 100%;
        padding: 16px;
        border: none;
        border-radius: 18px;
        background: #16a34a;
        color: white;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        transition: 0.3s;
        box-shadow: 0 10px 20px rgba(22, 163, 74, 0.2);
    }

    .btn:hover {
        background: #15803d;
        transform: translateY(-2px);
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 25px;
        padding: 12px 18px;
        background: #dcfce7;
        color: #16a34a;
        text-decoration: none;
        border-radius: 14px;
        font-weight: bold;
        transition: 0.3s;
    }

    .back-btn:hover {
        background: #bbf7d0;
        transform: translateX(-3px);
    }

    select {
        cursor: pointer;
    }

    #customBox {
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 768px) {

        .card {
            padding: 22px;
            border-radius: 22px;
        }

        .card h1 {
            font-size: 26px;
        }

        input,
        select,
        textarea,
        .btn {
            font-size: 15px;
            min-height: 52px;
        }

        .btn {
            padding: 14px;
        }

        .back-btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="card">

    <a href="produk.php" class="back-btn">
        ← Kembali
    </a>

    <h1>Edit Produk</h1>

    <form method="POST">

        <!-- ================= NAMA ================= -->

        <div class="input-group">
            <label>Nama Produk</label>
            <input
                type="text"
                name="nama_produk"
                value="<?= $data['nama_produk']; ?>">
        </div>

        <!-- ================= HARGA ================= -->

        <div class="input-group">
            <label>Harga Jual</label>
            <input
                type="number"
                name="harga_jual"
                value="<?= $data['harga_jual']; ?>">
        </div>

        <!-- ================= STOK ================= -->

        <div class="input-group">
            <label>Stok</label>
            <input
                type="number"
                name="stok"
                value="<?= $data['stok']; ?>">
        </div>

        <!-- ================= KETERANGAN ================= -->

        <div class="input-group">
            <label>Keterangan Stock</label>

            <select
                name="jenis_keterangan"
                id="jenis_keterangan"
                onchange="toggleCustomKeterangan()">

                <option value="Stock Opname Manual">Stock Opname Manual</option>
                <option value="Barang Rusak">Barang Rusak</option>
                <option value="Retur Supplier">Retur Supplier</option>
                <option value="Penyesuaian Gudang">Penyesuaian Gudang</option>
                <option value="Lainnya">Lainnya</option>

            </select>
        </div>

        <!-- ================= CUSTOM ================= -->

        <div class="input-group"
            id="customBox"
            style="display: none;">

            <label>Keterangan Custom</label>

            <textarea
                name="custom_keterangan"
                placeholder="Masukkan keterangan custom..."></textarea>
        </div>

        <!-- ================= BUTTON ================= -->

        <button class="btn" name="update">
            Update Produk
        </button>

    </form>

</div>

<script>
    function toggleCustomKeterangan() {

        const jenis = document.getElementById("jenis_keterangan").value;
        const customBox = document.getElementById("customBox");

        if (jenis == "Lainnya") {
            customBox.style.display = "block";
        } else {
            customBox.style.display = "none";
        }
    }
</script>

<?php require "../../includes/footer.php"; ?>
