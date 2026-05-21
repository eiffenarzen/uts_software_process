<?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Login SI-KASIR</title>

    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(
                135deg,
                #f0fdf4,
                #dcfce7,
                #bbf7d0
            );
            overflow: hidden;
            position: relative;
        }

        /* ================= BACKGROUND GLOW ================= */

        body::before {
            content: "";
            position: absolute;
            width: 350px;
            height: 350px;
            background: #22c55e;
            border-radius: 50%;
            filter: blur(150px);
            top: -100px;
            left: -100px;
            opacity: 0.35;
        }

        body::after {
            content: "";
            position: absolute;
            width: 350px;
            height: 350px;
            background: #86efac;
            border-radius: 50%;
            filter: blur(150px);
            bottom: -120px;
            right: -120px;
            opacity: 0.35;
        }

        /* ================= LOGIN CARD ================= */

        .login-card {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 430px;
            padding: 40px;
            border-radius: 28px;
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(34, 197, 94, 0.15);
            box-shadow: 0 20px 50px rgba(34, 197, 94, 0.18);
        }

        /* ================= LOGO ================= */

        .logo {
            text-align: center;
            margin-bottom: 35px;
        }

        .logo .icon {
            width: 90px;
            height: 90px;
            margin: auto;
            border-radius: 24px;
            background: linear-gradient(
                135deg,
                #22c55e,
                #16a34a
            );
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 18px;
            box-shadow: 0 10px 30px rgba(34, 197, 94, 0.3);
        }

        .logo .icon i {
            font-size: 42px;
            color: white;
        }

        .logo h1 {
            color: #166534;
            font-size: 38px;
            margin-bottom: 8px;
            letter-spacing: 1px;
        }

        .logo p {
            color: #4b5563;
            font-size: 15px;
        }

        /* ================= INPUT ================= */

        .input-group {
            margin-bottom: 20px;
            position: relative;
        }

        .input-group i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #16a34a;
            font-size: 20px;
        }

        .input-group input {
            width: 100%;
            padding: 16px 16px 16px 50px;
            border: 2px solid #d1fae5;
            outline: none;
            border-radius: 16px;
            background: white;
            color: #166534;
            font-size: 15px;
            transition: 0.3s;
        }

        .input-group input:focus {
            border: 2px solid #22c55e;
            box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.15);
        }

        /* ================= BUTTON ================= */

        .login-btn {
            width: 100%;
            padding: 16px;
            border: none;
            border-radius: 16px;
            background: linear-gradient(
                135deg,
                #22c55e,
                #16a34a
            );
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(34, 197, 94, 0.3);
        }

        /* ================= FOOTER ================= */

        .footer {
            margin-top: 25px;
            text-align: center;
            color: #4b5563;
            font-size: 13px;
        }

        /* ================= MOBILE ================= */

        @media (max-width: 768px) {

            body {
                padding: 20px;
            }

            .login-card {
                padding: 30px 22px;
            }

            .logo h1 {
                font-size: 30px;
            }

            .logo .icon {
                width: 75px;
                height: 75px;
            }
        }
    </style>
</head>

<body>
    
    <?php if(isset($_SESSION['error'])) { ?>

    <script>
        alert("<?= $_SESSION['error']; ?>");
    </script>

    <?php unset($_SESSION['error']); } ?>

    <form
        class="login-card"
        method="POST"
        action="proses_login.php">

        <!-- ================= LOGO ================= -->

        <div class="logo">

            <div class="icon">
                <i class='bx bxs-store'></i>
            </div>

            <h1>SI-KASIR</h1>

            <p>Sistem Kasir Modern & Inventory</p>

        </div>

        <!-- ================= USERNAME ================= -->

        <div class="input-group">
            <i class='bx bxs-user'></i>
            <input
                type="text"
                name="username"
                placeholder="Masukkan username"
                required>
        </div>

        <!-- ================= PASSWORD ================= -->

        <div class="input-group">
            <i class='bx bxs-lock-alt'></i>
            <input
                type="password"
                name="password"
                placeholder="Masukkan password"
                required>
        </div>

        <!-- ================= BUTTON ================= -->

        <button
            type="submit"
            class="login-btn">
            LOGIN
        </button>

        <!-- ================= FOOTER ================= -->

        <div class="footer">
            Created By Eiffen Arjen
        </div>

    </form>

</body>
</html>
