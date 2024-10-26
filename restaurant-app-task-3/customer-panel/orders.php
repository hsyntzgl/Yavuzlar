<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
} else {
    $user_id = $_SESSION['id'];
}



if ($_SESSION['role'] === 'admin' || $_SESSION['id'] == $_GET['user_id']) {

    include (__DIR__) . '/../src/orders.php';

    if (isset($_GET['option'])) {
        if ($_GET['option'] == 'active') {
?>

            <!DOCTYPE html>
            <html lang="en">

            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" href="../style.css">
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
                <title>Aktif Siparişler</title>
            </head>

            <body>

                <?php include $_SERVER['DOCUMENT_ROOT'] . '/static-elements/nav.php'; ?>

                <center>

                    <?php

                    $getOrders = Orders::getAllOrdersWithUserId($user_id);

                    if (empty($getOrders)) {
                        echo "<h4>Aktif Sipariş Bulunamadı</h4>";
                    } else {
                    ?>
                        <table>
                            <thead>
                                <tr>
                                    Aktif Siparişler
                                </tr>
                                <tr>
                                    <td>
                                        Sipariş Durumu
                                    </td>
                                    <td>
                                        Toplam Ücret
                                    </td>
                                    <td>
                                        Oluşturulma Tarihi
                                    </td>
                                </tr>
                            </thead>
                            <tbody>



                            <?php

                            foreach ($getOrders as $order) {

                                if ($order['status'] != 'delivered') {
                                    echo "<tr><td>" . $order['order_status'] . "</td><td>" . $order['total_price'] . "</td><td>" . $order['created_at'] . "</td></tr>";
                                }
                            }
                        }



                            ?>
                            </tbody>
                        </table>

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

                    <?php

                    $getOrders = Orders::getAllOrdersWithUserId($user_id);

                    if (empty($getOrders)) {
                        echo "<h4>Aktif Sipariş Bulunamadı</h4>";
                    } else {
                    ?>
                        <table>
                            <thead>
                                <tr>
                                    Spişariler
                                </tr>
                                <tr>
                                    <td>
                                        Sipariş Durumu
                                    </td>
                                    <td>
                                        Toplam Ücret
                                    </td>
                                    <td>
                                        Oluşturulma Tarihi
                                    </td>
                                </tr>
                            </thead>
                            <tbody>

                            <?php

                            foreach ($getOrders as $order) {

                                echo "<tr><td>" . $order['order_status'] . "</td><td>" . $order['total_price'] . "</td><td>" . $order['created_at'] . "</td></tr>";
                            }
                        }

                            ?>
                            </tbody>
                        </table>

                </center>

            </body>

            </html>
        <?php
        }
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

                <?php

                $getOrders = Orders::getAllOrdersWithUserId($user_id);

                if (empty($getOrders)) {
                    echo "<h4>Aktif Sipariş Bulunamadı</h4>";
                } else {
                ?>
                    <table>
                        <thead>
                            <tr>
                                Siparişler
                            </tr>
                            <tr>
                                <td>
                                    Sipariş Durumu
                                </td>
                                <td>
                                    Toplam Ücret
                                </td>
                                <td>
                                    Oluşturulma Tarihi
                                </td>
                            </tr>
                        </thead>
                        <tbody>

                        <?php

                        foreach ($getOrders as $order) {

                            echo "<tr><td>" . $order['order_status'] . "</td><td>" . $order['total_price'] . "</td><td>" . $order['created_at'] . "</td></tr>";
                        }
                    }

                        ?>
                        </tbody>
                    </table>

            </center>

        </body>

        </html>
    <?php
    }
} else {
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../style.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <title>Yetkiniz Yok</title>
    </head>

    <body>

        <?php include $_SERVER['DOCUMENT_ROOT'] . '/static-elements/nav.php'; ?>

        <center>
            <h2>Bu Sayfayı Görüntüleme Yetkiniz Bulunmamaktadır.</h2>
        </center>



    </body>

    </html>
<?php
}
exit;
?>