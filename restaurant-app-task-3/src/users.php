<?php

include $_SERVER['DOCUMENT_ROOT'] . '/connection.php';


class Users
{
    public static function addUser($user)
    {
        global $conn;

        if (self::checkUsernameExists($user['username'])) {
            return -1;
        }

        $sql = "INSERT INTO users (name, surname, username, password, role, company_id) VALUES (:name, :surname, :username, :password, :role, :company_id)";
        $stmt = $conn->prepare($sql);

        $hashed_password = password_hash($user['password'], PASSWORD_ARGON2ID);

        $stmt->bindParam(':name', $user['name']);
        $stmt->bindParam(':surname', $user['surname']);
        $stmt->bindParam(':username', $user['username']);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':role', $user['role']);
        $stmt->bindParam(':company_id', $user['company_id']);

        if ($stmt->execute()) {
            return 0;
        } else {
            return -2;
        }
    }
    public static function registerUser($user)
    {
        global $conn;

        if (self::checkUsernameExists($user['username'])) {
            return false;
        }

        $sql = "INSERT INTO users (name, surname, username, password, role) VALUES (:name, :surname, :username, :password, :role)";
        $stmt = $conn->prepare($sql);

        $hashed_password = password_hash($user['password'], PASSWORD_ARGON2ID);

        $stmt->bindParam(':name', $user['name']);
        $stmt->bindParam(':surname', $user['surname']);
        $stmt->bindParam(':username', $user['username']);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':role', $user['role']);

        return $stmt->execute();
    }
    public static function updateUser($user)
    {
        global $conn;

        if (!self::checkUsernameExists($user['username'])) {
            return -1;
        }

        $sql = "UPDATE users SET name = :name, surname = :surname WHERE username = :username";
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':name', $user['name']);
        $stmt->bindParam(':surname', $user['surname']);
        $stmt->bindParam(':username', $user['username']);

        if ($stmt->execute()) {
            return 0;
        }
        return -2;
    }

    public static function updateWallet($user_id, $value)
    {
        global $conn;

        $sql = "UPDATE users SET balance = balance + :value WHERE id = :user_id";
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':value', $value);
        $stmt->bindParam(':user_id', $user_id);

        return $stmt->execute();
    }
    public static function updatePassword($user_id, $newPassword)
    {
        global $conn;

        $hashedPassword = password_hash($newPassword, PASSWORD_ARGON2ID);

        $sql = "UPDATE users SET password = :password WHERE id = :user_id";
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':user_id', $user_id);

        return $stmt->execute();
    }
    public static function checkCurrentPassword($user_id, $currentPassword)
    {
        global $conn;

        $sql = "SELECT password FROM users WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $user_id);
        $stmt->execute();

        $hashedPassword = $stmt->fetchColumn();

        if ($hashedPassword && password_verify($currentPassword, $hashedPassword)) {
            return true;
        }

        return false;
    }

    public static function checkUsernameExists($username)
    {
        global $conn;

        $sql = "SELECT COUNT(*) FROM users WHERE username = :username AND deleted_at IS NULL";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $count = $stmt->fetchColumn();
        return $count > 0;
    }

    public static function checkUserExist($id)
    {
        global $conn;

        $sql = "SELECT COUNT(*) FROM users WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $count = $stmt->fetchColumn();
        return $count > 0;
    }

    public static function checkUserLogin($userLogin)
    {
        global $conn;

        $sql = "SELECT * FROM users WHERE username = :username";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $userLogin['username']);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($userLogin['password'], $user['password'])) {
            return $user;
        } else {
            return null;
        }
    }

    public static function getUserById($id)
    {
        global $conn;

        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id);

        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            return $user;
        } else {
            echo "Kullanıcı bulunamadı!";
            return null;
        }
    }

    public static function getAllUsers()
    {
        global $conn;

        $sql = "SELECT * FROM users";
        $stmt = $conn->prepare($sql);

        $stmt->execute();

        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $users;
    }

    public static function deleteUser($id)
    {
        global $conn;

        if (!self::checkUserExist($id)) {
            return -1;
        }

        $sql = "UPDATE users SET deleted_at = :deleted_at WHERE id = :id";
        $stmt = $conn->prepare($sql);

        $deleted_at = date('Y-m-d H:i:s');

        $stmt->bindParam(':deleted_at', $deleted_at);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            echo "Kullanıcı başarıyla silindi!";
            return 0;
        } else {
            echo "Kullanıcı silinirken bir hata oluştu!";
            return -2;
        }
    }
}

