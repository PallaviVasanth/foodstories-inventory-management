# Foodstories Inventory Management System

A web-based Inventory Management System developed using **Core PHP and MySQL** to manage products, categories, stock movements, suppliers, and purchase orders.

The system is designed to simplify inventory operations, maintain accurate stock records, and provide better visibility into inventory activities.

## 🚀 Features

- **Product Management:** Add, update, view, and manage products with unique SKUs.
- **Category Management:** Organize products into different categories.
- **Stock Management:** Track stock quantities and maintain inventory records.
- **Stock Movement History:** Record and track stock-related transactions.
- **Supplier Management:** Maintain supplier information.
- **Purchase Order Management:** Create and manage purchase orders and their associated items.
- **Inventory Reports:** View inventory information and stock summaries.
- **Data Validation:** Apply validation rules to maintain data consistency.
- **Database Management:** Store and manage inventory data using MySQL.

## 🛠️ Technologies Used

| Technology | Purpose |
|---|---|
| PHP 8.4 | Backend development |
| MySQL | Database management |
| PDO | Secure database connectivity |
| HTML5 | Application structure |
| CSS3 | Styling |
| JavaScript | Client-side interactions |
| Apache / PHP Development Server | Local application hosting |

## 📁 Project Structure

```text
foodstories-inventory/
│
├── config/
│   └── Database.php
│
├── controllers/
│   ├── ProductController.php
│   ├── StockController.php
│   └── SummaryController.php
│
├── helpers/
│   ├── Response.php
│   └── Validator.php
│
├── models/
│   ├── Product.php
│   ├── Category.php
│   └── StockMovement.php
│
├── routes/
│   └── api.php
│
├── sql/
│   └── Database scripts
│
├── index.php
├── README.md
└── .gitignore
```

The Folder Structure of foodstories_inventory Project

Folder -> foodstories_inventory -> Inside this

Folder/File				                        Purpose
			
config/database.php			                  Used to store the database details of connection configuration.
controllers/ProductController.php	        Used to handle product-related API operations.
controllers/StockController.php		        Used to handle stock details like additions of the stocks, removals of the stocks, and also the history of the stocks.
controllers/SummaryController.php	        Used to handle summary requests.
helpers/Response.php			                Used to help in format API responses.
helpers/Validator.php			                Used to validate incoming data.
models/Category.php			                  Used for category related database operations.
models/Product.php			                  Used for handling product's database operations.
models/Stock_Movement.php		              Used for handling stock movement records.
routes/api.php				                    Which defines how API requests are routed.
sql/schema.sql				                    Contains SQL statements for creating database tables.
index.php				                          Typically serves as the entry point for incoming requests.
README.md				                          Documentation project setup and usage.


*Note: Update this structure to match the actual files in your repository.*

## ⚙️ Installation and Setup

### Prerequisites

Make sure you have installed:

- PHP 8.4 or later
- MySQL Server
- Git
- A code editor such as Visual Studio Code

### 1. Clone the Repository

```bash
git clone https://github.com/PallaviVasanth/foodstories-inventory-management.git
```

### 2. Navigate to the Project Directory

```bash
cd foodstories-inventory-management
```

### 3. Create the Database

Open MySQL and execute:

```sql
CREATE DATABASE foodstories_inventory;
```

### 4. Configure Database Connection

Update the database configuration in:

```text
config/Database.php
```

Provide your local MySQL connection details.

### 5. Import Database Tables

Execute the SQL scripts available in the `sql/` directory to create the required database tables.

### 6. Start the PHP Development Server

Run:

```bash
php -S localhost:8000
```

### 7. Open the Application

Visit:

```text
http://localhost:8000
```

## 🗄️ Database

The application uses MySQL to store and manage inventory-related information.

The database includes tables for:

- Categories
- Products
- Stock Movements
- Suppliers
- Purchase Orders
- Purchase Order Items

These tables help maintain relationships between products, categories, suppliers, and inventory transactions.

## 🏗️ Application Architecture

The project follows a structured backend architecture:

1. **Configuration Layer:** Handles database connectivity and configuration.
2. **Controller Layer:** Handles incoming requests and coordinates application operations.
3. **Model Layer:** Handles database operations and data management.
4. **Helper Layer:** Provides reusable functionality such as validation and standardized responses.
5. **Routing Layer:** Organizes application routes and request handling.

## 🔒 Validation and Data Integrity

The application incorporates business rules to maintain reliable inventory data, including:

- Unique SKU validation.
- Non-negative product prices.
- Non-negative stock quantities.
- Input validation.
- Consistent stock movement records.

  ## When I open CMD

Step 1: "C:\Program Files\MySQL\MySQL Server 26.10\bin\mysql.exe" -u root -p
Step 2: MySQL Password
Step 3: show databases;
Step 4: use foodstories_inventory;
Step 5: show tables;


To Start PHP Server

Step 1: C:\foodstories-inventory
Step 2: C:\php\php.exe -S localhost:8000

## 🎯 Project Objective

The objective of this project is to develop a structured inventory management solution using Core PHP and MySQL while applying backend development concepts such as database connectivity, CRUD operations, validation, modular architecture, and relational database management.

## 👩‍💻 Author

**Pallavi Vasanth**

- GitHub: [@PallaviVasanth](https://github.com/PallaviVasanth)

---

*Developed as part of backend development work at Foodstories.*
