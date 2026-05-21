<?php
require "../../config/koneksi.php";
require "../../includes/auth_helper.php";

/** @var mysqli $conn */

adminOnly();

/* ================= TAMBAH USER ================= */

if (isset($_POST['tambah'])) {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role     = $_POST['role'];

    if (empty($username) || empty($password)) {

        echo "
        <script>
            alert('Semua field wajib diisi!');
        </script>
        ";

    } else {

        $cek = mysqli_query($conn, "
            SELECT * FROM m_user
            WHERE username = '$username'
        ");

        if (mysqli_num_rows($cek) > 0) {

            echo "
            <script>
                alert('Username sudah digunakan!');
            </script>
            ";

        } else {

            $hash = password_hash($password, PASSWORD_DEFAULT);

            mysqli_query($conn, "
                INSERT INTO m_user(
                    username,
                    password,
                    role
                ) VALUES (
                    '$username',
                    '$hash',
                    '$role'
                )
            ");

            echo "
            <script>
                alert('User berhasil ditambahkan');
                window.location = 'user.php';
            </script>
            ";
        }
    }
}

$data = mysqli_query($conn, "
    SELECT * FROM m_user
    ORDER BY id_user DESC
");
?>

<?php include "../../includes/header.php"; ?>

<title>Manajemen User</title>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', sans-serif;
    }

    body {
        background: #f4fff7;
        color: #1f2937;
        padding: 25px;
    }

    /* ================= CARD ================= */

    .card {
        background: white;
        padding: 30px;
        border-radius: 24px;
        margin-bottom: 30px;
        border: 1px solid #dcfce7;
        box-shadow: 0 10px 30px rgba(22, 163, 74, 0.08);
    }

    /* ================= TITLE ================= */

    h1 {
        margin-bottom: 25px;
        color: #166534;
        font-size: 30px;
    }

    /* ================= FORM ================= */

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    input,
    select {
        width: 100%;
        padding: 14px;
        border-radius: 14px;
        border: 2px solid #dcfce7;
        background: #f0fdf4;
        color: #1f2937;
        font-size: 15px;
        transition: 0.3s;
    }

    input:focus,
    select:focus {
        outline: none;
        border-color: #22c55e;
        background: white;
    }

    button {
        padding: 14px;
        border: none;
        border-radius: 14px;
        background: #16a34a;
        color: white;
        font-weight: bold;
        cursor: pointer;
        transition: 0.3s;
        box-shadow: 0 4px 12px rgba(22, 163, 74, 0.2);
    }

    button:hover {
        background: #15803d;
        transform: translateY(-2px);
    }

    /* ================= TABLE ================= */

    .table-wrap {
        overflow: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        overflow: hidden;
        border-radius: 18px;
        background: white;
    }

    table th {
        background: #16a34a;
        color: white;
        font-weight: 600;
    }

    table th,
    table td {
        padding: 16px;
        border-bottom: 1px solid #dcfce7;
        text-align: left;
    }

    table td {
        color: #374151;
    }

    table tr:hover {
        background: #f0fdf4;
    }

    /* ================= BUTTON ================= */

    .back-btn {
        display: inline-block;
        margin-bottom: 20px;
        padding: 12px 18px;
        background: #16a34a;
        color: white;
        text-decoration: none;
        border-radius: 14px;
        font-weight: bold;
        transition: 0.3s;
        box-shadow: 0 4px 12px rgba(22, 163, 74, 0.2);
    }

    .back-btn:hover {
        background: #15803d;
        transform: translateY(-2px);
    }

    .delete-btn {
        background: #ef4444;
        color: white;
        padding: 10px 14px;
        border-radius: 12px;
        text-decoration: none;
        font-size: 13px;
        font-weight: bold;
        transition: 0.3s;
    }

    .delete-btn:hover {
        background: #dc2626;
    }

    .edit-btn {
        background: #22c55e;
        color: white;
        padding: 10px 14px;
        border-radius: 12px;
        text-decoration: none;
        font-size: 13px;
        font-weight: bold;
        transition: 0.3s;
    }

    .edit-btn:hover {
        background: #15803d;
    }

    /* ================= ACTION ================= */

    .action-btn {
        display: flex;
        gap: 10px;
    }

    /* ================= MOBILE USER CARD ================= */

    .mobile-user {
        display: none;
    }

    .user-card {
        background: white;
        border: 1px solid #dcfce7;
        border-radius: 22px;
        padding: 20px;
        margin-bottom: 16px;
        box-shadow: 0 8px 25px rgba(22, 163, 74, 0.08);
    }

    .user-card h3 {
        color: #166534;
        margin-bottom: 10px;
    }

    .user-card p {
        margin-bottom: 8px;
        color: #4b5563;
    }

    /* ================= ROLE BADGE ================= */

    .role-admin {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 999px;
        background: #dcfce7;
        color: #166534;
        font-size: 12px;
        font-weight: bold;
    }

    .role-kasir {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 999px;
        background: #dbeafe;
        color: #1d4ed8;
        font-size: 12px;
        font-weight: bold;
    }

    /* ================= DESKTOP NAV ================= */

    .bottom-nav {
        display: none;
    }

    /* ================= MOBILE ================= */

    @media (max-width: 768px) {

        body {
            padding: 15px;
            padding-bottom: 95px;
        }

        .card {
            padding: 20px;
            border-radius: 20px;
        }

        h1 {
            font-size: 24px;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        table {
            display: none;
        }

        .mobile-user {
            display: block;
        }

        input,
        select,
        button {
            min-height: 52px;
            font-size: 15px;
        }

        .action-btn {
            flex-direction: column;
        }

        .edit-btn,
        .delete-btn {
            width: 100%;
            text-align: center;
        }

        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: white;
            display: flex;
            justify-content: space-around;
            padding: 14px 0;
            z-index: 999;
            border-top: 1px solid #dcfce7;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.05);
        }

        .bottom-nav a {
            color: #15803d;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 12px;
            gap: 5px;
            font-weight: 600;
        }

        .bottom-nav i {
            font-size: 22px;
        }
    }
</style>

<body>

    <a href="dashboard.php" class="back-btn">
        ← Dashboard
    </a>

    <div class="card">

        <h1>Manajemen User</h1>

        <form method="POST">
            <div class="form-grid">

                <input
                    type="text"
                    name="username"
                    placeholder="Username">

                <input
                    type="password"
                    name="password"
                    placeholder="Password">

                <select name="role">
                    <option value="Admin">Admin</option>
                    <option value="Kasir">Kasir</option>
                </select>

                <button name="tambah">Tambah User</button>

            </div>
        </form>

    </div>

    <div class="card">

        <h1>Daftar User</h1>

        <div class="table-wrap">

            <table>

                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>

                <?php while ($d = mysqli_fetch_assoc($data)) { ?>

                <tr>

                    <td><?= $d['id_user']; ?></td>

                    <td><?= $d['username']; ?></td>

                    <td>
                        <?php if ($d['role'] == "Admin") { ?>
                            <span class="role-admin">Admin</span>
                        <?php } else { ?>
                            <span class="role-kasir">Kasir</span>
                        <?php } ?>
                    </td>

                    <td>
                        <div class="action-btn">

                            <a
                                href="edit_user.php?id=<?= $d['id_user']; ?>"
                                class="edit-btn">
                                Edit
                            </a>

                            <a
                                href="hapus_user.php?id=<?= $d['id_user']; ?>"
                                class="delete-btn"
                                onclick="return confirm('Hapus user ini?')">
                                Hapus
                            </a>

                        </div>
                    </td>

                </tr>

                <?php } ?>

            </table>

        </div>

        <!-- ================= MOBILE USER ================= -->

        <div class="mobile-user">

            <?php
            $dataMobile = mysqli_query($conn, "
                SELECT * FROM m_user
                ORDER BY id_user DESC
            ");

            while ($m = mysqli_fetch_assoc($dataMobile)) {
            ?>

            <div class="user-card">

                <h3><?= $m['username']; ?></h3>

                <p>ID : <?= $m['id_user']; ?></p>

                <p>
                    Role :
                    <?php if ($m['role'] == "Admin") { ?>
                        <span class="role-admin">Admin</span>
                    <?php } else { ?>
                        <span class="role-kasir">Kasir</span>
                    <?php } ?>
                </p>

                <div class="action-btn">

                    <a
                        href="edit_user.php?id=<?= $m['id_user']; ?>"
                        class="edit-btn">
                        Edit
                    </a>

                    <a
                        href="hapus_user.php?id=<?= $m['id_user']; ?>"
                        class="delete-btn"
                        onclick="return confirm('Hapus user ini?')">
                        Hapus
                    </a>

                </div>

            </div>

            <?php } ?>

        </div>

    </div>

    <!-- ================= BOTTOM NAV ================= -->

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

</body>

<?php include "../../includes/footer.php"; ?>
