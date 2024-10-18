<?php

include '../connection.php';

class Companies
{
    public static function addCompany($company)
    {
        if (self::checkRestaurantExist($company['name'])) {
            return -1;
        }

        global $conn;
        $query = "INSERT INTO companies (name, description, logo_path) VALUES (:name, :description, :logo_path)";
        $stmt = $conn->prepare($query);

        $stmt->bindParam('name', $company['name']);
        $stmt->bindParam('description', $company['description']);
        $stmt->bindParam('logo_path', $company['logo_path']);

        if ($stmt->execute()) {
            return 0;
        } else {
            return -2;
        }
    }

    public static function checkRestaurantExist($name)
    {
        global $conn;

        $sql = "SELECT COUNT(*) FROM companies WHERE name = :name";
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':name', $name);
        $stmt->execute();

        $count = $stmt->fetchColumn();

        return $count > 0;
    }

    public static function updateCompany($company)
    {
        global $conn;
        $query = "UPDATE companies SET name = :name, description = :description, logo_path = :logo_path WHERE id = :id";
        $stmt = $conn->prepare($query);

        return $stmt->execute([
            ':id' => $company['id'],
            ':name' => $company['name'],
            ':description' => $company['description'],
            ':logo_path' => $company['logo_path']
        ]);
    }

    public static function getCompany($id)
    {
        global $conn;
        $query = "SELECT * FROM companies WHERE id = :id AND deleted_at IS NULL";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getCompanies()
    {
        global $conn;
        $query = "SELECT * FROM companies WHERE deleted_at IS NULL";
        $stmt = $conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function deleteCompany($company_id)
    {
        global $conn;

        $currentDateTime = date('Y-m-d H:i:s');

        $query = "UPDATE companies SET deleted_at = :deleted_at WHERE id = :id";
        $stmt = $conn->prepare($query);

        $stmt->bindParam(':deleted_at', $currentDateTime);
        $stmt->bindParam(':id', $company_id);

        return $stmt->execute();
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($_POST['action'] === 'add') {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $logo_file = $_FILES['logo_path'];
    
        if (empty($name) || empty($description) || empty($logo_file['name'])) {
            echo "<script>alert('Bilgiler eksik'); window.location.href = '../admin-panel/companies.php';</script>";
            exit;
        }
    
        $fileName = basename($logo_file['name']);
        $fileTmpPath = $logo_file['tmp_name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');
    
        $uploadDirectory = '../uploads/logos/';
        $newFileName = uniqid() . '.' . $fileExtension;
        $uploadFilePath = $uploadDirectory . $newFileName;
    
        if (!in_array($fileExtension, $allowedExtensions)) {
            echo "<script>alert('Geçersiz dosya formatı. Yalnızca JPG, JPEG, PNG ve GIF kabul edilir.'); window.location.href = history.back();</script>";
            exit;
        }
    
        if (!move_uploaded_file($fileTmpPath, $uploadFilePath)) {
            echo "<script>alert('Dosya yüklenemedi. Lütfen tekrar deneyin.'); history.back();</script>";
            exit;
        }
    
        $company = [
            'name' => $name,
            'description' => $description,
            'logo_path' => $uploadFilePath
        ];
    
        $exit_code = Companies::addCompany($company);
    
        if ($exit_code == 0) {
            echo "<script>alert('Firma başarıyla eklendi.'); window.location.href = '/admin-panel/companies.php';</script>";
        } elseif ($exit_code == -1) {
            echo "<script>alert('Firma adı zaten mevcut.'); window.location.href = '/admin-panel/companies.php';</script>";
        } else {
            echo "<script>alert('Firma eklenemedi. Lütfen tekrar deneyin.'); history.back();</script>";
        }
    } elseif ($_POST['action'] === 'update') {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $logo_file = $_FILES['logo_path'];

        if (empty($id) || empty($name) || empty($description)) {
            echo "<script>alert('Bilgiler eksik'); history.back();</script>";
            exit;
        }

        $company = Companies::getCompany($id);

        if ($name == $company['name'] && $description == $company['description'] && empty($logo_file['name'])) {
            echo "<script>alert('Değişiklik yapmadınız'); history.back();</script>";
            exit;
        }

        $uploadDirectory = '../uploads/logos/';
        $logo_path = $company['logo_path']; 

        if (!empty($logo_file['name'])) {  
            $fileName = basename($logo_file['name']);
            $fileTmpPath = $logo_file['tmp_name'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');

            if (in_array($fileExtension, $allowedExtensions)) {
                $newFileName = uniqid() . '.' . $fileExtension; 
                $uploadFilePath = $uploadDirectory . $newFileName;

                if (move_uploaded_file($fileTmpPath, $uploadFilePath)) {
                    $logo_path = $uploadFilePath; 
                } else {
                    echo "<script>alert('Resim yüklenirken hata oluştu'); history.back();</script>";
                    exit;
                }
            } else {
                echo "<script>alert('Geçersiz dosya formatı'); history.back();</script>";
                exit;
            }
        }

        $updatedCompany = [
            'id' => $id,
            'name' => $name,
            'description' => $description,
            'logo_path' => $logo_path
        ];

        if (Companies::updateCompany($updatedCompany)) {
            echo "<script>alert('Şirket başarıyla güncellendi'); window.location.href = 'company-list.php';</script>";
        } else {
            echo "<script>alert('Güncelleme sırasında bir hata oluştu'); history.back();</script>";
        }
    }
} elseif (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $company_id = $_GET['company_id'];

    if (Companies::deleteCompany($company_id)) {
        echo "<script>alert('Firma Silindi'); window.location.href = '../admin-panel/companies.php';</script>";
        exit;
    } else {
        echo "<script>alert('Firma Silinemedi'); history.back();</script>";
        exit;
    }
}
