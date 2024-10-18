<?php
include 'connection.php';

try {

    $password = password_hash('admin', PASSWORD_ARGON2ID);

    $sql = "INSERT INTO users (name, surname, password, username, role) 
    VALUES (:name, :surname, :password, :username, :role)";

    $stmt = $conn->prepare($sql);

    $stmt->execute([
        ':name' => 'admin',
        ':surname' => 'admin',
        ':password' => $password,
        ':username' => 'admin',
        ':role' => 'admin'
    ]);

    echo "<script>alert('kurulum tamamlandı')</script>";

    //unlink(__FILE__);

    header('Location: index.php');
    exit;

} catch (PDOException $e) {
    echo "Hata: " . $e->getMessage();
}