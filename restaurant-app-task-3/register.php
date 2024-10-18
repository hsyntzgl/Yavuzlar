<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Kayıt Ol</title>
</head>

<body>
<?php require_once("static-elements/nav.php"); ?>
    <div class="h-100vh d-flex align-items-center justify-content-center">
        <form action="src/users.php" method="POST">
            <div class="form-group">
                <label for="exampleInputEmail1">İsim</label>
                <input type="text" class="form-control" id="name" placeholder="Name" required="required">
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Soyisim</label>
                <input type="text" class="form-control" id="surname" placeholder="Surname" required="required">
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Kullanıcı Adı</label>
                <input type="text" class="form-control" id="username" placeholder="Kullanıcı Adı" required="required">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" placeholder="Password" required="required">
            </div>
            <div class="form-group">
                <label for="rePassword">Password</label>
                <input type="password" class="form-control" id="rePassword" placeholder="rePassword" required="required">
            </div>
            <input type="hidden" name="action" value="register">
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</body>

</html>