FoodStories Inventory Management System API Documentation
Base URL

http://localhost:8000

Product Management
Get All Products
GET /products

Response

{
  "success": true,
  "data": []
}
Get Product By ID
GET /products/{id}

Example

GET /products/1

Response

{
  "success": true,
  "data": {
    "id": 1,
    "name": "Chocolate Cake"
  }
}
Create Product
POST /products

Request

{
  "name": "Rice",
  "sku": "SKU001",
  "category_id": 1,
  "price": 50,
  "quantity": 100,
  "low_stock_threshold": 10
}
Response

{
  "success": true,
  "data": {
    "message": "Product created successfully"
  }
}
Update Product
PUT /products/{id}

Request

{
  "name": "Rice Premium",
  "sku": "SKU001",
  "category_id": 1,
  "price": 60,
  "quantity": 120,
  "low_stock_threshold": 10
}
Response

{
  "success": true,
  "data": {
    "message": "Product updated successfully"
  }
}
Delete Product
DELETE /products/{id}

Response

{
  "success": true,
  "data": {
    "message": "Product deleted successfully"
  }
}
Search Products
GET /products/search?keyword=juice

Response

{
  "success": true,
  "data": []
}
Filter By Category
GET /products/category/{id}

Example

GET /products/category/5

Response

{
  "success": true,
  "data": []
}
Stock Management
Stock IN
POST /stock/in

Request

{
  "product_id": 4,
  "quantity": 20,
  "reason": "New Stock Arrival"
}
Response

{
  "success": true,
  "data": {
    "message": "Stock added successfully"
  }
}
Stock OUT
POST /stock/out

Request

{
  "product_id": 4,
  "quantity": 10,
  "reason": "Sold"
}
Response

{
  "success": true,
  "data": {
    "message": "Stock removed successfully"
  }
}
Movement History
GET /products/{id}/movements

Example

GET /products/4/movements

Response

{
  "success": true,
  "data": []
}
Inventory Summary
GET /summary

Response

{
  "success": true,
  "data": {
    "total_products": 5,
    "total_units": 268,
    "low_stock_products": 0,
    "out_of_stock_products": 0
  }
}
Error Responses
Product Not Found
{
  "success": false,
  "message": "Product not found"
}
SKU Already Exists
{
  "success": false,
  "message": "SKU already exists"
}
Insufficient Stock
{
  "success": false,
  "message": "Insufficient stock"
}
Validation Error
{
  "success": false,
  "message": "Quantity must be positive"
}