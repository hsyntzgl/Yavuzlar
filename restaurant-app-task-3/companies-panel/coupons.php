<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SESSION['role'] != 'company') {
    echo "<script>alert('Bu sayfayı görme yetkiniz yok'); history.back();</script>";
    exit;
}


include (__DIR__) . '/../src/restaurants.php';
include (__DIR__) . '/../src/coupons.php';
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
        <h1>Kupon Ekle</h1>


        <div class="login-form">
            <form action="/src/coupons.php" method="POST">
                <div class="form-group">
                    <label for="name">Kupon Adı</label>
                    <input type="text" name="name" class="form-control" id="name" placeholder="Kupon Kodu" required>
                </div>
                <div class="form-group">
                    <label for="discount">İndirim Tutarı</label>
                    <input type="text" name="discount" class="form-control" id="discount" placeholder="İndirim Tutarı" required>
                </div>
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="company_id" value="<?php echo $_SESSION['company_id'] ?>">
                <button type="submit" class="btn btn-primary">Kuponu Ekle</button>
            </form>
        </div>



        <h1>KUPONLAR</h1>
    </center>

    <?php

    $coupons = Coupons::getCoupons();
    echo '<div class="cards-list">';
    if ($coupons) {
        foreach ($coupons as $coupon) {
            echo  '<div class="card" style="width: 18rem;">
  <div class="card-body">
    <h5 class="card-title">' . $coupon['name'] . '</h5>
    <p class="card-text">İndirim Tutarı: ' . $coupon['discount'] . ' TL</p>
    <a href="/src/coupons.php?action=delete&id=' . $coupon['id'] . '" class="btn btn-danger">Sil</a>

  </div>
</div>';
        }
        echo '</div>';
    } else {
        echo "<h2>Kupon Bulunamadı</h2>";
    }

    ?>



</body>

</html>