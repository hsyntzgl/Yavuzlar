<?php

if (!isset($_GET['id'])) {
    echo "<script>alert('Eksik Parametre')</script>";
    exit;
}


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SESSION['role'] == 'admin' || $_SESSION['company_id'] == $_GET['id']) {
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../style.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <title>Document</title>
    </head>

    <body>

        <?php include $_SERVER['DOCUMENT_ROOT'] . '/static-elements/nav.php'; ?>

        <center>
            <h1>SİPARİŞ YÖNETİMİ</h1>
        </center>
    </body>

    </html>

<?php
} else {
    echo "<script>alert('Yetkiniz Yok')</script>";
    exit;
}


?>