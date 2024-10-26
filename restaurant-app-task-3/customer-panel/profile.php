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

    $user = Users::getUserById($user_id);
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../style.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <title>Profil</title>
    </head>

    <body>

        <?php include $_SERVER['DOCUMENT_ROOT'] . '/static-elements/nav.php'; ?>

        <div class="container">
            <div class="main-body">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex flex-column align-items-center text-center">
                                    <img src="https://bootdey.com/img/Content/avatar/avatar6.png" alt="Admin" class="rounded-circle p-1 bg-primary" width="110">
                                    <div class="mt-3">
                                        <h4><?php
                                            echo  $user["name"] . " " . $user["surname"];
                                            ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">İsim</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" class="form-control" value=<?php echo  $user["name"]; ?> disabled>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Soyisim</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" class="form-control" value=<?php echo  $user["surname"]; ?> disabled>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Kullanıcı Adı</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" class="form-control" value=<?php echo  $user["username"]; ?> disabled>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Cüzdan</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" class="form-control" value="<?php echo $user['balance']; ?>" disabled>
                                        
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-9 text-secondary">
                                        <a href="updateProfile.php?user_id=<?php echo $user['id']; ?>" class="btn btn-primary px-4">Bilgileri Güncelle</a>
                                        <a href="changePassword.php?user_id=<?php echo $user['id']; ?>" class="btn btn-primary px-4">Şifre Değiştir</a>
                                        <a href="addMoney.php?user_id=<?php echo $user['id']; ?>" class="btn btn-primary px-4">Para Ekle</a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-9 text-secondary">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>

    </html>


<?php
}