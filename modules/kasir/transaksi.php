<?php
require "../../config/koneksi.php";
require "../../includes/auth_helper.php";
require "../../includes/header.php";

/** @var mysqli $conn */

kasirOnly();

/* ================= SESSION CART ================= */

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

/* ================= TAMBAH CART ================= */

if (isset($_POST['tambah'])) {

    $id_produk = mysqli_real_escape_string($conn, $_POST['id_produk']);
    $qty       = (int) $_POST['qty'];

    if ($qty <= 0) {

        echo "
        <script>
            alert('Qty harus lebih dari 0');
        </script>
        ";

    } else {

        $queryProduk = mysqli_query($conn, "
            SELECT * FROM m_produk
            WHERE id_produk = '$id_produk'
        ");

        $produk = mysqli_fetch_assoc($queryProduk);

        if (!$produk) {

            echo "
            <script>
                alert('Produk tidak ditemukan');
            </script>
            ";

        } elseif ($qty > $produk['stok']) {

            echo "
            <script>
                alert('Stok tidak cukup');
            </script>
            ";

        } else {

            $found = false;

            foreach ($_SESSION['cart'] as $key => $item) {

                if ($item['id_produk'] == $produk['id_produk']) {

                    $_SESSION['cart'][$key]['qty'] += $qty;

                    $_SESSION['cart'][$key]['subtotal'] =
                        $_SESSION['cart'][$key]['qty'] *
                        $_SESSION['cart'][$key]['harga'];

                    $found = true;
                    break;
                }
            }

            if (!$found) {

                $_SESSION['cart'][] = [
                    'id_produk'   => $produk['id_produk'],
                    'nama_produk' => $produk['nama_produk'],
                    'harga'       => $produk['harga_jual'],
                    'qty'         => $qty,
                    'subtotal'    => $qty * $produk['harga_jual']
                ];
            }

            echo "
            <script>
                alert('Produk ditambahkan');
                window.location='transaksi.php';
            </script>
            ";
        }
    }
}

/* ================= HAPUS CART ================= */

if (isset($_GET['hapus'])) {

    $index = $_GET['hapus'];

    if (isset($_SESSION['cart'][$index])) {

        unset($_SESSION['cart'][$index]);

        $_SESSION['cart'] = array_values($_SESSION['cart']);

        echo "
        <script>
            alert('Produk dihapus dari keranjang');
            window.location='transaksi.php';
        </script>
        ";
    }
}

/* ================= PRODUK ================= */

$produk = mysqli_query($conn, "
    SELECT * FROM m_produk
    ORDER BY nama_produk ASC
");
?>

<style>

    .transaksi-container{
        max-width:1300px;
        margin:auto;
    }

    /* ================= TOPBAR ================= */

    .topbar{
        display:flex;
        justify-content:space-between;
        align-items:center;
        gap:20px;
        margin-bottom:35px;
    }

    .topbar h1{
        font-size:42px;
        font-weight:800;
        color:#16a34a;
    }

    .top-right{
        display:flex;
        align-items:center;
        gap:15px;
    }

    /* ================= KASIR BOX ================= */

    .kasir-box{
        display:flex;
        align-items:center;
        gap:14px;
        background:white;
        padding:14px 18px;
        border-radius:24px;
        border:1px solid #dcfce7;
        box-shadow:0 10px 30px rgba(0,0,0,0.05);
    }

    .kasir-icon{
        width:55px;
        height:55px;
        border-radius:18px;
        background:linear-gradient(135deg,#22c55e,#16a34a);
        display:flex;
        justify-content:center;
        align-items:center;
        color:white;
        font-size:28px;
    }

    .kasir-info{
        display:flex;
        flex-direction:column;
    }

    .kasir-info small{
        color:#64748b;
        font-size:12px;
    }

    .kasir-info b{
        font-size:16px;
        color:#14532d;
    }

    /* ================= LOGOUT ================= */

    .logout{
        display:flex;
        justify-content:center;
        align-items:center;
        padding:16px 24px;
        border-radius:20px;
        text-decoration:none;
        background:#ef4444;
        color:white;
        font-weight:700;
        min-width:120px;
        transition:0.3s;
    }

    .logout:hover{
        transform:translateY(-2px);
    }

    /* ================= CARD ================= */

    .card{
        background:white;
        border-radius:30px;
        padding:30px;
        margin-bottom:30px;
        border:1px solid #dcfce7;
        box-shadow:0 15px 40px rgba(0,0,0,0.05);
    }

    .card h2{
        margin-bottom:22px;
        font-size:28px;
        color:#166534;
    }

    /* ================= FORM ================= */

    .form-grid{
        display:grid;
        grid-template-columns:2fr 1fr 1fr;
        gap:15px;
    }

    select,
    input{
        width:100%;
        padding:16px;
        border-radius:18px;
        border:1px solid #bbf7d0;
        background:#f0fdf4;
        color:#14532d;
        font-size:15px;
    }

    select:focus,
    input:focus{
        outline:none;
        border:1px solid #22c55e;
    }

    button{
        border:none;
        border-radius:18px;
        background:#16a34a;
        color:white;
        font-size:15px;
        font-weight:bold;
        cursor:pointer;
        transition:0.3s;
    }

    button:hover{
        transform:translateY(-2px);
    }

    /* ================= TABLE ================= */

    .table-wrap{
        overflow-x:auto;
    }

    table{
        width:100%;
        border-collapse:collapse;
        overflow:hidden;
        border-radius:22px;
        background:white;
    }

    table th{
        background:#16a34a;
        color:white;
    }

    table th,
    table td{
        padding:16px;
        text-align:left;
        border-bottom:1px solid #dcfce7;
    }

    table tr:hover{
        background:#f0fdf4;
    }

    .hapus{
        padding:10px 14px;
        border-radius:12px;
        background:#ef4444;
        color:white;
        text-decoration:none;
        font-size:13px;
        font-weight:bold;
    }

    /* ================= TOTAL ================= */

    .total-box{
        margin-top:25px;
        text-align:right;
    }

    .total-box h1{
        font-size:42px;
        color:#16a34a;
    }

    /* ================= BAYAR ================= */

    .bayar-box{
        display:flex;
        gap:15px;
        margin-top:20px;
    }

    .bayar-box input{
        flex:1;
    }

    /* ================= EMPTY ================= */

    .empty{
        text-align:center;
        padding:70px 20px;
    }

    .empty i{
        font-size:90px;
        color:#22c55e;
        margin-bottom:20px;
    }

    .empty h3{
        font-size:34px;
        margin-bottom:10px;
        color:#14532d;
    }

    .empty p{
        color:#64748b;
        font-size:16px;
    }

    /* ================= MOBILE ================= */

    @media (max-width:768px){

        .topbar{
            flex-direction:column;
            align-items:flex-start;
        }

        .top-right{
            width:100%;
            flex-direction:column;
            align-items:stretch;
        }

        .kasir-box,
        .logout{
            width:100%;
        }

        .form-grid{
            grid-template-columns:1fr;
        }

        .bayar-box{
            flex-direction:column;
        }

        .topbar h1{
            font-size:32px;
        }

        .card{
            padding:22px;
            border-radius:24px;
        }

        .card h2{
            font-size:24px;
        }

        .total-box h1{
            font-size:32px;
        }

        table{
            min-width:700px;
        }
    }

</style>

<div class="transaksi-container">

    <!-- ================= TOPBAR ================= -->

    <div class="topbar">

        <h1>
            <i class='bx bxs-cart'></i>
            Transaksi Kasir
        </h1>

        <div class="top-right">

            <div class="kasir-box">

                <div class="kasir-icon">
                    <i class='bx bxs-user'></i>
                </div>

                <div class="kasir-info">
                    <small>Kasir</small>
                    <b><?= $_SESSION['username']; ?></b>
                </div>

            </div>

            <a
                href="../auth/logout.php"
                class="logout">
                Logout
            </a>

        </div>

    </div>

    <!-- ================= TAMBAH PRODUK ================= -->

    <div class="card">

        <h2>Tambah Produk</h2>

        <form method="POST">

            <div class="form-grid">

                <select name="id_produk" required>

                    <option value="">
                        -- Pilih Produk --
                    </option>

                    <?php while($p = mysqli_fetch_assoc($produk)) { ?>

                    <option value="<?= $p['id_produk']; ?>">

                        <?= $p['nama_produk']; ?>
                        - Stok: <?= $p['stok']; ?>

                    </option>

                    <?php } ?>

                </select>

                <input
                    type="number"
                    name="qty"
                    placeholder="Qty"
                    required>

                <button
                    type="submit"
                    name="tambah">
                    Tambah
                </button>

            </div>

        </form>

    </div>

    <!-- ================= CART ================= -->

    <div class="card">

        <h2>Keranjang Belanja</h2>

        <?php if(count($_SESSION['cart']) > 0) { ?>

        <div class="table-wrap">

            <table>

                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                    <th>Aksi</th>
                </tr>

                <?php
                $total = 0;

                foreach($_SESSION['cart'] as $index => $c){

                    $total += $c['subtotal'];
                ?>

                <tr>

                    <td><?= $c['nama_produk']; ?></td>

                    <td>
                        Rp <?= number_format($c['harga']); ?>
                    </td>

                    <td><?= $c['qty']; ?></td>

                    <td>
                        Rp <?= number_format($c['subtotal']); ?>
                    </td>

                    <td>

                        <a
                            href="?hapus=<?= $index; ?>"
                            class="hapus"
                            onclick="return confirm('Hapus produk ini?')">
                            Hapus
                        </a>

                    </td>

                </tr>

                <?php } ?>

            </table>

        </div>

        <div class="total-box">

            <h1>
                Total : Rp <?= number_format($total); ?>
            </h1>

        </div>

        <form
            action="proses_transaksi.php"
            method="POST">

            <div class="bayar-box">

                <input
                    type="number"
                    name="bayar"
                    placeholder="Masukkan uang bayar"
                    required>

                <button type="submit">
                    Selesaikan Transaksi
                </button>

            </div>

        </form>

        <?php } else { ?>

        <div class="empty">

            <i class='bx bx-cart'></i>

            <h3>
                Keranjang Masih Kosong
            </h3>

            <p>
                Tambahkan produk untuk memulai transaksi.
            </p>

        </div>

        <?php } ?>

    </div>

</div>

<?php require "../../includes/footer.php"; ?>
