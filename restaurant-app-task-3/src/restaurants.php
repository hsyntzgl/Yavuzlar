<?php

include(__DIR__ . '/../connection.php');

class Restaurants
{
    public static function addRestaurant($restaurant)
    {
        global $conn;

        if (self::checkRestaurantExist($restaurant['name'])) {
            return -1;
        }

        $query = "INSERT INTO restaurants (name, description, image_path, company_id) VALUES (:name, :description, :image_path, :company_id)";
        $stmt = $conn->prepare($query);

        $stmt->bindParam(':name', $restaurant['name']);
        $stmt->bindParam(':description', $restaurant['description']);
        $stmt->bindParam(':image_path', $restaurant['image_path']);
        $stmt->bindParam(':company_id', $restaurant['company_id']);

        if ($stmt->execute()) {
            return 0;
        } else {
            return -2;
        }
    }
    public static function addComment($comment)
    {
        global $conn;

        $sql = "INSERT INTO comments (user_id, restaurant_id, title, description, score, created_at) VALUES(:user_id, :restaurant_id, :title, :description, :score, NOW())";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $comment['user_id']);
        $stmt->bindParam(':restaurant_id', $comment['restaurant_id']);
        $stmt->bindParam(':title', $comment['title']);
        $stmt->bindParam(':description', $comment['comment']);
        $stmt->bindParam(':score', $comment['star']);

