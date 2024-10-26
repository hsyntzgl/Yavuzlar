<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


if ($_SESSION['role'] != "customer") {
    echo "<script>alert('Bu sayfayı görme yetkiniz yok'); history.back();</script>";
    exit;
}

if (!isset($_GET['id'])) {
    echo "<script>alert('Yorum bilgisi bulunamadı'); history.back();</script>";
    exit;
}

include (__DIR__) . '/../src/restaurants.php';

$comment = Restaurants::getRestaurantComment($_GET['id']);

if ($comment['user_id'] != $_SESSION['id']) {
    echo "<script>alert('Bu sayfayı görme yetkiniz yok'); history.back();</script>";
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <title>Yorum Düzenle</title>

<body>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/static-elements/nav.php'; ?>
    <form action="/src/restaurants.php" method="post">
        <section>
            <div class="container my-5 py-5 text-body">
                <div class="row d-flex justify-content-center">
                    <div class="col-md-10 col-lg-8 col-xl-6">
                        <div class="card">
                            <div class="card-body p-4">
                                <div class="d-flex flex-start w-100">
                                    <div class="w-100">
                                        <h5>Yorum Düzenle</h5>
                                        <ul data-mdb-rating-init class="rating mb-3" data-mdb-toggle="rating">
                                            Yıldız:
                                            <input type="number" name="star" id="" min=1 max=5 value="<?= $comment['score']?>">
                                        </ul>
                                        <div data-mdb-input-init class="form-outline">
                                            <input type="text" name="title" id="title" placeholder="Başlık" value="<?= $comment['title'] ?>">
                                        </div>
                                        <div data-mdb-input-init class="form-outline">
                                            <textarea class="form-control" id="textAreaExample" rows="4" name="comment"><?= $comment['description'] ?></textarea>
                                        </div>
                                        <input type="hidden" name="action" value="updateComment">
                                        <input type="hidden" name="id" value="<?= $comment['id'] ?>">
                                        <input type="hidden" name="restaurant_id" value="<?= $comment['restaurant_id'] ?>">
                                        <div class="d-flex justify-content-center mt-3">
                                            <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-success">
                                                Yorumu Güncelle <i class="fas fa-long-arrow-alt-right ms-1"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </form>
    </head>

</body>

</html>