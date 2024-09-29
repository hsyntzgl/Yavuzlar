<?php

class Restaurants{
    public static function addRestaurant($restaurant) {
        global $conn;
        $query = "INSERT INTO restaurants (name, address, rating) VALUES (:name, :address, :rating)";
        $stmt = $conn->prepare($query);

        return $stmt->execute([
            ':name' => $restaurant['name'],
            ':address' => $restaurant['address'],
            ':rating' => $restaurant['rating']
        ]);
    }

    public static function updateRestaurant($restaurant) {
        global $conn;
        $query = "UPDATE restaurants SET name = :name, address = :address, rating = :rating WHERE id = :id";
        $stmt = $conn->prepare($query);
        
        return $stmt->execute([
            ':name' => $restaurant['name'],
            ':address' => $restaurant['address'],
            ':rating' => $restaurant['rating'],
            ':id' => $restaurant['id']
        ]);
    }

    public static function getRestaurants() {
        global $conn;
        $query = "SELECT * FROM restaurants";
        $stmt = $conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function deleteRestaurant($restaurant) {
        global $conn;
        $query = "DELETE FROM restaurants WHERE id = :id";
        $stmt = $conn->prepare($query);

        return $stmt->execute([':id' => $restaurant['id']]);
    }
    
}
class Restaurant{
    public $name;
    public $description;

}