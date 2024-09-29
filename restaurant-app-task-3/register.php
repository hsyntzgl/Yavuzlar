<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol</title>
</head>

<body>
    <div class="nav">
        <img src="logos/navbar-logo.png" alt="logo">
        <ul>
            <li><a href="login.php">Giriş Yap</a></li>
            <li><a href="">Kayıt Ol</a></li>

        </ul>
    </div>

    <div class="h-100vh d-flex align-items-center justify-content-center">
        <form action="src/users.php" method="POST">
            <div class="form-group">
                <label for="exampleInputEmail1">İsim</label>
                <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Soyisim</label>
                <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Email address</label>
                <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" placeholder="Password">
            </div>
            <div class="form-group">
                <label for="rePassword">Password</label>
                <input type="password" class="form-control" id="rePassword" placeholder="Password">
            </div>
            <input type="hidden" name="action" value="register">
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</body>

</html>