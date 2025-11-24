<?php

class PurchaseDTO
{
    private $con;

    public function __construct(PDO $pdo)
    {
        $this->con = $pdo;
    }

    public function getAllPurchases()
    {
        $sql = "SELECT 
                    p.id,
                    p.id_user,
                    p.id_book,
                    p.price,
                    p.date as purchase_date,
                    u.name as user_name,
                    b.title as book_title,
                    'completed' as status
                FROM purchases p
                INNER JOIN users u ON p.id_user = u.id
                INNER JOIN books b ON p.id_book = b.id
                ORDER BY p.date DESC";
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getSalesByMonth()
    {
        $sql = "SELECT 
    DATE_FORMAT(date, '%b') AS month,
    SUM(price) AS total
FROM purchases
GROUP BY DATE_FORMAT(date, '%b')
";
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getTotalRevenue()
    {
        $sql = "SELECT SUM(price) as total FROM purchases";
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?: 0;
    }

    public function getActiveCustomers()
    {
        $sql = "SELECT COUNT(DISTINCT id_user) as active_customers FROM purchases";
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['active_customers'] ?: 0;
    }

    public function createPurchase($id_user, $id_book, $price, $status = 'completed')
    {
        $sql = "INSERT INTO purchases (id_user, id_book, price, date) VALUES (:id_user, :id_book, :price, NOW())";
        $stmt = $this->con->prepare($sql);
        $stmt->bindParam(':id_user', $id_user);
        $stmt->bindParam(':id_book', $id_book);
        $stmt->bindParam(':price', $price);
        return $stmt->execute();
    }

    public function updatePurchase($id, $id_user, $id_book, $price, $status = 'completed')
    {
        $sql = "UPDATE purchases SET id_user = :id_user, id_book = :id_book, price = :price WHERE id = :id";
        $stmt = $this->con->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':id_user', $id_user);
        $stmt->bindParam(':id_book', $id_book);
        $stmt->bindParam(':price', $price);
        return $stmt->execute();
    }

    public function deletePurchase($id)
    {
        $sql = "DELETE FROM purchases WHERE id = :id";
        $stmt = $this->con->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function getPurchaseById($id)
    {
        $sql = "SELECT 
                    p.id,
                    p.id_user,
                    p.id_book,
                    p.price,
                    p.date as purchase_date,
                    u.name as user_name,
                    b.title as book_title,
                    'completed' as status
                FROM purchases p
                INNER JOIN users u ON p.id_user = u.id
                INNER JOIN books b ON p.id_book = b.id
                WHERE p.id = :id";
        $stmt = $this->con->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
