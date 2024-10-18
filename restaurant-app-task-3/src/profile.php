<?php
class CustomerActions
{
    public static function viewBalance($customerId)
    {
        global $conn;
        $query = "SELECT balance FROM customers WHERE id = :customerId";
        $stmt = $conn->prepare($query);
        $stmt->execute([':customerId' => $customerId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function updateProfile($customer)
    {
        global $conn;
        $query = "UPDATE customers SET name = :name, email = :email WHERE id = :id";
        $stmt = $conn->prepare($query);
        return $stmt->execute([
            ':name' => $customer['name'],
            ':email' => $customer['email'],
            ':id' => $customer['id']
        ]);
    }

    public static function changePassword($customerId, $newPassword)
    {
        global $conn;
        $query = "UPDATE customers SET password = :newPassword WHERE id = :customerId";
        $stmt = $conn->prepare($query);
        return $stmt->execute([':newPassword' => password_hash($newPassword, PASSWORD_BCRYPT), ':customerId' => $customerId]);
    }
}
