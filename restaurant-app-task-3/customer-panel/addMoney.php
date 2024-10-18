<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Para Ekle</title>
</head>

<body>
    <?php
    session_start();

    include '../src/profile.php';
    include '../src/users.php';

    $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : $_SESSION['id'];

    if ($_SESSION['id'] != $user_id && $_SESSION['role'] != 'admin') {
        echo "<script>alert('Yekiniz Yok'); history.back();</script>";
        exit;
    }
    include $_SERVER['DOCUMENT_ROOT'] . '/static-elements/nav.php'; ?>

    <center>

        <div class="login-form">
            <form action="../src/users.php" method="POST">
                <div class="form-group">
                    <label for="moneyInput">Para Ekle</label>
                    <input type="number" name="money" class="form-control" id="moneyInput" placeholder="Miktar" required>
                </div>
                <input type="hidden" name="action" value="add_money">
                <input type="hidden" name="user_id" value=<?php echo $user_id ?>>
                <button type="submit" class="btn btn-primary">Ekle</button>
            </form>
        </div>


    </center>
</body>

</html>