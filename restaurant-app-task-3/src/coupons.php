<?php

include '../connection.php';

class Coupons
{
    public static function addCoupon($coupon)
    {
        global $conn;

        if (self::checkCouponExist($coupon['couponCode'])) {
            return -1;
        }

        $sql = "INSERT INTO coupons (name, company_id, discount) VALUES (:name, :company_id, :discount)";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $coupon['couponCode']);
        $stmt->bindParam(':company_id', $coupon['restaurant_id']);
        $stmt->bindParam(':discount', $coupon['discount']);

        if ($stmt->execute()) {
            return 0;
        } else {
            return -2;
        }
    }

    public static function checkCouponExist($couponCode)
    {
        global $conn;

        $sql = "SELECT COUNT(*) FROM coupons WHERE name = :name";
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':name', $couponCode);
        $stmt->execute();

        $count = $stmt->fetchColumn();

        return $count > 0;
    }

    public static function useCoupon($coupon) {}
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

    if ($_POST['action'] === 'addCoupon') {
        $couponCode = $_POST['couponCode'];
        $discount = $_POST['discount'];
        $restaurant_id = $_POST['restaurant_id'];

        if (empty($couponCode) || empty($discount) || empty($restaurant_id)) {
            echo "<script>alert('Kupon bilgileri eksik');
                window.location.href = '../admin-panel/coupons.php';
            </script>";
            exit;
        }

        $coupon = [
            'couponCode' => $couponCode,
            'discount' => $discount,
            'restaurant_id' => $restaurant_id
        ];

        $exit_code = Coupons::addCoupon($coupon);

        if ($exit_code == 0) {
            echo "<script>alert('Kupon başarıyla eklendi');
                window.location.href = '../admin-panel/coupons.php';
            </script>";
            exit;
        } else if ($exit_code == -1) {
            echo "<script>alert('Kupon mevcut');
                window.location.href = '../admin-panel/coupons.php';
            </script>";
            exit;
        } else {
            echo "<script>alert('Kupon eklenemedi');
                window.location.href = '../admin-panel/coupons.php';
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
                window.location.href = '/admin-panel/coupons.php';
            </script>";
            exit;
        } else if ($exit_code == -1) {
            echo "<script>alert('Kupon Mevcut Değil');
                window.location.href = '/admin-panel/coupons.php';
            </script>";
            exit;
        } else {
            echo "<script>alert('Kupon Silinemedi');
                window.location.href = '/admin-panel/coupons.php';
            </script>";
            exit;
        }
    }
}
