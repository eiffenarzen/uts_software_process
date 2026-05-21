<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>SI-KASIR</title>

    <link
        href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css"
        rel="stylesheet">

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        html,
        body{
            overflow-x:hidden;
        }

        body{
            font-family:'Segoe UI',sans-serif;
            background:#f8fafc;
            color:#0f172a;
        }

        a{
            text-decoration:none;
        }

        button,
        input,
        select,
        textarea{
            font-family:'Segoe UI',sans-serif;
        }

    </style>

</head>
<body>
