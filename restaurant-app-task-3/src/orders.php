<?php

include (__DIR__) . '/../connection.php';

class Orders
{
    public static function addOrder() {}

    public static function getAllOrders() {}
    public static function getAllOrdersWithRestaurantId($id) {}
    public static function getAllOrdersWithUserId($id)
    {
        global $conn;

        $query = "SELECT * FROM orders WHERE user_id = :id";
        $stmt = $conn->prepare($query);

        $stmt->bindParam(":id", $id);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}


if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    if ($_POST['action'] === 'add') {
        echo $_POST['data'];
        echo $_POST['order_items'];
    }
}
