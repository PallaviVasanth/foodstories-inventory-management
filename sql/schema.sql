CREATE DATABASE IF NOT EXISTS foodstories_inventory;

USE foodstories_inventory;

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    sku VARCHAR(50) NOT NULL UNIQUE,
    category_id INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL DEFAULT 0,
    low_stock_threshold INT NOT NULL DEFAULT 10,

    CONSTRAINT fk_products_category
        FOREIGN KEY (category_id)
        REFERENCES categories(id),

    CONSTRAINT chk_price
        CHECK (price >= 0),

    CONSTRAINT chk_quantity
        CHECK (quantity >= 0),

    CONSTRAINT chk_threshold
        CHECK (low_stock_threshold >= 0)
);

CREATE TABLE stock_movements (
    id INT AUTO_INCREMENT PRIMARY KEY,

    product_id INT NOT NULL,

    type ENUM('IN','OUT') NOT NULL,

    quantity INT NOT NULL,

    reason VARCHAR(255) NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_stock_product
        FOREIGN KEY(product_id)
        REFERENCES products(id),

    CONSTRAINT chk_movement_quantity
        CHECK (quantity > 0)
);