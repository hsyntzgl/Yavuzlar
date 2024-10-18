<?php
/*
if (file_exists('setup.php')) {
    header('Location: setup.php');
    exit; 
}
*/

include 'src/restaurants.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Yavuzlar Sepeti</title>
</head>

<body>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/static-elements/nav.php'; ?>

    <div class="banner">
        <h1>Yemek ya da market, tüm ihtiyaçların kapında</h1>
        <div>
            <form class="form-inline">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>
        </div>

    </div>


    <h2 id="restaurant-title">Restoranlar</h2>

    <div class="restaurants">
        <?php
        $result = Restaurants::getRestaurants();
        if ($result == null) {
        ?><h2>Yok</h2><?php
                    } else {
                        ?> <div class="card" style="width: 18rem;">
                <img class="card-img-top" src="" alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title">Card title</h5>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    <a href="#" class="btn btn-primary">Go somewhere</a>
                </div>
            </div><?php
                    }
                    ?>


    </div>

</body>

</html>