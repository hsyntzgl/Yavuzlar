<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SESSION['role'] != 'customer') {
    echo "<script>alert('Bu sayfayı görme yetkiniz yok'); history.back();</script>";
    exit;
}


if (!isset($_GET['id'])) {
    echo "<script>alert('Restoran bilgisi bulunamadı'); history.back();</script>";
    exit;
}

include (__DIR__) . '/../src/restaurants.php';

$restaurant_id = $_GET['id'];

if (!Restaurants::checkIsAlreadyDeleted($restaurant_id)) {
    echo "<script>alert('Restoran Sistemden Silinmiştir'); history.back();</script>";
    exit;
}

include (__DIR__) . '/../src/foods.php';

$foods = Foods::getRestaurantFoods($restaurant_id);

if (empty($foods)) {
    echo "<script>alert('Restoranda ürün bulunmamaktadır'); history.back();</script>";
    exit;
}

$restaurant = Restaurants::getRestaurantWithId($restaurant_id);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <title><?php $restaurant['name'] ?></title>
</head>

<body>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/static-elements/nav.php'; ?>

    <center>

        <div class="restaurants">

            <?php

            foreach ($foods as $food) {

                echo '<form action="/src/basket.php" method="POST">
        <div class="card" style="width: 18rem;">
          <img src="' . $food['image_path'] . '" class="card-img-top" alt="No photo">
            <div class="card-body">
                <h5 class="card-title">' . $food['name'] . '</h5>
            <p class="card-text">' . $food['description'] .  '</p>
            <label for="quantity">Adet</label>
            <input type="number" name="quantity" value="1">
            <label for="note">Not</label>
            <input type="text" name="note"> 
            <input type="hidden" name="food_id" value=' . $food['id'] . '> 
            <input type="hidden" name="user_id" value=' . $_SESSION['id'] . '>
            <input type="hidden" name="restaurant_id" value=' . $_GET['id'] . '>
            <input type="hidden" name="action" value="add">
            <button type=submit class="btn btn-primary">Sepete Ekle</a>
            </div>
        </div></form>';
            }

            ?>

        </div>



        <div class="comments">
            <hr>
            <h2>Yorumlar</h2>


            <form action="/src/restaurants.php" method="post">
                <section>
                    <div class="container my-5 py-5 text-body">
                        <div class="row d-flex justify-content-center">
                            <div class="col-md-10 col-lg-8 col-xl-6">
                                <div class="card">
                                    <div class="card-body p-4">
                                        <div class="d-flex flex-start w-100">
                                            <div class="w-100">
                                                <h5>Yorum Ekle</h5>
                                                <ul data-mdb-rating-init class="rating mb-3" data-mdb-toggle="rating">
                                                    Yıldız:
                                                    <input type="number" name="star" id="" min=1 max=5>
                                                </ul>
                                                <div data-mdb-input-init class="form-outline">
                                                    <input type="text" name="title" id="title" placeholder="Başlık">
                                                </div>
                                                <div data-mdb-input-init class="form-outline">
                                                    <textarea class="form-control" id="textAreaExample" rows="4" name="comment" placeholder="Yorumunuz..."></textarea>
                                                </div>
                                                <input type="hidden" name="action" value="addComment">
                                                <input type="hidden" name="restaurant_id" value="<?= $restaurant_id ?>">
                                                <input type="hidden" name="user_id" value="<?= $_SESSION['id'] ?>">
                                                <div class="d-flex justify-content-center mt-3">
                                                    <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-success">
                                                        Yorumu Gönder <i class="fas fa-long-arrow-alt-right ms-1"></i>
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

            <?php

            $comments = Restaurants::getRestaurantComments($restaurant_id);

            if (empty($comments)) {
            ?>
                <h2>Yorum bulunamadı</h2>
            <?php
            } else {

            ?>
                <section class="old-comments" style="background-color: #f7f6f6;">


                    <?php

                    include (__DIR__) . '/../src/users.php';

                    foreach ($comments as $comment) {
                        $user = Users::getUserById($comment['user_id']);
                    ?>
                        <div class="comment-box">

                            <div class="header">
                                <span class="surname"><?= $user['surname'] ?></span>
                                <span class="title"><?= $comment['title'] ?></span>
                                <span class="created_at"><?= $comment['created_at'] ?></span>
                            </div>
                            <div class="comment">
                                <span class="description"><?= $comment['description'] ?></span>
                                <span class="score"><?= $comment['score'] ?></i></span>
                            </div>
                            <?php
                            if ($_SESSION['id'] == $user['id']) {
                            ?>
                                <a href="/customer-panel/comments.php?id=<?= $comment['id']?>" data-mdb-button-init data-mdb-ripple-init class="btn btn-success">
                                    Yorumu Düzenle <i class="fas fa-long-arrow-alt-right ms-1"></i>
                                </a>
                            <?php
                            }
                            ?>


                        </div>



                    <?php

                    }
                    ?>
                </section>
            <?php
            }
            ?>


        </div>
    </center>

</body>

</html>