<?php


if (isset($_GET['id'])) {
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/style.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <title>Document</title>
    </head>

    <body>

        <?php include $_SERVER['DOCUMENT_ROOT'] . '/static-elements/nav.php'; ?>
        <?php
        include(__DIR__ . '/../../src/restaurants.php');
        include(__DIR__ . '/../../src/foods.php');

        $company_id = $_GET['company_id'];
        $food_id = $_GET['id'];
        $food = Foods::getFoodWithId($food_id);

        ?>

        <center>
            <h1>Yemek Güncelle</h1>

            <div class="login-form">
                <form action="/src/foods.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name">Yemek Adı</label>
                        <input type="text" name="name" class="form-control" id="name" placeholder="İsim" required value="<?php echo $food['name']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="description">Açıklama</label>
                        <input type="text" name="description" class="form-control" id="description" placeholder="Açıklama" required value="<?php echo $food['description']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="image_path">Mevcut Resim</label><br>
                        <?php if (!empty($food['image_path'])): ?>
                            <img src="<?php echo htmlspecialchars($food['image_path']); ?>" alt="Mevcut Resim" width="150"><br><br>
                        <?php endif; ?>
                        <label for="image_path">Yeni Resim Yükle</label>
                        <input type="file" name="image_path" class="form-control" id="image_path" accept="image/*">
                    </div>
                    <div class="form-group">
                        <label for="price">Fiyat</label>
                        <input type="number" step="0.01" name="price" class="form-control" id="price" placeholder="Fiyat" required value="<?php echo $food['price']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="discount">İndirim (%)</label>
                        <input type="number" step="0.01" name="discount" class="form-control" id="discount" placeholder="İndirim" required value="<?php echo $food['discount']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="restaurant_id">Restoran Seçin</label>
                        <select name="restaurant_id" class="form-control" id="restaurant_id" required>
                            <option value="">Restoran Seçin</option>
                            <?php
                            $restaurants = Restaurants::getRestaurantsWithCompanyId($company_id);
                            foreach ($restaurants as $restaurant) {
                                $selected = $restaurant['id'] == $food['restaurant_id'] ? 'selected' : '';
                                echo '<option value="' . $restaurant['id'] . '" ' . $selected . '>' . $restaurant['name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="company_id" value="<?php echo $company_id; ?>">
                    <button type="submit" class="btn btn-primary">Yemek Güncelle</button>
                </form>
            </div>
        </center>



    </body>

    </html>

<?php
}
