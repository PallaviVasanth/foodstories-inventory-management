<?php

// Loads a dependency only once, preventing duplicate class/function declarations. `include` or Composer autoloading are alternatives; require_once is simple for this small project.

require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../helpers/Response.php';
require_once __DIR__ . '/../helpers/Validator.php';

/** Handles product API requests and delegates database work to Product. This separates HTTP logic from SQL; putting queries here would mix responsibilities. */
class ProductController
{
    private Product $product;                               // Restricts this property to the class, protecting internal implementation details. `public` would expose it unnecessarily; typed properties also document expected data types where used.

    /** Receives the shared database connection and builds the model. Dependency injection is used instead of opening a new connection here. */
    public function __construct($db)                        
    {
        $this->product = new Product($db);                  // Assigns a value to a variable for later use. Inline expressions are an alternative, but named variables improve readability and reuse.
    }

    /** Returns all products as JSON. The model owns the query so the controller stays focused on the request/response. */

    public function index()                                 // Declares a public operation callable by the router/other objects. A standalone function is an alternative, but methods group related API behavior inside this controller/model.
    {
        $products = $this->product->getAll();               // Assigns a value to a variable for later use. Inline expressions are an alternative, but named variables improve readability and reuse.

        Response::success($products);                       // Sends a standardized successful JSON API response and ends execution. Repeating `json_encode` and status handling in every controller is an alternative, but the helper avoids duplication.
        
    }

    /** Returns one product by ID, or HTTP 404 if absent. A dedicated lookup is clearer than fetching every product and filtering in PHP. */

    public function show($id)                               // Declares a public operation callable by the router/other objects. A standalone function is an alternative, but methods group related API behavior inside this controller/model.
    {
        $product = $this->product->getById($id);            // Assigns a value to a variable for later use.

        if (!$product)                                      // Checks a condition and runs the block only when it is true. Ternary expressions or early-return patterns are alternatives; an if block is clearer for validation and API flow.
        {
            Response::error(                                // Sends a standardized JSON error response with an HTTP status and ends execution. Throwing exceptions to a global handler is an alternative, but this project uses a small response helper.
                "Product not found",                        // String literal used as a fixed message/value or part of SQL.
                404                                         // Integer literal used as a fixed message/value or part of SQL.    
            );
        }

        Response::success($product);                        // Sends a standardized successful JSON API response and ends execution. Repeating `json_encode` and status handling in every controller is an alternative, but the helper avoids duplication.
    }

    /** Reads JSON, validates fields, checks SKU uniqueness, then creates a product. Server-side validation is used because client-side checks can be bypassed. */

    public function store()                                 // Declares a public operation callable by the router/other objects. A standalone function is an alternative, but methods group related API behavior inside this controller/model.
    {
        $data = json_decode(                                // Parses the incoming JSON request body into a PHP value/array. Form data via `$_POST` is an alternative, but JSON fits this API's request format.
            file_get_contents("php://input"),               // Reads the raw HTTP request body, which PHP exposes through php://input. `$_POST` is an alternative for form submissions, but does not reliably parse JSON bodies.
            true                                            // Converts the JSON into an associative array instead of an object. `false` is an alternative, but arrays are easier to work with in this project. 
        );

        $error = Validator::validateProduct($data);          // Assigns a value to a variable for later use. Inline expressions are an alternative, but named variables improve readability and reuse.

        if ($error) {                                        // Checks a condition and runs the block only when it is true.
            Response::error($error, 422);                    // Sends a standardized JSON error response with an HTTP status and ends execution.                      
        }

        if ($this->product->skuExists($data['sku'])) {

            Response::error(
                "SKU already exists",                         // String literal used as a fixed message/value or part of SQL. Variables/constants are alternatives for repeated or configurable values; a literal is simple for fixed text.
                409
            );
        }

        $this->product->create($data);

        Response::success(
            [
                "message" => "Product created successfully"
            ],
            201
        );
    }

    /** Confirms the product exists, validates submitted JSON, and updates it. Checking first allows a clear 404 response; relying only on the update result gives less context. */

    public function update($id)                               // Declares a public operation callable by the router/other objects. A standalone function is an alternative, but methods group related API behavior inside this controller/model.
    {
        $product = $this->product->getById($id);

        if (!$product) {
            Response::error(
                "Product not found",
                404
            );
        }

        $data = json_decode(                                  // Parses the incoming JSON request body into a PHP value/array. Form data via `$_POST` is an alternative, but JSON fits this API's request format.
            file_get_contents("php://input"),                 // Reads the raw HTTP request body, which PHP exposes through php://input. `$_POST` is an alternative for form submissions, but does not reliably parse JSON bodies.
            true
        );

        $error = Validator::validateProduct($data);

        if ($error) {
            Response::error($error, 422);                  // Sends a standardized JSON error response with an HTTP status and ends execution.      
        }

        $this->product->update($id, $data);                 // Assigns a value to a variable for later use. Inline expressions are an alternative, but named variables improve readability and reuse.

        Response::success([
            "message" => "Product updated successfully"
        ]);
    }

    /** Deletes an existing product and returns a JSON result. The existence check provides a meaningful 404 rather than silently deleting zero rows. */

    public function delete($id)
    {
        $product = $this->product->getById($id);

        if (!$product)
        {
            Response::error(
                "Product not found",
                404
            );
        }

        $this->product->delete($id);

        Response::success([
            "message" => "Product deleted successfully"
        ]);
    }

    /** Searches products using the optional keyword query parameter. A query parameter keeps the endpoint simple; a request body is more suitable for complex search filters. */

    public function search()
    {
        $keyword = $_GET['keyword'] ?? '';

        $products = $this->product->search($keyword);

        Response::success($products);
    }

    /** Returns products in one category. Filtering in SQL is more efficient than loading all products and filtering in PHP. */

    public function category($categoryId)
    {
        $products = $this->product->getByCategory(
            $categoryId
        );

        Response::success($products);
    }
}



