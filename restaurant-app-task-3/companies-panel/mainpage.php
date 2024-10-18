<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Anasayfa</title>
</head>

<body>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/static-elements/nav.php'; ?>

    <center>
        <h1>FİRMA PANEL ANASAYFA</h1>
    </center>

    <?php 

    $company_id = $_SESSION['company_id'];

    ?>

    <a href="foods.php?id=<?php echo $company_id ?>">Tüm yemekler</a>
    <a href="restaurants.php?id=<?php echo $company_id ?>">Restoranlar</a>
    <a href="coupons.php">Kupon yönetimi</a>
</body>

</html>