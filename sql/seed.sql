USE foodstories_inventory;

INSERT INTO categories(name)
VALUES
('Bakery'),
('Cakes'),
('Dairy'),
('Fruits'),
('Beverages');

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
(
    'Chocolate Cake',
    'CAKE001',
    2,
    500.00,
    20,
    5
),
(
    'White Bread',
    'BAK001',
    1,
    50.00,
    30,
    10
),
(
    'Apple',
    'FRU001',
    4,
    120.00,
    100,
    20
),
(
    'Orange Juice',
    'BEV001',
    5,
    20.00,
    18,
    10
),
(
    'Milk',
    'DAI001',
    3,
    60.00,
    100,
    15
);