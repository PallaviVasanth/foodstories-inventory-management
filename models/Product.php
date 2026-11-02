<?php

class Product
{
    private PDO $conn;

    /** Stores the shared PDO connection for this model. Injecting it avoids opening a new connection per method. */

    public function __construct($db)
    {
        $this->conn = $db;
    }

    /** Fetches products with their category names, newest ID first. JOIN returns related data in one query instead of running a query per product. */

    public function getAll()
    {
        $sql = "
            SELECT
                p.*,
                c.name AS category_name
            FROM products p
            INNER JOIN categories c
                ON p.category_id = c.id
            ORDER BY p.id DESC
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Fetches one product and its category by ID. A prepared statement binds the ID safely; concatenating it into SQL can allow SQL injection. */

    public function getById($id)
    {
        $sql = "
            SELECT
                p.*,
                c.name AS category_name
            FROM products p
            INNER JOIN categories c
                ON p.category_id = c.id
            WHERE p.id = ?
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /** Inserts a product using bound parameters. Prepared statements are safer than building SQL strings with user input. */

    public function create($data)
    {
        $sql = "
            INSERT INTO products
            (
                name,
                sku,
                category_id,
                price,
                quantity,
                low_stock_threshold
            )
            VALUES
            (?, ?, ?, ?, ?, ?)
        ";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            $data['name'],
            $data['sku'],
            $data['category_id'],
            $data['price'],
            $data['quantity'],
            $data['low_stock_threshold']
        ]);
    }

    /** Checks whether a SKU is already present. This gives a friendly conflict response; a UNIQUE database constraint should also enforce this against concurrent requests. */

    public function skuExists($sku)
    {
        $stmt = $this->conn->prepare(
            "SELECT id FROM products WHERE sku = ?"
        );

        $stmt->execute([$sku]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /** Updates a product's editable fields by ID. One parameterized UPDATE is simpler and safer than constructing SQL dynamically. */

    public function update($id, $data)
    {
        $sql = "
            UPDATE products
            SET
                name = ?,
                sku = ?,
                category_id = ?,
                price = ?,
                quantity = ?,
                low_stock_threshold = ?
            WHERE id = ?
        ";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            $data['name'],
            $data['sku'],
            $data['category_id'],
            $data['price'],
            $data['quantity'],
            $data['low_stock_threshold'],
            $id
        ]);
    }

    /** Deletes a product by ID using a prepared statement. Soft deletion is an alternative if records must be retained for audit purposes. */

    public function delete($id)
    {
        $stmt = $this->conn->prepare(
            "DELETE FROM products WHERE id = ?"
        );

        return $stmt->execute([$id]);
    }

    /** Finds products whose name or SKU contains the keyword. SQL LIKE performs filtering in the database; PHP-side filtering would require loading more rows. */

    public function search($keyword)
    {
        $sql = "
            SELECT
                p.*,
                c.name AS category_name
            FROM products p
            INNER JOIN categories c
                ON p.category_id = c.id
            WHERE p.name LIKE ?
            OR p.sku LIKE ?
        ";

        $stmt = $this->conn->prepare($sql);

        $keyword = "%{$keyword}%";

        $stmt->execute([
            $keyword,
            $keyword
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Returns products for a category ID. SQL filtering avoids fetching unrelated products. */

    public function getByCategory($categoryId)
    {
        $sql = "
            SELECT
                p.*,
                c.name AS category_name
            FROM products p
            INNER JOIN categories c
                ON p.category_id = c.id
            WHERE p.category_id = ?
        ";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([$categoryId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Adds the supplied quantity to current stock in SQL. Updating quantity in the database avoids a separate read-and-write calculation in PHP. */

    public function increaseStock($productId, $quantity)
    {
        $stmt = $this->conn->prepare(
            "UPDATE products
             SET quantity = quantity + ?
             WHERE id = ?"
        );

        return $stmt->execute([
            $quantity,
            $productId
        ]);
    }

    /** Subtracts the supplied quantity from current stock. The controller checks availability and uses a transaction/lock; a database-side guard can add further protection. */

    public function decreaseStock($productId, $quantity)
    {
        $stmt = $this->conn->prepare(
            "UPDATE products
             SET quantity = quantity - ?
             WHERE id = ?"
        );

        return $stmt->execute([
            $quantity,
            $productId
        ]);
    }

    /** Reads stock while locking the row for the current transaction. FOR UPDATE helps coordinate concurrent stock changes; a normal SELECT does not lock the row. */

    public function getQuantityForUpdate($productId)
    {
        $stmt = $this->conn->prepare(
            "SELECT quantity
            FROM products
            WHERE id = ?
            FOR UPDATE"
        );

        $stmt->execute([$productId]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
