<?php
$servername = "127.0.0.1";
$username = "root";
$password = "12345678";
$dbname = "restaurant";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Veritabanına başarıyla bağlanıldı!";

} catch (PDOException $e) {
    echo "Bağlantı hatası: " . $e->getMessage();
}
