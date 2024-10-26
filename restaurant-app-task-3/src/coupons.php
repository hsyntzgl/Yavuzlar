<?php

include (__DIR__) . '/../connection.php';

class Coupons
{
    public static function addCoupon($coupon)
    {
        global $conn;

        if (self::checkCouponExist($coupon['name'])) {
            return -1;
        }

        $sql = "INSERT INTO coupons (name, company_id, discount) VALUES (:name, :company_id, :discount)";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $coupon['name']);
        $stmt->bindParam(':company_id', $coupon['company_id']);
        $stmt->bindParam(':discount', $coupon['discount']);

        if ($stmt->execute()) {
            return 0;
        } else {
            return -2;
        }
    }

    public static function checkCouponExist($name)
    {
        global $conn;

        $sql = "SELECT COUNT(*) FROM coupons WHERE name = :name";
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':name', $name);
        $stmt->execute();

        $count = $stmt->fetchColumn();

        return $count > 0;
    }
    public static function checkCoupon($coupon)
    {

        global $conn;

        $sql = "SELECT * FROM coupons WHERE name = :name AND deleted_at IS NULL";

        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':name', $coupon['code']);

        $stmt->execute();

        $couponValue = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($couponValue != null) {
            if((int)$couponValue['discount'] == (int)$coupon['discount'])
                return 0;
            return -2;
        } else {
            return -1;
        }
    }

    public static function useCoupon($coupon)
    {
        global $conn;

        $sql = "SELECT * FROM coupons WHERE name = :name AND deleted_at IS NULL";

        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':name', $coupon['code']);

        $stmt->execute();

        $couponDatabase = $stmt->fetch();

        if ($couponDatabase != null) {
            include (__DIR__) . '/../src/restaurants.php';

            $company_id = Restaurants::getCompanyId($coupon['restaurant_id']);

            if ($company_id != null) {
                if ($company_id == $couponDatabase['company_id']) {
                    return "/basket.php?code=" . $couponDatabase['name'] . '&discount=' . $couponDatabase['discount'];
                } else {
                    return -1;
                }
            }
            return null;
        }
    }
    public static function getCoupons()
    {
        global $conn;

        $sql = "SELECT * FROM coupons WHERE deleted_at IS NULL";

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $coupons = $stmt->fetchAll();

        return $coupons;
    }
    public static function deleteCoupon($coupon_id)
    {
        global $conn;

        $sql = "SELECT * FROM coupons WHERE id = :id AND deleted_at IS NULL";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $coupon_id);
        $stmt->execute();

        $coupon = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($coupon) {
            $sql = "UPDATE coupons SET deleted_at = :deleted_at WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $currentTime = date('Y-m-d H:i:s');
            $stmt->bindParam(':deleted_at', $currentTime);
            $stmt->bindParam(':id', $coupon_id);

            if ($stmt->execute()) {
                return 0;
            } else {
                return -2;
            }
        } else {
            return -1;
        }
    }
}
class Coupon
{
    public $name;
    public $description;
    public $discount;
    public $end_date;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($_POST['action'] === 'add') {
        $couponCode = $_POST['name'];
        $discount = $_POST['discount'];
        $company_id = $_POST['company_id'];

        if (empty($couponCode) || empty($discount) || empty($company_id)) {
            echo $couponCode . $discount . $company_id;
            //echo "<script>alert('Kupon bilgileri eksik');window.location.href = '../companies-panel/coupons.php';</script>";
            exit;
        }

        $coupon = [
            'name' => $couponCode,
            'discount' => $discount,
            'company_id' => $company_id
        ];

        $exit_code = Coupons::addCoupon($coupon);

        if ($exit_code == 0) {
            echo "<script>alert('Kupon başarıyla eklendi');
                window.location.href = '../companies-panel/coupons.php';
            </script>";
            exit;
        } else if ($exit_code == -1) {
            echo "<script>alert('Kupon mevcut');
                window.location.href = '../companies-panel/coupons.php';
            </script>";
            exit;
        } else {
            echo "<script>alert('Kupon eklenemedi');
                window.location.href = '../companies-panel/coupons.php';
            </script>";
            exit;
        }
    } elseif ($_POST['action'] === 'checkCoupon') {
        $code = $_POST['code'];
        $restaurant_id = $_POST['restaurant_id'];

        $coupon = [
            'restaurant_id' => $restaurant_id,
            'code' => $code
        ];

        $result = Coupons::useCoupon($coupon);

        if ($result == -1) {
            echo "<script>alert('Kupon Bu Firmaya ait Değil');
                window.location.href = '/basket.php';
            </script>";
            exit;
        }

        if ($result == null) {
            echo "<script>alert('Kupon Mevcut Değil');
                window.location.href = '/basket.php';
            </script>";
            exit;
        } else {
            echo "<script>alert('Kupon Kullanıldı');
                window.location.href = '" . $result . "';
            </script>";
            exit;
        }
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['action']) && $_GET['action'] === 'delete') {
        $coupon_id = $_GET['id'];

        $exit_code = Coupons::deleteCoupon($coupon_id);

        if ($exit_code == 0) {
            echo "<script>alert('Kupon Silindi');
                window.location.href = '/companies-panel/coupons.php';
            </script>";
            exit;
        } else if ($exit_code == -1) {
            echo "<script>alert('Kupon Mevcut Değil');
                window.location.href = '/companies-panel/coupons.php';
            </script>";
            exit;
        } else {
            echo "<script>alert('Kupon Silinemedi');
                window.location.href = '/companies-panel/coupons.php';
            </script>";
            exit;
        }
    }
}
