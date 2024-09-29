<?php
class Foods{
    public static function addFood($food) {
        global $conn;
        $query = "INSERT INTO foods (name, description, price, restaurant_id) VALUES (:name, :description, :price, :restaurant_id)";
        $stmt = $conn->prepare($query);

        return $stmt->execute([
            ':name' => $food['name'],
            ':description' => $food['description'],
            ':price' => $food['price'],
            ':restaurant_id' => $food['restaurant_id']
        ]);
    }

    public static function updateFood($food) {
        global $conn;
        $query = "UPDATE foods SET name = :name, description = :description, price = :price WHERE id = :id";
        $stmt = $conn->prepare($query);

        return $stmt->execute([
            ':name' => $food['name'],
            ':description' => $food['description'],
            ':price' => $food['price'],
            ':id' => $food['id']
        ]);
    }

    public static function getRestaurantFoods($restaurant_id) {
        global $conn;
        $query = "SELECT * FROM foods WHERE restaurant_id = :restaurant_id";
        $stmt = $conn->prepare($query);
        
        $stmt->execute([':restaurant_id' => $restaurant_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function deleteFood($food) {
        global $conn;
        $query = "DELETE FROM foods WHERE id = :id";
        $stmt = $conn->prepare($query);

        return $stmt->execute([':id' => $food['id']]);
    }
}
class Food{
    public $name;
    public $description;
    public $price;
}