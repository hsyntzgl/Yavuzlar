<?php
include (__DIR__) . '/../src/restaurants.php';
include (__DIR__) . '/../src/companies.php';
include (__DIR__) . '/../src/foods.php';


if (isset($_GET['id'])) {



?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../style.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <title>Restoran Ayrıntıları</title>
    </head>

    <body>

        <?php include $_SERVER['DOCUMENT_ROOT'] . '/static-elements/nav.php'; ?>
        <?php $restaurants = Restaurants::getRestaurantsWithCompanyId($_GET['id']);
        ?>
        <center>
            <h1>Restoranlar</h1>

            <?php



            if ($restaurants) {
                echo '<div class="cards-list">';
                foreach ($restaurants as $restaurant) {
                    echo    ' <div class="card" style="width: 18rem;">
<img class="card-img-top" src="' . $restaurant['image_path'] . '" alt="Card image cap">
<div class="card-body">
<h5 class="card-title">' . $restaurant['name'] . '</h5>
<p class="card-text">' . $restaurant['description'] . '</p>
<a href="/companies-panel/restaurant-details.php?id=' . $restaurant['id'] . '" class="btn btn-primary">Ayrıntılar</a>
<a href="/src/restaurants.php?action=delete&id=' . $restaurant['id'] . '" class="btn btn-danger">Sil</a>
</div>
</div>';
                }

                echo '</div>';
            } else {
                echo "<h2>Restoran Bulunamadı</h2>";
            }

            ?>
        </center>
    </body>

    </html>
<?php
} else {
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
            <h1>Restoran Ekle</h1>


            <div class="login-form">
                <form action="../src/restaurants.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name">Restaurant Adı</label>
                        <input type="text" name="name" class="form-control" id="name" placeholder="İsim" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Açıklama</label>
                        <input type="text" name="description" class="form-control" id="description" placeholder="Açıklama" required>
                    </div>
                    <div class="form-group">
                        <label for="image_path">Resim Yükle</label>
                        <input type="file" name="image_path" class="form-control" id="image_path" placeholder="Resim Yükle" accept="image/*" required>
                    </div>
                    <div class="form-group">
                        <label for="company_id">Şirket Seçin</label>
                        <select name="company_id" class="form-control" id="company_id" required>
                            <option value="">Şirket Seçin</option>
                            <?php
                            $companies = Companies::getCompanies();
                            foreach ($companies as $company): ?>
                                <option value="<?php echo $company['id']; ?>">
                                    <?php echo $company['name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <input type="hidden" name="action" value="add">
                    <button type="submit" class="btn btn-primary">Restoranı Ekle</button>
                </form>
            </div>



            <h1>Restoranlar</h1>
        </center>

        <?php

        $restaurants = Restaurants::getRestaurants();

        if ($restaurants) {
            echo '<div class="cards-list">';
            foreach ($restaurants as $restaurant) {
                echo    ' <div class="card" style="width: 18rem;">
  <img class="card-img-top" src="' . $restaurant['image_path'] . '" alt="Card image cap">
  <div class="card-body">
    <h5 class="card-title">' . $restaurant['name'] . '</h5>
    <p class="card-text">' . $restaurant['description'] . '</p>
    <a href="restaurants.php?id=' . $restaurant['id'] . '" class="btn btn-primary">Ayrıntılar</a>
    <a href="restaurants.php?action=delete&id=' . $restaurant['id'] . '" class="btn btn-danger">Sil</a>
  </div>
</div>';
            }

            echo '</div>';
        } else {
            echo "<h2>Restoran Bulunamadı</h2>";
        }

        ?>



    </body>

    </html>
<?php
}

?>