        return $stmt->execute();
    }

    public static function checkRestaurantExist($name)
    {
        global $conn;

        $sql = "SELECT COUNT(*) FROM restaurants WHERE name = :name";
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':name', $name);
        $stmt->execute();

        $count = $stmt->fetchColumn();

        return $count > 0;
    }

    public static function checkIsAlreadyDeleted($id)
    {
        global $conn;
        $query = "SELECT * FROM restaurants WHERE id = :id AND deleted_at IS NULL";
        $stmt = $conn->prepare($query);

        $stmt->bindParam(":id", $id);

        $stmt->execute();

        $count = $stmt->fetchColumn();
        return $count > 0;
    }

    public static function updateRestaurant($restaurant)
    {
        global $conn;
        $query = "UPDATE restaurants SET name = :name, description = :description, image_path = :image_path, company_id = :company_id WHERE id = :id";
        $stmt = $conn->prepare($query);

        $stmt->bindParam(':name', $restaurant['name']);
        $stmt->bindParam(':description', $restaurant['description']);
        $stmt->bindParam(':image_path', $restaurant['image_path']);
        $stmt->bindParam(':company_id', $restaurant['company_id']);
        $stmt->bindParam(':id', $restaurant['id']);

        return $stmt->execute();
    }

    public static function updateComment($comment)
    {
        global $conn;

        $sql = "UPDATE comments SET title = :title, description = :description, score = :score, updated_at = NOW() 
        WHERE id = :id";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $comment['id']);
        $stmt->bindParam(':title', $comment['title']);
        $stmt->bindParam(':description', $comment['comment']);
        $stmt->bindParam(':score', $comment['star']);

        return $stmt->execute();
    }

    public static function getRestaurants()
    {
        global $conn;
        $query = "SELECT * FROM restaurants WHERE deleted_at IS NULL";
        $stmt = $conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getCompanyId($restaurant_id)
    {

        global $conn;
        $query = "SELECT * FROM restaurants WHERE id = :id AND deleted_at IS NULL";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $restaurant_id);
        $stmt->execute();

        $result = $stmt->fetch();

        if ($result != null) {
            return $result['company_id'];
        }
        return null;
    }

    public static function getRestaurantComments($restaurant_id)
    {

        global $conn;

        $query = "SELECT * FROM comments WHERE restaurant_id = :restaurant_id";

        $stmt = $conn->prepare($query);

        $stmt->bindParam(':restaurant_id', $restaurant_id);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function getRestaurantComment($id)
    {
        global $conn;

        $query = "SELECT * FROM comments WHERE id = :id";

        $stmt = $conn->prepare($query);

        $stmt->bindParam(':id', $id);

        $stmt->execute();

        return $stmt->fetch();
    }

    public static function deleteRestaurant($id)
    {
        if (!self::checkIsAlreadyDeleted($id)) {
            return -1;
        }

        global $conn;
        $query = "UPDATE restaurants SET deleted_at = NOW() WHERE id = :id AND deleted_at IS NULL";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":id", $id);

        if ($stmt->execute()) {
            return 0;
        }
        return -2;
    }
    public static function getRestaurantsWithCompanyId($id)
    {
        global $conn;
        $query = "SELECT * FROM restaurants WHERE company_id = :company_id AND deleted_at IS NULL";
        $stmt = $conn->prepare($query);

        $stmt->bindParam(":company_id", $id);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function getRestaurantWithId($id)
    {
        global $conn;
        $query = "SELECT * FROM restaurants WHERE id = :id AND deleted_at IS NULL";
        $stmt = $conn->prepare($query);

        $stmt->bindParam(":id", $id);

        $stmt->execute();

        return $stmt->fetch();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($_POST['action'] === 'add') {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $image_path = $_FILES['image_path'];
        $company_id = $_POST['company_id'];

        if (empty($name) || empty($description) || empty($image_path) || empty($company_id)) {
            echo "<script>alert('bilgiler eksik'); window.location.href = '../admin-panel/restaurants.php';</script>";
            exit;
        }
        $image_file = $_FILES['image_path'];

        $fileName = basename($image_file['name']);
        $fileTmpPath = $image_file['tmp_name'];
        $fileSize = $image_file['size'];
        $fileType = $image_file['type'];

        $uploadDirectory = '../uploads/images/';
        $uploadFilePath = $uploadDirectory . $fileName;

        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');

        if (in_array($fileExtension, $allowedExtensions)) {
            if (move_uploaded_file($fileTmpPath, $uploadFilePath)) {
                echo "Resim başarıyla yüklendi.";
            }
        }

        $restaurant = [
            'name' => $name,
            'description' => $description,
            'image_path' => $uploadFilePath,
            'company_id' => $company_id
        ];

        $exit_code = Restaurants::addRestaurant($restaurant);

        if ($exit_code == 0) {
            echo "<script>alert('Restoran Eklendi'); window.location.href = '../admin-panel/restaurants.php';</script>";
            exit;
        } else if ($exit_code == -1) {
            echo "<script>alert('Restoran adı mevcut'); window.location.href = '../admin-panel/restaurants.php';</script>";
            exit;
        } else {
            echo "<script>alert('Restoren Eklenemedi'); window.location.href = '../admin-panel/restaurants.php';</script>";
            exit;
        }
    } elseif ($_POST['action'] === 'update') {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $image_file = $_FILES['image_path'];
        $company_id = $_POST['company_id'];

        if (empty($name) || empty($description) || empty($company_id)) {
            echo "<script>alert('Bilgiler eksik'); window.location.href = '../admin-panel/restaurants.php';</script>";
            exit;
        }
        $currentRestaurant = Restaurants::getRestaurantWithId($id);

        $uploadDirectory = '../uploads/restaurants/';
        $image_path = $currentRestaurant['image_path'];

        if (!empty($image_file['name'])) {
            $fileName = basename($image_file['name']);
            $fileTmpPath = $image_file['tmp_name'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');

            if (in_array($fileExtension, $allowedExtensions)) {
                $newFileName = uniqid() . '.' . $fileExtension;
                $uploadFilePath = $uploadDirectory . $newFileName;

                if (move_uploaded_file($fileTmpPath, $uploadFilePath)) {
                    $image_path = $uploadFilePath;
                } else {
                    echo "<script>alert('Resim yüklenirken hata oluştu'); window.location.href = '../admin-panel/restaurants.php';</script>";
                    exit;
                }
            } else {
                echo "<script>alert('Geçersiz dosya formatı'); window.location.href = '../admin-panel/restaurants.php';</script>";
                exit;
            }
        }

        $newRestaurantData = [
            'id' => $id,
            'name' => $name,
            'description' => $description,
            'image_path' => $image_path,
            'company_id' => $company_id,
        ];

        if (Restaurants::updateRestaurant($newRestaurantData)) {
            echo "<script>alert('Restoran başarıyla güncellendi'); window.location.href = '/companies-panel/restaurant-details.php?id=" . $id . "';</script>";
        } else {
            echo "<script>alert('Güncellenemedi'); history.back(); </script>";
        }
    } elseif ($_POST['action'] == 'addComment') {
        $user_id = $_POST['user_id'];
        $restaurant_id = $_POST['restaurant_id'];
        $star = $_POST['star'];
        $comment = $_POST['comment'];
        $title = $_POST['title'];

        $commentDetails = [
            'user_id' => $user_id,
            'restaurant_id' => $restaurant_id,
            'star' => $star,
            'comment' => $comment,
            'title' => $title
        ];

        if (Restaurants::addComment($commentDetails)) {
            echo "<script>alert('Yorum Eklendi'); window.history.back(); window.location.reload();</script>";
            exit;
        } else {
            echo "<script>alert('Yorum Eklenemedi'); window.history.back(); window.location.reload();</script>";
            exit;
        }
    } elseif ($_POST['action'] == 'updateComment') {
        $id = $_POST['id'];
        $restaurant_id = $_POST['restaurant_id'];
        $star = $_POST['star'];
        $comment = $_POST['comment'];
        $title = $_POST['title'];

        $commentDetails = [
            'id' => $id,
            'star' => $star,
            'comment' => $comment,
            'title' => $title
        ];

        if (Restaurants::updateComment($commentDetails)) {
            echo "<script>alert('Yorum Güncellendi'); window.location.href = '/customer-panel/restaurants.php?id=" . $restaurant_id . "'</script>";
            exit;
        } else {
            echo "<script>alert('Yorum Güncellenemedi'); window.history.back(); window.location.reload();</script>";
            exit;
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['action']) && $_GET['action'] === 'delete') {
        $id = $_GET['id'];

        if (empty($id)) {
            echo "<script>alert('Eksik bilgi'); history.back();</script>";
            exit;
        }

        $exit_code = Restaurants::deleteRestaurant($id);

        if ($exit_code == 0) {
            echo "<script>alert('Restoran Silindi'); window.history.back(); window.location.reload();</script>";
            exit;
        } elseif ($exit_code == -1) {
            echo "<script>alert('Restoran Bulunamadı'); history.back();</script>";
            exit;
        } else {
            echo "<script>alert('Restoran Silinemedi'); history.back();</script>";
            exit;
        }
    }
}