function getAllUsers()
{
    global $conn;

    $sql = "SELECT * FROM users";

    $stmt = $conn->query($sql);

    $users = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $users[] = $row;
    }

    return $users;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($_POST['action'] === 'login') {
        $username = isset($_POST['username']) ? $_POST['username'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';

        if (empty($username) || empty($password)) {
            echo "<script>alert('Kullanıcı adı veya şifre girmediniz')";
            header('Location: ../login.php');
            exit;
        }

        $userLogin = [
            'username' => $username,
            'password' => $password
        ];

        $user = Users::checkUserLogin($userLogin);

        if ($user != null) {
            session_start();
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if($user['company_id'] != null){
                $_SESSION['company_id'] = $user['company_id'];
            }

            echo "<script>alert('Giriş başarılı'); window.location.href = '../index.php';</script>";
        } else {
            echo "<script>alert('Giriş başarısız, lütfen bilgilerinizi kontrol edin.'); window.history.back();</script>";
        }
    } elseif ($_POST['action'] === 'register') {

        $name = isset($_POST['name']) ? $_POST['name'] : '';
        $surname = isset($_POST['surname']) ? $_POST['surname'] : '';
        $username = isset($_POST['username']) ? $_POST['username'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $rePassword = isset($_POST['rePassword']) ? $_POST['rePassword'] : '';

        if (empty($name) || empty($surname) || empty($username) || empty($password) || empty($rePassword)) {
            echo "<script>alert('Lütfen tüm alanları doldurunuz.')</script>";
        } else {
            if ($password != $rePassword) {
                echo "<script>alert('Şifreler Eşleşmiyor.')</script>";
                header('Location: ../register.php');
                exit;
            }

            $user = [
                'name' => $name,
                'surname' => $surname,
                'username' => $username,
                'password' => $password,
            ];

            echo "İsim: $name<br>";
            echo "Soyisim: $surname<br>";
            echo "Kullanıcı Adı: $username<br>";
            echo "Şifre: $password<br>";
            echo "Şifre (Tekrar): $rePassword<br>";
        }
        exit;
    } elseif ($_POST['action'] === 'update') {
        $name = isset($_POST['name']) ? $_POST['name'] : '';
        $surname = isset($_POST['surname']) ? $_POST['surname'] : '';
        $username = isset($_POST['username']) ? $_POST['username'] : '';

        if (empty($name) || empty($surname) || empty($username)) {
            echo "<script>alert('Lütfen tüm alanları doldurunuz.'); history.back();</script>";
        }

        $user = [
            'name' => $name,
            'surname' => $surname,
            'username' => $username
        ];

        $exit_code = Users::updateUser($user);

        if ($exit_code == 0) {
            echo "<script>alert('Profiliniz Güncellendi'); window.location.href = '../customer-panel/profile.php'</script>";
            exit;
        } elseif ($exit_code == -1) {
            echo "<script>alert('Kullanıcı Adı Mevcut.'); history.back();</script>";
            exit;
        } else {
            echo "<script>alert('Profil Güncellenemedi.'); history.back();</script>";
            exit;
        }
    } elseif ($_POST['action'] === 'add') {
        $name = isset($_POST['name']) ? $_POST['name'] : '';
        $surname = isset($_POST['surname']) ? $_POST['surname'] : '';
        $username = isset($_POST['username']) ? $_POST['username'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
        $role = isset($_POST['role']) ? $_POST['role'] : '';
        $company_id = isset($_POST['company_id']) ? $_POST['company_id'] : null;

        if (empty($name) || empty($surname) || empty($username) || empty($password) || empty($confirm_password) || empty($role) || empty($company_id)) {
            echo "<script>alert('Lütfen tüm alanları doldurunuz.'); history.back();</script>";
            exit;
        } else {

            if ($company_id == -1) {
                $company_id = null;
            }

            $user = [
                'name' => $name,
                'surname' => $surname,
                'username' => $username,
                'password' => $password,
                'role' => $role,
                'company_id' => $company_id
            ];

            $exit_code = Users::addUser($user);

            if ($exit_code == 0) {
                echo "<script>alert('Kullanıcı Eklendi'); window.location.href = '../customer-panel/profile.php'</script>";
                exit;
            } elseif ($exit_code == -1) {
                echo "<script>alert('Kullanıcı Adı Mevcut.'); history.back();</script>";
                exit;
            } else {
                echo "<script>alert('Kullanıcı Eklenemedi.'); history.back();</script>";
                exit;
            }
        }
    } elseif ($_POST['action'] === 'delete') {
        $user_id = isset($_POST['delete_id']) ? $_POST['delete_id'] : null;

        if (empty($user_id)) {
            echo "<script>alert('Lütfen bir id belirtin.'); history.back();</script>";
        }

        session_start();

        if ($_SESSION['id'] == $user_id) {
            echo "<script>alert('Kendinizi Silemezsiniz'); history.back();</script>";
            exit;
        }

        if ($_SESSION['role'] != 'admin') {
            echo "<script>alert('Yetkiniz yok'); history.back();</script>";
            exit;
        }

        $exit_code = Users::deleteUser((int)$user_id);

        if ($exit_code == 0) {
            echo "<script>alert('Kullanıcı Silindi'); window.location.href = '../customer-panel/customers.php'</script>";
            exit;
        } elseif ($exit_code == -1) {
            echo "<script>alert('Kullanıcı Bulunamadı.'); history.back();</script>";
            exit;
        } else {
            echo "<script>alert('Kullanıcı Silinemedi.'); history.back();</script>";
            exit;
        }
    } elseif ($_POST['action'] === 'update_password') {
        $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';
        $currentPassword = isset($_POST['current_password']) ? $_POST['current_password'] : '';
        $newPassword = isset($_POST['new_password']) ? $_POST['new_password'] : '';
        $confirmPassword = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

        if (empty($user_id) || empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            echo "<script>alert('Lütfen bir id belirtin.'); history.back();</script>";
            exit;
        }

        if ($newPassword != $confirmPassword) {
            echo "<script>alert('Şifreler uyuşmuyor.'); history.back();</script>";
            exit;
        }

        if (Users::checkCurrentPassword($user_id, $currentPassword)) {
            if (Users::updatePassword($user_id, $newPassword)) {
                echo "<script>alert('Şifre Değiştirildi.'); window.location.href = '/customer-panel/profile.php';</script>";
                exit;
            } else {
                echo "<script>alert('Şifre Değiştirelemedi.'); history.back();</script>";
                exit;
            }
        } else {
            echo "<script>alert('Mevcut Şifrenizi yanlış giridniz.'); history.back();</script>";
            exit;
        }
    } elseif ($_POST['action'] === 'add_money') {
        $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';
        $money = isset($_POST['money']) ? $_POST['money'] : '';

        if(empty($user_id) || empty($money)){
            echo "<script>alert('Eksik bilgi.'); history.back();</script>";
            exit;
        }

        if(Users::updateWallet($user_id, $money)){
            echo "<script>alert('Tutar eklendi.'); window.location.href = '/customer-panel/profile.php'</script>";
                exit;
        }else{
            echo "<script>alert('Tutar eklenemedi.'); history.back();</script>";
                exit;
        }

    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
}
