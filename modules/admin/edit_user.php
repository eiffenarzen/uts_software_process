<?php
require "../../config/koneksi.php";
require "../../includes/auth_helper.php";
require "../../includes/header.php";

/** @var mysqli $conn */

adminOnly();

$id = $_GET['id'];

$data = mysqli_fetch_assoc(
    mysqli_query($conn, "
        SELECT * FROM m_user
        WHERE id_user = '$id'
    ")
);

if (!$data) {

    echo "
    <script>
        alert('User tidak ditemukan');
        window.location = 'user.php';
    </script>
    ";

    exit;
}

if (isset($_POST['update'])) {

    $username = trim($_POST['username']);
    $role     = $_POST['role'];

    if (empty($username)) {

        echo "
        <script>
            alert('Username wajib diisi');
        </script>
        ";

    } else {

        mysqli_query($conn, "
            UPDATE m_user
            SET
                username = '$username',
                role     = '$role'
            WHERE id_user = '$id'
        ");

        if (!empty($_POST['password'])) {

            $password = password_hash(
                $_POST['password'],
                PASSWORD_DEFAULT
            );

            mysqli_query($conn, "
                UPDATE m_user
                SET password = '$password'
                WHERE id_user = '$id'
            ");
        }

        echo "
        <script>
            alert('User berhasil diupdate');
            window.location = 'user.php';
        </script>
        ";
    }
}
?>

<style>
    .page-wrapper {
        padding: 30px;
    }

    .card {
        max-width: 550px;
        margin: auto;
        background: white;
        padding: 35px;
        border-radius: 28px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.06);
    }

    h1 {
        margin-bottom: 28px;
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
        color: #334155;
        font-weight: 600;
        font-size: 15px;
    }

    input,
    select {
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
    select:focus {
        outline: none;
        border: 1px solid #16a34a;
        background: white;
        box-shadow: 0 0 0 4px rgba(22, 163, 74, 0.15);
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

    input::placeholder {
        color: #94a3b8;
    }

    @media (max-width: 768px) {

        .page-wrapper {
            padding: 15px;
            padding-bottom: 90px;
        }

        .card {
            padding: 22px;
            border-radius: 22px;
        }

        h1 {
            font-size: 26px;
        }

        input,
        select,
        .btn {
            min-height: 52px;
            font-size: 15px;
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

<div class="page-wrapper">

    <div class="card">

        <a href="user.php" class="back-btn">
            ← Kembali
        </a>

        <h1>Edit User</h1>

        <form method="POST">

            <div class="input-group">
                <label>Username</label>

                <input
                    type="text"
                    name="username"
                    value="<?= $data['username']; ?>"
                    required>
            </div>

            <div class="input-group">
                <label>Password Baru</label>

                <input
                    type="password"
                    name="password"
                    placeholder="Kosongkan jika tidak diganti">
            </div>

            <div class="input-group">
                <label>Role</label>

                <select name="role">

                    <option
                        value="Admin"
                        <?= $data['role'] == "Admin" ? "selected" : ""; ?>>

                        Admin

                    </option>

                    <option
                        value="Kasir"
                        <?= $data['role'] == "Kasir" ? "selected" : ""; ?>>

                        Kasir

                    </option>

                </select>
            </div>

            <button
                class="btn"
                name="update">

                Update User

            </button>

        </form>

    </div>

</div>

<?php require "../../includes/footer.php"; ?>
