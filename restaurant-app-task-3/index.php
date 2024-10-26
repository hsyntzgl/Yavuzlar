<?php
if (file_exists('setup.php')) {
    header('Location: setup.php');
    exit; 
}

if(session_status() == PHP_SESSION_NONE){
    session_start();
}

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
        $results = Restaurants::getRestaurants();
        if (empty($results)) {
        ?><h2>Yok</h2><?php
                    } else {

                    foreach ($results as $result) {
                        echo '<div class="card" style="width: 18rem;">
                <img class="card-img-top" src="'. $result['image_path'] .'" alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title">' . $result['name'] . '</h5>
                    <p class="card-text">' . $result['description'] .'</p>
                    <a href="/customer-panel/restaurants.php?id='. $result['id'] .'" class="btn btn-primary">Sipariş Ver</a>
                </div>
            </div>';
                    }
                        ?> <?php
                    }
                    ?>


    </div>

</body>

</html>