<?php

require_once __DIR__ . '/../helpers/Response.php';

/** Provides inventory summary metrics for the dashboard. Keeping reporting queries here separates dashboard output from product CRUD operations. */

class SummaryController
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }
    /** Counts products, total units, low-stock items, and out-of-stock items. SQL aggregates are used because the database can calculate these efficiently without loading every product into PHP. */

    public function index()
    {
        $totalProducts = $this->db
            ->query(
                "SELECT COUNT(*) AS total
                 FROM products"
            )
            ->fetch(PDO::FETCH_ASSOC);

        $totalUnits = $this->db
            ->query(
                "SELECT SUM(quantity) AS total
                 FROM products"
            )
            ->fetch(PDO::FETCH_ASSOC);

        $lowStock = $this->db
            ->query(
                "SELECT COUNT(*) AS total
                 FROM products
                 WHERE quantity <= low_stock_threshold"
            )
            ->fetch(PDO::FETCH_ASSOC);

        $outOfStock = $this->db
            ->query(
                "SELECT COUNT(*) AS total
                 FROM products
                 WHERE quantity = 0"
            )
            ->fetch(PDO::FETCH_ASSOC);

        Response::success([
            "total_products"      => (int)$totalProducts['total'],
            "total_units"         => (int)$totalUnits['total'],
            "low_stock_products"  => (int)$lowStock['total'],
            "out_of_stock_products" => (int)$outOfStock['total']
        ]);
    }
}