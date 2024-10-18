<?php

include (__DIR__) . '/../connection.php';

class Foods
{
    public static function addFood($food)
    {
        global $conn;
        $query = "INSERT INTO foods (name, description, price, discount, restaurant_id) VALUES (:name, :description, :price, :discount, :restaurant_id)";
        $stmt = $conn->prepare($query);

        return $stmt->execute([
            ':name' => $food['name'],
            ':description' => $food['description'],
            ':price' => $food['price'],
            ':discount' => $food['discount'],
            ':restaurant_id' => $food['restaurant_id']
        ]);
    }

    public static function updateFood($food)
    {
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

    public static function getFoodWithId($id)
    {
        global $conn;

        $query = "SELECT * FROM foods WHERE id = :id AND deleted_at IS NULL";
        $stmt = $conn->prepare($query);

        $stmt->bindParam(":id", $id);

        $stmt->execute();

        return $stmt->fetch();
    }

    public static function getRestaurantFoods($restaurant_id)
    {
        global $conn;
        $query = "SELECT * FROM foods WHERE restaurant_id = :restaurant_id";
        $stmt = $conn->prepare($query);

        $stmt->execute([':restaurant_id' => $restaurant_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function deleteFood($food)
    {
        global $conn;

        $query = "UPDATE foods SET deleted_at = NOW() WHERE id = :id";
        $stmt = $conn->prepare($query);

        $stmt->bindParam(':id', $food['id'], PDO::PARAM_INT);

        return $stmt->execute();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'add') {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $image_file = $_FILES['image_path']; 
        $restaurant_id = $_POST['restaurant_id'];
        $company_id = $_POST['company_id'];
        $price = $_POST['price'];
        $discount = $_POST['discount'];


        if (empty($name) || empty($description) || empty($image_file) || empty($restaurant_id) || empty($price)) {
            echo "<script>alert('Bilgiler eksik'); window.location.href = '../companies-panel/foods.php?id=". $company_id ."';</script>";
            exit;
        }

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

        $food = [
            'name' => $name,
            'description' => $description,
            'image_path' => $uploadFilePath,
            'restaurant_id' => $restaurant_id,
            'price' => $price,
            'discount' => $discount
        ];

        $exit_code = Foods::addFood($food);

        if ($exit_code == 0) {
            echo "<script>alert('Yemek eklendi'); window.location.href = '../admin-panel/foods.php';</script>";
            exit;
        } else if ($exit_code == -1) {
            echo "<script>alert('Yemek adı mevcut'); window.location.href = '../admin-panel/foods.php';</script>";
            exit;
        } else {
            echo "<script>alert('Yemek eklenemedi'); window.location.href = '../admin-panel/foods.php';</script>";
            exit;
        }
    } elseif ($_POST['action'] === 'update') {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $image_file = $_FILES['image_path'];
        $company_id = $_POST['company_id'];
        $price = $_POST['price'];
        $discount = $_POST['discount'];

        if (empty($name) || empty($description) || empty($company_id) || empty($price) || empty($discount)) {
            echo "<script>alert('Bilgiler eksik'); window.location.href = '../admin-panel/foods.php';</script>";
            exit;
        }

        $currentFood = Foods::getFoodWithId($id);
        $uploadDirectory = '../uploads/foods/';
        $image_path = $currentFood['image_path'];

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
                    echo "<script>alert('Resim yüklenirken hata oluştu'); window.location.href = '../admin-panel/foods.php';</script>";
                    exit;
                }
            } else {
                echo "<script>alert('Geçersiz dosya formatı'); window.location.href = '../admin-panel/foods.php';</script>";
                exit;
            }
        }

        $newFoodData = [
            'id' => $id,
            'name' => $name,
            'description' => $description,
            'image_path' => $image_path,
            'company_id' => $company_id,
            'price' => $price,
            'discount' => $discount
        ];

        if (Foods::updateFood($newFoodData)) {
            echo "<script>alert('Yemek başarıyla güncellendi'); window.location.href = '/companies-panel/food-details.php?id=" . $id . "';</script>";
        } else {
            echo "<script>alert('Yemek güncellenemedi'); history.back(); </script>";
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['action']) && $_GET['action'] === 'delete') {
        $id = $_GET['id'];

        if (empty($id)) {
            echo "<script>alert('Eksik bilgi'); history.back();</script>";
            exit;
        }

        $exit_code = Foods::deleteFood($id);

        if ($exit_code == 0) {
            echo "<script>alert('Yemek silindi'); window.history.back(); window.location.reload();</script>";
            exit;
        } elseif ($exit_code == -1) {
            echo "<script>alert('Yemek bulunamadı'); history.back();</script>";
            exit;
        } else {
            echo "<script>alert('Yemek silinemedi'); history.back();</script>";
            exit;
        }
    }
}
