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
    <?php
    include(__DIR__ . '/../src/restaurants.php');
    include(__DIR__ . '/../src/foods.php');

    $company_id = $_GET['id'];

    ?>

    <center>
        <h1>Yemek Ekle</h1>

        <div class="login-form">
            <form action="/src/foods.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Yemek Adı</label>
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
                    <label for="price">Fiyat</label>
                    <input type="number" step="0.01" name="price" class="form-control" id="price" placeholder="Fiyat" required>
                </div>
                <div class="form-group">
                    <label for="discount">İndirim (%)</label>
                    <input type="number" step="0.01" name="discount" class="form-control" id="discount" placeholder="İndirim" required>
                </div>
                <div class="form-group">
                    <label for="restaurant_id">Restoran Seçin</label>
                    <select name="restaurant_id" class="form-control" id="restaurant_id" required>
                        <option value="">Restoran Seçin</option>
                        <?php
                        $restaurants = Restaurants::getRestaurantsWithCompanyId($company_id);
                        foreach ($restaurants as $restaurant) {
                            echo '<option value="' . $restaurant['id'] . '">' . $restaurant['name'] . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <input type="hidden" name="action" value="add">
                <input type="hidden" name="company_id" value=<?php echo $company_id ?>>
                <button type="submit" class="btn btn-primary">Yemek Ekle</button>
            </form>
        </div>
        <hr>
        <?php

        foreach ($restaurants as $restaurant) {

            echo '<h4>' . $restaurant['name'] . ' Restoranı</h4>';

            $foods = Foods::getRestaurantFoods($restaurant['id']);

            if ($foods) {
                echo '<h4>Yiyecekler:</h4>';
                foreach ($foods as $food) {
                    echo '<div class="food-card" style="margin: 10px; border: 1px solid #ccc; border-radius: 5px; padding: 10px;">
                    <h6>' . $food['name'] . '</h6>
                    <p>' . $food['description'] . '</p>
                    <p><strong>Fiyat: ' . $food['price'] . ' TL</strong></p>
                    <a href="/companies-panel/foods/food.php?id=' . $food['id'] . '&company_id='. $company_id .'" class="btn btn-secondary">Düzenle</a>
                    <a href="/src/foods.php?action=delete&id=' . $food['id'] . '" class="btn btn-danger">Sil</a>
                </div>';
                }
            } else {
                echo "<center><h4>Yemek Bulunamadı</h4><hr></center>";
            }

            echo '</div>';
        }


        ?>
    </center>


</body>

</html>