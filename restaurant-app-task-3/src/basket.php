<?php

include (__DIR__) . '/../connection.php';


class Basket
{
    public static function add($food)
    {

        global $conn;

        $sql = "INSERT INTO basket(id, user_id, food_id, quantity, note, created_at) VALUES(:id, :user_id, :food_id, :quantity, :note, NOW())";

        $stmt = $conn->prepare($sql);

        $id = self::getLastId() + 1;

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':user_id', $food['user_id']);
        $stmt->bindParam(':food_id', $food['food_id']);
        $stmt->bindParam(':quantity', $food['quantity']);
        $stmt->bindParam(':note', $food['note']);

        if ($stmt->execute()) {
            return 0;
        }
        return -1;
    }

    public static function getUserBasket($user_id)
    {
        global $conn;

        $sql = "SELECT * FROM basket WHERE user_id = :user_id";

        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':user_id', $user_id);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getLastId()
    {
        global $conn;

        $sql = "SELECT * FROM basket ORDER BY id DESC LIMIT 1";
        $stmt = $conn->query($sql);

        $row = $stmt->fetch();

        if ($row) {
            return $row['id'];
        }
        return 0;
    }
    public static function deleteItem($id)
    {
        global $conn;

        $sql = "DELETE FROM basket WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }
}



if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'add') {
            $food_id = $_POST['food_id'];
            $user_id = $_POST['user_id'];
            $quantity = $_POST['quantity'];
            $note = $_POST['note'];


            $food = [
                'food_id' => $food_id,
                'user_id' => $user_id,
                'quantity' => $quantity,
                'note' => $note
            ];

            $exit_code = Basket::add($food);

            if ($exit_code == 0) {
                echo "<script>alert('Ürün eklendi'); window.location.href = '/customer-panel/restaurants.php?id=" . $_POST['restaurant_id'] . "';</script>";
            } else if ($exit_code == -1) {
                echo "<script>alert('Ürün Eklenemedi'); window.location.href = '/customer-panel/restaurants.php?id=" . $_POST['restaurant_id'] . "';</script>";
            }
        }
    }
} else if ($_SERVER["REQUEST_METHOD"] === 'GET') {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        if (Basket::deleteItem($id)) {
            echo "<script>alert('Ürün Silindi'); window.location.href = '/basket.php';</script>";
            exit;
        } else {
            echo "<script>alert('Ürün Silinemedi'); window.location.href = '/basket.php';</script>";
            exit;
        }
    }
}
