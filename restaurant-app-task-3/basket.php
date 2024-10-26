<?php

if (isset($_GET['code']) && isset($_GET['discount'])) {



  include (__DIR__) . '/src/coupons.php';

  $coupon = [
    'code' => $_GET['code'],
    'discount' => $_GET['discount']
  ];




  $result = Coupons::checkCoupon($coupon);

    if ($result == -2) {
    echo "<script>alert('Kupon Bilgileri Uyuşmuyor');
                window.location.href = '/basket.php';
            </script>";
    exit;
  } elseif ($result == -1) {
    echo "<script>alert('Kupon Bulunamadı');
                window.location.href = '/basket.php';
            </script>";
    exit;
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

  <title>Document</title>
</head>

<body>

  <?php include $_SERVER['DOCUMENT_ROOT'] . '/static-elements/nav.php'; ?>

  <?php
  include (__DIR__) . '/src/basket.php';
  include (__DIR__) . '/src/foods.php';

  if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }

  $results = Basket::getUserBasket($_SESSION['id']);

  if (empty($results)) {
  ?>
    <center>
      <h2>Sepet Boş</h2>
    </center>

  <?php
  } else {

  ?>
    <section class="h-100">
      <div class="container h-100 py-5">
        <div class="row d-flex justify-content-center align-items-center h-100">
          <div class="col-10">

            <div class="d-flex justify-content-between align-items-center mb-4">
              <h3 class="fw-normal mb-0">Sepet</h3>
              <div>
                <p class="mb-0"><span class="text-muted">Cüzdan: 5000 </span> </p>
              </div>
            </div>

            <?php

            $foods = []; 
            $total_price = 0;

            $order_items = [];

            foreach ($results as $result) {

              $food = Foods::getFoodWithId($result['food_id']);

              $order_item = [
                'food_id' => $food['id'],
                'quantity' => $result['quantity'],
                'price' => $food['price'] * $result['quantity']
              ];

              array_push($order_items, $order_item);

              $total_price += $food['price'] * $result['quantity'];
            ?>
              <div class="card rounded-3 mb-4">
                <div class="card-body p-4">
                  <div class="row d-flex justify-content-between align-items-center">
                    <div class="col-md-2 col-lg-2 col-xl-2">
                      <img
                        src="<?= $food['image_path'] ?>"
                        class="img-fluid rounded-3" alt="Resim Bulunamadı">
                    </div>
                    <div class="col-md-3 col-lg-3 col-xl-3">
                      <p class="lead fw-normal mb-2"><?= $food['name'] ?></p>
                      <p><span class="text-muted">Not: </span><?= $result['note'] ?></p>
                    </div>
                    <div class="col-md-3 col-lg-3 col-xl-2 d-flex">
                      <p class="lead fw-normal mb-2">Adet: </p>
                      <p class="lead fw-normal mb-2"><?= $result['quantity'] ?></p>
                    </div>
                    <div class="col-md-3 col-lg-2 col-xl-2 offset-lg-1">
                      <h5 class="mb-0"><?= $food['price'] * $result['quantity'] ?> TL</h5>
                    </div>
                    <div class="col-md-1 col-lg-1 col-xl-1 text-end">
                      <a href="/src/basket.php?id=<?= $result['id'] ?>" class="text-danger"><i class="fas fa-trash fa-lg"></i></a>
                    </div>
                  </div>
                </div>
              </div>

            <?php
            }

            ?>
            <form action="/src/coupons.php" method="post">
              <div class="card mb-4">
                <div class="card-body p-4 d-flex flex-row">
                  <div data-mdb-input-init class="form-outline flex-fill">
                    <input type="text" id="form1" name="code" class="form-control form-control-lg" />
                    <label class="form-label" for="form1">Kupon Kodu</label>
                  </div>
                  <input type="hidden" name="action" value="checkCoupon">
                  <input type="hidden" name="restaurant_id" value="<?= $food['restaurant_id'] ?>">
                  <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-outline-warning btn-lg ms-3">Ekle</button>
                </div>
              </div>
            </form>
            <div class="card">
              <div class="card-body">

                <div class="col-md-9 col-lg-4 col-xl-11 text-end">
                  Toplam Tutar: <?= $total_price ?> TL
                </div>

                <?php
                if (isset($_GET['discount'])) {
                ?>
                  <div class="col-md-9 col-lg-4 col-xl-11 text-end">
                    Kupon İndirimi: 50 TL
                  </div>
                  <div class="col-md-9 col-lg-4 col-xl-11 text-end">
                    Ödenecek Tutar: 50 TL
                  </div>
                <?php
                }
                ?>


              </div>
            </div>
            <form action="/src/orders.php" method="post">
              <div class="card">
                <div class="card-body">

                <?php

                $data = [
                  'user_id' => $_SESSION['id'],
                  'total_price' => $total_price
                ];

                ?>
                
                <input type="hidden" name="data" value="<?= htmlspecialchars(json_encode($data)) ?>">
                <input type="hidden" name="order_items" value="<?= htmlspecialchars(json_encode($order_items)) ?>">
                  
                <input type="hidden" name="action" value="add">

                  <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-warning btn-block btn-lg">Sipariş Ver</button>

                </div>
              </div>
            </form>
          <?php
        }
          ?>



          </div>
        </div>
      </div>
    </section>
    <?php
    ?>

</body>

</html>