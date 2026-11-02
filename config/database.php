<?php

/** Opens the MySQL connection; kept in one class so controllers/models can reuse it. Alternative: connect in every file, but that duplicates credentials and connection logic. */

class Database
{
    // Restricts this property to the class, protecting internal implementation details. `public` would expose it unnecessarily; typed properties also document expected data types where used.
    
    private $host = "localhost";
    private $dbname = "foodstories_inventory";
    private $username = "root";
    private $password = "mysql123";

    /** Creates and returns a PDO connection with exception-based errors. PDO is chosen for prepared statements and portability; mysqli is another option but is MySQL-specific. */
  
    public function connect()
    {
        // Starts an exception-handling block for operations that may throw. Checking every return value is an alternative, but exceptions centralize database error handling.
        
        try {

            // Creates a PDO database connection using the configured DSN and credentials.
            $pdo = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname}",          // String literal used as a fixed message/value or part of SQL.
                $this->username,                                            // Assigns a value to a variable for later use.
                $this->password
            );

            $pdo->setAttribute(
                PDO::ATTR_ERRMODE,                                           // Selects PDO's error-reporting configuration.
                PDO::ERRMODE_EXCEPTION                                       // Makes PDO throw exceptions for database errors.              
            );

            return $pdo;                                                     // Returns a value to the caller and stops this method.

        } catch(PDOException $e) {                                           // Closes the current block or statement so PHP can parse the structure correctly. 

            die(json_encode([                                                // Converts a PHP array/value into JSON for API clients.
                "success" => false,                                          // String literal used as a fixed message/value or part of SQL.
                "message" => $e->getMessage()
            ]));
        }
    }
}

// FILE PURPOSE: Creates and configures the PDO connection to the MySQL database.
// FOLDER PURPOSE: foodstories-inventory/config groups files by responsibility; keeping related code together makes the project easier to navigate and maintain.
