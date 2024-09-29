<?php

include '../connection.php';

function getAllUsers() {
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
    
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    if ($_POST['action'] === 'login') {
        echo "<script>alert('Giriş başarılı'); history.back();</script>";
    } elseif ($_POST['action'] === 'register') {
        $username = $_POST['username'];
        echo "<script>alert('Kayıt başarılı'); history.back();</script>";
    }
    exit;
}
