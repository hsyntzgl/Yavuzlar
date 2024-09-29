<?php
include 'connection.php';

try {
    $sql = "INSERT INTO users (name, surname, password, username, role) 
    VALUES ('admin', 'admin', 'admin', 'admin', 'admin')";

    $conn->exec($sql);

    echo "<script>alert('kurulum tamamlandı')</script>";

    unlink(__FILE__);

    header('Location: index.php');
    exit;

} catch (PDOException $e) {
    echo "Hata: " . $e->getMessage();
}