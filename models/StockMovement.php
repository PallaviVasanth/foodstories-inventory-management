<?php

/** Records and retrieves stock movement history. Keeping audit records separate from products preserves a history of each stock change. */

class StockMovement
{
    private PDO $conn;

    /** Stores the shared PDO connection so movement writes can participate in the stock transaction. */

    public function __construct($db)
    {
        $this->conn = $db;
    }

    /** Inserts one IN/OUT movement with quantity and reason. A history table is preferable to overwriting a single last-movement field because it preserves past changes. */

    public function create(
        $productId,
        $type,
        $quantity,
        $reason
    )
    {
        $sql = "
            INSERT INTO stock_movements
            (
                product_id,
                type,
                quantity,
                reason
            )
            VALUES
            (?, ?, ?, ?)
        ";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            $productId,
            $type,
            $quantity,
            $reason
        ]);
    }

    /** Retrieves a product's movements newest first. Ordering in SQL avoids sorting the full result in PHP. */

    public function getHistory($productId)
    {
        $stmt = $this->conn->prepare(
            "SELECT *
             FROM stock_movements
             WHERE product_id = ?
             ORDER BY created_at DESC"
        );

        $stmt->execute([$productId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}