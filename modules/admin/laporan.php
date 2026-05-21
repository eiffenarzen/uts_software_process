<?php
require "../../config/koneksi.php";
require "../../includes/auth_helper.php";
require "../../includes/header.php";

/** @var mysqli $conn */

adminOnly();

/* ================= LAPORAN ================= */

$laporan = mysqli_query($conn, "
    SELECT
        t_penjualan.nomor_nota,
        t_penjualan.tgl_transaksi,
        m_user.username,
        t_penjualan.total_bayar
    FROM t_penjualan
    INNER JOIN m_user
        ON t_penjualan.id_user = m_user.id_user
    ORDER BY t_penjualan.id_penjualan DESC
");

/* ================= BEST SELLER ================= */

$bestseller = mysqli_query($conn, "
    SELECT
        m_produk.nama_produk,
        SUM(t_penjualan_detail.qty) total_terjual
    FROM t_penjualan_detail
    INNER JOIN m_produk
        ON t_penjualan_detail.id_produk = m_produk.id_produk
    GROUP BY m_produk.nama_produk
    ORDER BY total_terjual DESC
");

/* ================= LOG STOK ================= */

$stok = mysqli_query($conn, "
    SELECT
        t_log_stok.*,
        m_produk.nama_produk
    FROM t_log_stok
    INNER JOIN m_produk
        ON t_log_stok.id_produk = m_produk.id_produk
    ORDER BY waktu_log DESC
");
?>

<style>
    html,
    body{
        overflow-x:hidden;
        font-family:'Segoe UI',sans-serif;
    }

    .main {
        width: 100%;
        padding: 30px;
        max-width: 1400px;
        margin: auto;
    }

    .topbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        gap: 15px;
    }

    .topbar h1 {
        font-size: 34px;
        color: #16a34a;
    }

    .profile {
        background: white;
        padding: 12px 20px;
        border-radius: 16px;
        border: 1px solid #e5e7eb;
        color: #16a34a;
        font-weight: bold;
        box-shadow: 0 8px 20px rgba(0,0,0,0.05);
    }

    .card {
        background: white;
        padding: 25px;
        border-radius: 28px;
        margin-bottom: 30px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 12px 30px rgba(0,0,0,0.05);
    }

    .card h2 {
        margin-bottom: 20px;
        color: #16a34a;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 24px;
    }

    .table-wrap {
        overflow-x: auto;
        border-radius: 18px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
    }

    table th {
        background: #16a34a;
        color: white;
        padding: 16px;
        text-align: left;
        font-size: 15px;
    }

    table td {
        padding: 15px;
        border-bottom: 1px solid #e5e7eb;
        color: #334155;
    }

    table tr:hover {
        background: #f0fdf4;
    }

    .export-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 13px 20px;
        background: #16a34a;
        color: white;
        text-decoration: none;
        border-radius: 16px;
        margin-bottom: 20px;
        font-weight: bold;
        transition: 0.3s;
        box-shadow: 0 10px 20px rgba(22,163,74,0.2);
    }

    .export-btn:hover {
        background: #15803d;
        transform: translateY(-2px);
    }

    .masuk {
        color: #16a34a;
        font-weight: bold;
    }

    .keluar {
        color: #dc2626;
        font-weight: bold;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 18px;
        background: #dcfce7;
        color: #16a34a;
        text-decoration: none;
        border-radius: 14px;
        margin-bottom: 20px;
        font-weight: bold;
        transition: 0.3s;
    }

    .back-btn:hover {
        background: #bbf7d0;
        transform: translateX(-3px);
    }

    @media (max-width: 768px) {

        body {
            padding-bottom: 90px;
        }

        .main {
            padding: 15px;
        }

        .topbar {
            flex-direction: column;
            align-items: flex-start;
        }

        .topbar h1 {
            font-size: 28px;
        }

        .profile {
            width: 100%;
            text-align: center;
        }

        .card {
            padding: 18px;
            border-radius: 22px;
        }

        .card h2 {
            font-size: 20px;
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            min-width: 700px;
        }
        
        .main{
            overflow:hidden;
        }

        table th,
        table td {
            padding: 12px;
            font-size: 13px;
        }

        .export-btn,
        .back-btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="main">

    <div class="topbar">

        <h1>Laporan SI-KASIR</h1>

        <div class="profile">
            <?= $_SESSION['username']; ?>
        </div>

    </div>

    <!-- ================= LAPORAN PENJUALAN ================= -->

    <a href="dashboard.php" class="back-btn">
        ← Dashboard
    </a>

    <div class="card">

        <h2>
            <i class='bx bxs-report'></i>
            Laporan Penjualan
        </h2>

        <a href="export_laporan.php" class="export-btn">
            Export Excel
        </a>

        <div class="table-wrap">

            <table>

                <tr>
                    <th>No Nota</th>
                    <th>Tanggal</th>
                    <th>Kasir</th>
                    <th>Total</th>
                </tr>

                <?php while ($d = mysqli_fetch_assoc($laporan)) { ?>

                <tr>
                    <td><?= $d['nomor_nota']; ?></td>
                    <td><?= $d['tgl_transaksi']; ?></td>
                    <td><?= $d['username']; ?></td>
                    <td>Rp <?= number_format($d['total_bayar']); ?></td>
                </tr>

                <?php } ?>

            </table>

        </div>

    </div>

    <!-- ================= BEST SELLER ================= -->

    <div class="card">

        <h2>
            <i class='bx bxs-star'></i>
            Produk Best Seller
        </h2>

        <div class="table-wrap">

            <table>

                <tr>
                    <th>Nama Produk</th>
                    <th>Total Terjual</th>
                </tr>

                <?php while ($d = mysqli_fetch_assoc($bestseller)) { ?>

                <tr>
                    <td><?= $d['nama_produk']; ?></td>
                    <td><?= $d['total_terjual']; ?></td>
                </tr>

                <?php } ?>

            </table>

        </div>

    </div>

    <!-- ================= LOG STOK ================= -->

    <div class="card">

        <h2>
            <i class='bx bxs-box'></i>
            Mutasi Stok
        </h2>

        <div class="table-wrap">

            <table>

                <tr>
                    <th>Produk</th>
                    <th>Jumlah</th>
                    <th>Tipe</th>
                    <th>Keterangan</th>
                    <th>Waktu</th>
                </tr>

                <?php while ($d = mysqli_fetch_assoc($stok)) { ?>

                <tr>
                    <td><?= $d['nama_produk']; ?></td>
                    <td><?= $d['jumlah']; ?></td>
                    <td>
                        <?php
                        if ($d['tipe'] == "Masuk") {
                            echo "<span class='masuk'>Masuk</span>";
                        } else {
                            echo "<span class='keluar'>Keluar</span>";
                        }
                        ?>
                    </td>
                    <td><?= $d['keterangan']; ?></td>
                    <td><?= $d['waktu_log']; ?></td>
                </tr>

                <?php } ?>

            </table>

        </div>

    </div>

</div>

<?php require "../../includes/footer.php"; ?>
