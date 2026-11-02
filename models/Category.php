// Reserved for future category-related functionality currently not needed in the project. The Category class is a placeholder for potential future expansion, such as adding methods for creating, updating, or deleting categories.

<?php

class Category
{
    private PDO $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAll()
    {
        $stmt = $this->conn->prepare(
            "SELECT * FROM categories"
        );

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}