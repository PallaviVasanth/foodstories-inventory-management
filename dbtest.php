<?php

try {

    $pdo = new PDO(
        "mysql:host=localhost;dbname=foodstories_inventory",
        "root",
        "mysql123"
    );

    echo "Database Connected Successfully";

}
catch(PDOException $e){

    echo $e->getMessage();
}