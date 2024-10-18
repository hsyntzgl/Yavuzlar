<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Document</title>
</head>

<body>
<?php require_once("static-elements/nav.php"); ?>

    <center>

    <div class="login-form">
        <form action="src/users.php" method="POST">
            <div class="form-group">
                <label for="usernameInput">Kullanıcı Adı</label>
                <input type="text" name="username" class="form-control" id="usernameInput" aria-describedby="username" placeholder="Kullanıcı adı" required>
            </div>
            <div class="form-group">
                <label for="passwordInput">Şifre</label>
                <input type="password" name="password" class="form-control" id="passwordInput" placeholder="Password" required>
            </div>
            <input type="hidden" name="action" value="login">
            <button type="submit" class="btn btn-primary">Submit</button >
        </form>
    </div>

    </center>

    </div>
</body>

</html>