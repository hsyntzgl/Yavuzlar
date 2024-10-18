<?php
include(__DIR__ . '/../src/companies.php');


if (isset($_GET['id'])) {

    include(__DIR__ . '/../src/restaurants.php');

    $company = Companies::getCompany($_GET['id']);
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../style.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <title>Firma Ayrıntıları</title>
    </head>

    <body>

        <?php include $_SERVER['DOCUMENT_ROOT'] . '/static-elements/nav.php'; ?>

        <center>

            <?php

            echo '<div class="login-form">
                <form action="/src/companies.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name">Firma Adı</label>
                        <input type="text" name="name" class="form-control" id="name" value=' . $company['name'] . '  required>
                        
                    </div>
                    <div class="form-group">
                        <label for="description">Açıklama</label>
                        <input type="text" name="description" class="form-control" id="description" value=' . $company['description'] . ' required>
                    </div>
                    <div class="form-group">
                        <label for="logo_path">Logo Yükle</label>
                        <img src=' . $company['logo_path'] . '>
                        <input type="file" name="logo_path" class="form-control" id="logo_path" placeholder="Logo Yükle" accept="image/*" required>
                    </div>
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id" value=' . $company['id'] . '>
                    <button type="submit" class="btn btn-primary">Güncelle</button>
                </form>
            </div>';

            ?>
            <hr>
            <br>
            <h1>Restoranlar</h1>

            <?php

            $restaurants = Restaurants::getRestaurantsWithCompanyId($company['id']);

            if ($restaurants) {
                echo '<div class="cards-list">';
                foreach ($restaurants as $restaurant) {
                    echo    ' <div class="card" style="width: 18rem;">
      <img class="card-img-top" src="' . $restaurant['image_path'] . '" alt="Card image cap">
      <div class="card-body">
        <h5 class="card-title">' . $restaurant['name'] . '</h5>
        <p class="card-text">' . $restaurant['description'] . '</p>
        <a href="/admin-panel/restaurants.php?id=' . $restaurant['id'] . '" class="btn btn-primary">Ayrıntılar</a>
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
            <h1>Firma Ekle</h1>


            <div class="login-form">
                <form action="../src/companies.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name">Firma Adı</label>
                        <input type="text" name="name" class="form-control" id="name" placeholder="Firma Adı" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Açıklama</label>
                        <input type="text" name="description" class="form-control" id="description" placeholder="Açıklama" required>
                    </div>
                    <div class="form-group">
                        <label for="logo_path">Logo Yükle</label>
                        <input type="file" name="logo_path" class="form-control" id="logo_path" placeholder="Logo Yükle" accept="image/*" required>
                    </div>
                    <input type="hidden" name="action" value="add">
                    <button type="submit" class="btn btn-primary">Firma Ekle</button>
                </form>
            </div>



            <h1>Firmalar</h1>
        </center>

        <?php

        $companies = Companies::getCompanies();

        if ($companies) {
            echo '<div class="cards-list">';
            foreach ($companies as $company) {
                echo     '<div class="card" style="width: 18rem;">
  <img src="' . $company['logo_path'] . '" class="card-img-top" alt="...">
  <div class="card-body">
    <h5 class="card-title">' . $company['name'] . '</h5>
    <p class="card-text">' . $company['description'] . '</p>
    <a href="companies.php?id=' . $company['id'] . '" class="btn btn-primary">Ayrıntılar</a>
    <a href="/src/companies.php?action=delete&company_id=' . $company['id'] . '" class="btn btn-primary">Sil</a>
  </div>
</div>';
            }
            echo '</div>';
        } else {
            echo "<h2>Firma Bulunamadı</h2>";
        }

        ?>



    </body>

    </html>

<?php
}

?>