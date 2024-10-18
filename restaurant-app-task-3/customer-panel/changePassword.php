<?php


session_start();

if (!isset($_SESSION["id"])) {
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Hata</title>
    </head>

    <body>
        <center>
            <h1>Lütfen Giriş Yapın... Yönlendiriliyorsunuz...</h1>
            <h1 id="countdown"></h1>
            <script>
                let countdown = 3;

                const countdownInterval = setInterval(() => {

                    document.getElementById("countdown").innerText = countdown;
                    countdown--;

                    if (countdown < 0) {
                        clearInterval(countdownInterval);
                        window.location.href = "login.php";
                    }
                }, 1000);
            </script>
        </center>
    </body>

    </html>

<?php
} else {
    include '../src/profile.php';
    include '../src/users.php';

    $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : $_SESSION['id'];

    if ($_SESSION['id'] != $user_id && $_SESSION['role'] != 'admin') {
        echo "<script>alert('Yekiniz Yok'); history.back();</script>";
        exit;
    }
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../style.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <title>Şifre Değiştir</title>
    </head>

    <body>

        <?php include $_SERVER['DOCUMENT_ROOT'] . '/static-elements/nav.php'; ?>

        <center>

            <div class="login-form">
                <form action="../src/users.php" method="POST">
                    <div class="form-group">
                        <label for="currentPasswordInput">Mevcut Şifre</label>
                        <input type="password" name="current_password" class="form-control" id="currentPasswordInput" placeholder="Mevcut şifre" required>
                    </div>
                    <div class="form-group">
                        <label for="newPasswordInput">Yeni Şifre</label>
                        <input type="password" name="new_password" class="form-control" id="newPasswordInput" placeholder="Yeni şifre" required>
                    </div>
                    <div class="form-group">
                        <label for="confirmPasswordInput">Şifreyi Onayla</label>
                        <input type="password" name="confirm_password" class="form-control" id="confirmPasswordInput" placeholder="Şifreyi onayla" required>
                    </div>
                    <input type="hidden" name="action" value="update_password">
                    <input type="hidden" name="user_id" value=<?php echo $user_id ?>>
                    <button type="submit" class="btn btn-primary">Güncelle</button>
                </form>
            </div>


        </center>
    </body>

    </html>


<?php
}
