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
        <h1>Kullanıcı Ekle</h1>

        <div class="login-form">
            <form action="../src/users.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">İsim</label>
                    <input type="text" name="name" class="form-control" id="name" placeholder="İsim" required>
                </div>
                <div class="form-group">
                    <label for="surname">Soyisim</label>
                    <input type="text" name="surname" class="form-control" id="surname" placeholder="Soyisim" required>
                </div>
                <div class="form-group">
                    <label for="username">Kullanıcı Adı</label>
                    <input type="text" name="username" class="form-control" id="username" placeholder="Kullanıcı Adı" required>
                </div>
                <div class="form-group">
                    <label for="password">Şifre</label>
                    <input type="password" name="password" class="form-control" id="password" placeholder="Şifre" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Şifre Onay</label>
                    <input type="password" name="confirm_password" class="form-control" id="confirm_password" placeholder="Şifre Onay" required>
                </div>
                <div class="form-group">
                    <label for="role">Rol</label>
                    <select name="role" class="form-control" id="role" required>
                        <option value="">Rol Seçiniz</option>
                        <option value="customer">Müşteri</option>
                        <option value="admin">Admin</option>
                        <option value="company">Firma</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="company">Firma</label>
                    <select name="company_id" class="form-control" id="company" required>
                        <option value="">Firma Seçiniz</option>
                        <option value="-1">Yok ----Kullanıcı bir müşteri-----</option>
                        <?php
                        include_once '../src/companies.php';
                        $companies = Companies::getCompanies();
                        foreach ($companies as $company) {
                            echo '<option value="' . $company['id'] . '">' . $company['name'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <input type="hidden" name="action" value="add">
                <button type="submit" class="btn btn-primary">Kullanıcıyı Ekle</button>
            </form>
        </div>

        <hr>


        <h1>Kullanıcılar</h1>
    </center>

    <?php
    include_once '../src/users.php';
    $users = Users::getAllUsers();

    if ($users) {
        echo '<div class="cards-list">';
        foreach ($users as $user) {
            echo   '<div class="card" style="width: 18rem;">
                        <div class="card-body">
                        <h5 class="card-title">';
            echo $user['name'] . ' ' . $user['surname'];
            echo        '</h5><h6 class="card-subtitle mb-2 text-muted">Rol: ';
            echo $user['role'];
            echo '</h6>';

            if($user['role'] == 'customer'){
                echo '<a href="../customer-panel/orders.php?user_id=' . $user['id'] . '&action=active" class="card-link">Aktif Siparişler</a>';    
            }

            echo '<a href="../customer-panel/profile.php?user_id=' . $user['id'] . '" class="card-link">Profilini Gör</a>
            <form action="/src/users.php" method="POST">
                        <input type="hidden" name="delete_id" value="' . $user['id'] . '">
                        <input type="hidden" name="action" value="delete">
                        <button type="submit" name="delete_user" class="card-link btn btn-danger">Sil</button>
                    </form>
    </div>
</div> ';
        }
        echo '</div>';
    } else {
        echo "<h2>Kullanıcı Bulunamadı</h2>";
    }

    ?>

</body>

</html>