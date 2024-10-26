<?php
include 'connection.php';

$tables = [
    "CREATE TABLE IF NOT EXISTS `companies` (
        `id` int NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL,
        `logo_path` text,
        `deleted_at` datetime DEFAULT NULL,
        `description` text,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci",

    "CREATE TABLE IF NOT EXISTS `users` (
        `id` int NOT NULL AUTO_INCREMENT,
        `company_id` int DEFAULT NULL,
        `name` varchar(255) NOT NULL,
        `surname` varchar(255) NOT NULL,
        `username` varchar(255) NOT NULL,
        `password` varchar(255) NOT NULL,
        `role` varchar(50) NOT NULL,
        `balance` float DEFAULT '5000',
        `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
        `deleted_at` datetime DEFAULT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `email` (`username`),
        UNIQUE KEY `username` (`username`),
        KEY `fk_users_companies` (`company_id`),
        CONSTRAINT `fk_users_companies` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE SET NULL
    ) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci",

    "CREATE TABLE IF NOT EXISTS `restaurants` (
        `id` int NOT NULL AUTO_INCREMENT,
        `company_id` int NOT NULL,
        `name` text NOT NULL,
        `description` text,
        `image_path` text,
        `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
        `deleted_at` datetime DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `company_id` (`company_id`),
        CONSTRAINT `restaurants_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci",

    "CREATE TABLE IF NOT EXISTS `coupons` (
        `id` int NOT NULL AUTO_INCREMENT,
        `company_id` int NOT NULL,
        `discount` int NOT NULL,
        `deleted_at` datetime DEFAULT NULL,
        `name` varchar(255) DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `company_id` (`company_id`),
        CONSTRAINT `coupons_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci",

    "CREATE TABLE IF NOT EXISTS `foods` (
        `id` int NOT NULL,
        `restaurant_id` int DEFAULT NULL,
        `name` text,
        `description` text,
        `price` float DEFAULT NULL,
        `image_path` text,
        `created_at` datetime DEFAULT NULL,
        `deleted_at` datetime DEFAULT NULL,
        `discount` float DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `restaurant_id` (`restaurant_id`),
        CONSTRAINT `foods_ibfk_1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci",

    "CREATE TABLE IF NOT EXISTS `basket` (
        `id` int NOT NULL,
        `user_id` int DEFAULT NULL,
        `food_id` int DEFAULT NULL,
        `note` text,
        `quantity` int DEFAULT NULL,
        `created_at` datetime DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `user_id` (`user_id`),
        KEY `food_id` (`food_id`),
        CONSTRAINT `basket_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci",

    "CREATE TABLE IF NOT EXISTS `comments` (
        `id` int NOT NULL AUTO_INCREMENT,
        `user_id` int DEFAULT NULL,
        `restaurant_id` int DEFAULT NULL,
        `title` text,
        `description` text,
        `score` int DEFAULT NULL,
        `created_at` datetime DEFAULT NULL,
        `updated_at` datetime DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `user_id` (`user_id`),
        KEY `restaurant_id` (`restaurant_id`),
        CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
        CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci",

    "CREATE TABLE IF NOT EXISTS `orders` (
        `id` int NOT NULL AUTO_INCREMENT,
        `user_id` int NOT NULL,
        `order_status` int NOT NULL,
        `total_price` float NOT NULL,
        `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        KEY `user_id` (`user_id`),
        CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci",

    "CREATE TABLE IF NOT EXISTS `order_items` (
        `id` int NOT NULL,
        `food_id` int DEFAULT NULL,
        `order_id` int DEFAULT NULL,
        `quantity` int DEFAULT NULL,
        `price` float DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `food_id` (`food_id`),
        KEY `order_id` (`order_id`),
        CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`food_id`) REFERENCES `foods` (`id`) ON DELETE CASCADE,
        CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci"
];

foreach ($tables as $table) {
    $conn->exec($table);
}


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

    echo "<script>alert('kurulum tamamlandı'); window.location.href = '/index.php';</script>";

    unlink(__FILE__);
    exit;

} catch (PDOException $e) {
    echo "Hata: " . $e->getMessage();
}