<?php

require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/StockMovement.php';
require_once __DIR__ . '/../helpers/Response.php';

/** Handles stock-in, stock-out, and movement-history API requests. Stock changes and history logging are coordinated here because they form one business operation. */

class StockController
{
    private $db;
    private $product;
    private $movement;

    /** Stores the shared PDO connection and creates the required models. Reusing the connection lets stock updates and logs share one transaction. */

    public function __construct($db)
    {
        $this->db = $db;

        $this->product = new Product($db);

        $this->movement = new StockMovement($db);
    }

    /** Adds stock and records an IN movement in one transaction. A transaction is chosen so both changes succeed together; separate writes could leave stock and history inconsistent. */

    public function stockIn()
    {
        $data = json_decode(
            file_get_contents("php://input"),
            true
        );

        if ($data['quantity'] <= 0)
        {
            Response::error(
                "Quantity must be positive",
                422
            );
        }

        try
        {
            $this->db->beginTransaction();

            $stock = $this->product->getQuantityForUpdate(
                $data['product_id']
            );

            if (!$stock)
            {
                $this->db->rollBack();

                Response::error(
                    "Product not found",
                    404
                );
            }

            $this->product->increaseStock(
                $data['product_id'],
                $data['quantity']
            );

            $this->movement->create(
                $data['product_id'],
                'IN',
                $data['quantity'],
                $data['reason']
            );

            $this->db->commit();

            Response::success([
                "message" => "Stock added successfully"
            ]);
        }
        catch(Exception $e)
        {
            $this->db->rollBack();

            Response::error(
                $e->getMessage(),
                500
            );
        }
    }

    /** Checks available stock, removes units, and records an OUT movement transactionally. Row locking/rechecking helps prevent concurrent requests from removing the same stock; a simple read-then-update alone is less safe. */

    public function stockOut()
    {
        $data = json_decode(
            file_get_contents("php://input"),
            true
        );

        if ($data['quantity'] <= 0)
        {
            Response::error(
                "Quantity must be positive",
                422
            );
        }

        $stock = $this->product->getQuantity(
            $data['product_id']
        );

        if (!$stock)
        {
            Response::error(
                "Product not found",
                404
            );
        }

        if ($data['quantity'] > $stock['quantity'])
        {
            Response::error(
                "Insufficient stock",
                422
            );
        }

        try
        {
            $this->db->beginTransaction();

            $stock = $this->product->getQuantityForUpdate(
                $data['product_id']
            );

            if (!$stock)
            {
                $this->db->rollBack();

                Response::error(
                    "Product not found",
                    404
                );
            }

            if ($data['quantity'] > $stock['quantity'])
            {
                $this->db->rollBack();

                Response::error(
                    "Insufficient stock",
                    422
                );
        }

            $this->product->decreaseStock(
                $data['product_id'],
                $data['quantity']
            );

            $this->movement->create(
                $data['product_id'],
                'OUT',
                $data['quantity'],
                $data['reason']
            );

            $this->db->commit();

            Response::success([
                "message" => "Stock removed successfully"
            ]);
        }
        catch(Exception $e)
        {
            $this->db->rollBack();

            Response::error(
                $e->getMessage(),
                500
            );
        }
    }

    /** Returns movement history for an existing product. Checking the product first distinguishes an unknown product from an empty history. */

    public function history($productId)
    {
        $product = $this->product->getById(
            $productId
        );

        if (!$product)
        {
            Response::error(
                "Product not found",
                404
            );
        }

        $history = $this->movement->getHistory(
            $productId
        );

        Response::success($history);
    }
}