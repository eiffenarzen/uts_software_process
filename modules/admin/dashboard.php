<?php
require "../../config/koneksi.php";
require "../../includes/auth_helper.php";

adminOnly();

include "../../includes/header.php";

/** @var mysqli $conn */

/* ================= TOTAL DATA ================= */

$totalProduk = mysqli_fetch_assoc(
    mysqli_query($conn, "
        SELECT COUNT(*) total FROM m_produk
    ")
);

$totalUser = mysqli_fetch_assoc(
    mysqli_query($conn, "
        SELECT COUNT(*) total FROM m_user
    ")
);

$totalPenjualan = mysqli_fetch_assoc(
    mysqli_query($conn, "
        SELECT COUNT(*) total FROM t_penjualan
    ")
);

$totalOmset = mysqli_fetch_assoc(
    mysqli_query($conn, "
        SELECT COALESCE(SUM(total_bayar),0) total
        FROM t_penjualan
    ")
);

/* ================= CHART ================= */

$chart = mysqli_query($conn, "
    SELECT
        DATE(tgl_transaksi) tanggal,
        SUM(total_bayar) total
    FROM t_penjualan
    GROUP BY DATE(tgl_transaksi)
    ORDER BY DATE(tgl_transaksi) ASC
");

$tanggal = [];
$omset   = [];

while ($c = mysqli_fetch_assoc($chart)) {
    $tanggal[] = $c['tanggal'];
    $omset[]   = $c['total'];
}
?>

<title>Dashboard Admin</title>

<link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', sans-serif;
    }

    body {
        background: #f8fafc;
        color: #0f172a;
        display: flex;
    }

    /* ================= SIDEBAR ================= */

    .sidebar {
        width: 260px;
        height: 100vh;
        background: white;
        padding: 20px;
        position: fixed;
        border-right: 1px solid #e5e7eb;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
    }

    .logo {
        font-size: 28px;
        font-weight: bold;
        margin-bottom: 40px;
        color: #16a34a;
    }

    .menu a {
        display: flex;
        align-items: center;
        gap: 12px;
        color: #0f172a;
        text-decoration: none;
        padding: 15px;
        margin-bottom: 10px;
        border-radius: 14px;
        transition: 0.3s;
        font-weight: 500;
    }

    .menu a:hover {
        background: #dcfce7;
        color: #16a34a;
        transform: translateX(4px);
    }

    .menu i {
        font-size: 22px;
    }

    /* ================= MAIN ================= */

    .main {
        margin-left: 260px;
        width: 100%;
        padding: 30px;
    }

    /* ================= TOPBAR ================= */

    .topbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .topbar h1 {
        font-size: 32px;
        color: #0f172a;
    }

    /* ================= PROFILE ================= */

    .profile {
        background: white;
        padding: 12px 20px;
        border-radius: 14px;
        border: 1px solid #e5e7eb;
        color: #16a34a;
        font-weight: bold;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    /* ================= CARDS ================= */

    .cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .card {
        background: white;
        padding: 25px;
        border-radius: 24px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        transition: 0.3s;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    .card h2 {
        margin-top: 12px;
        font-size: 30px;
        color: #0f172a;
    }

    .card p {
        margin-top: 6px;
        color: #64748b;
    }

    .card i {
        font-size: 42px;
        color: #16a34a;
    }

    /* ================= CHART ================= */

    .chart-box {
        background: white;
        padding: 25px;
        border-radius: 24px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    }

    .chart-box h2 {
        margin-bottom: 20px;
        color: #0f172a;
    }

    canvas {
        background: #f8fafc;
        border-radius: 16px;
        padding: 10px;
    }

    /* ================= MOBILE ================= */

    .bottom-nav {
        display: none;
    }

    @media (max-width: 768px) {

        body {
            display: block;
            padding-bottom: 90px;
        }

        .sidebar {
            display: none;
        }

        .main {
            margin-left: 0;
            padding: 15px;
        }

        .topbar {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .topbar h1 {
            font-size: 26px;
        }

        .profile {
            width: 100%;
            text-align: center;
        }

        .cards {
            grid-template-columns: 1fr;
        }

        .card {
            padding: 20px;
        }

        .chart-box {
            padding: 18px;
        }

        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: white;
            display: flex;
            justify-content: space-around;
            padding: 12px 0;
            border-top: 1px solid #e5e7eb;
            z-index: 999;
            box-shadow: 0 -5px 20px rgba(0, 0, 0, 0.05);
        }

        .bottom-nav a {
            color: #16a34a;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 12px;
            gap: 5px;
            font-weight: 500;
        }

        .bottom-nav i {
            font-size: 22px;
        }
    }
</style>

<body>

    <div class="sidebar">

        <div class="logo">
            SI-KASIR
        </div>

        <div class="menu">

            <a href="dashboard.php">
                <i class='bx bxs-dashboard'></i>
                <span class="text">Dashboard</span>
            </a>

            <a href="produk.php">
                <i class='bx bxs-package'></i>
                <span class="text">Produk</span>
            </a>

            <a href="user.php">
                <i class='bx bxs-user'></i>
                <span class="text">User</span>
            </a>

            <a href="laporan.php">
                <i class='bx bxs-report'></i>
                <span class="text">Laporan</span>
            </a>

            <a href="../auth/logout.php">
                <i class='bx bx-log-out'></i>
                <span class="text">Logout</span>
            </a>

        </div>

    </div>

    <div class="main">

        <div class="topbar">

            <h1>Dashboard Admin</h1>

            <div class="profile">
                <?= $_SESSION['username']; ?>
            </div>

        </div>

        <div class="cards">

            <div class="card">
                <i class='bx bxs-package'></i>
                <h2><?= $totalProduk['total']; ?></h2>
                <p>Total Produk</p>
            </div>

            <div class="card">
                <i class='bx bxs-user'></i>
                <h2><?= $totalUser['total']; ?></h2>
                <p>Total User</p>
            </div>

            <div class="card">
                <i class='bx bxs-cart'></i>
                <h2><?= $totalPenjualan['total']; ?></h2>
                <p>Total Penjualan</p>
            </div>

            <div class="card">
                <i class='bx bxs-wallet'></i>
                <h2>
                    Rp <?= number_format($totalOmset['total'] ?? 0); ?>
                </h2>
                <p>Total Omset</p>
            </div>

        </div>

        <div class="chart-box">

            <h2 style="margin-bottom: 20px;">
                Grafik Penjualan
            </h2>

            <canvas id="myChart"></canvas>

        </div>

    </div>

    <script>
        const ctx = document.getElementById('myChart');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?= json_encode($tanggal); ?>,
                datasets: [{
                    label: 'Omset',
                    data: <?= json_encode($omset); ?>,
                    borderWidth: 3,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true
            }
        });
    </script>

    <div class="bottom-nav">

        <a href="dashboard.php">
            <i class='bx bxs-dashboard'></i>
            <span>Dashboard</span>
        </a>

        <a href="produk.php">
            <i class='bx bxs-package'></i>
            <span>Produk</span>
        </a>

        <a href="user.php">
            <i class='bx bxs-user'></i>
            <span>User</span>
        </a>

        <a href="laporan.php">
            <i class='bx bxs-report'></i>
            <span>Laporan</span>
        </a>

    </div>

<?php include "../../includes/footer.php"; ?>
