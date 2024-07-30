<?php

require_once "link_db.php";
echo "Conecton success";
// Create database
$sql = "CREATE DATABASE IF NOT EXISTS myDB";
if ($conn->query($sql) === true) {
    echo "Database created successfully<br>";
} else {
    echo "Error creating database: " . $conn->error;
}

$sql = "USE myDB";

if ($conn->query($sql) === true) {
    echo "using myDB<br>";
} else {
    echo "Error using db: " . $conn->error;
}

//DROPS DE TABLES

// $sql = "DROP TABLE IF EXISTS mydatabase.Stores_Products";

// if ($conn->query($sql) === true) {
//     echo "Table Stores_Products Deleted successfully";
// } else {
//     echo "Error Deleting table: " . $conn->error;
// }

// $sql = "DROP TABLE IF EXISTS mydatabase.Stores";

// if ($conn->query($sql) === true) {
//     echo "Table Stores Deleted successfully";
// } else {
//     echo "Error Deleting table: " . $conn->error;
// }

// $sql = "DROP TABLE IF EXISTS mydatabase.Cities";

// if ($conn->query($sql) === true) {
//     echo "Table Cities Deleted successfully";
// } else {
//     echo "Error Deleting table: " . $conn->error;
// }

// $sql = "DROP TABLE IF EXISTS mydatabase.Products";

// if ($conn->query($sql) === true) {
//     echo "Table Products Deleted successfully";
// } else {
//     echo "Error Deleting table: " . $conn->error;
// }

//TABLA CITIES
$sql = "CREATE TABLE IF NOT EXISTS mydatabase.Cities (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    city VARCHAR(30) NOT NULL
    )";

if ($conn->query($sql) === true) {
    echo "Table Cities created successfully<br>";
    $sql = "INSERT INTO mydatabase.Cities VALUES (1,'Barcelona')";

    if ($conn->query($sql) === true) {
        echo "Table Cities fill successfully<br>";
    } else {
        echo "Error filling table: " . $conn->error;
    }
} else {
    echo "Error creating table: " . $conn->error;
}

//TABLA STORES
$sql = "CREATE TABLE IF NOT EXISTS mydatabase.Stores (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    city INT(6) UNSIGNED NOT NULL,
    `address` VARCHAR(50),
    phone INT(9),
    email VARCHAR(50) NOT NULL,
    opening_time TIME,
    closing_time TIME,
    last_updated DATETIME,
    FOREIGN KEY (city) REFERENCES Cities(id) ON DELETE CASCADE ON UPDATE CASCADE
    )";

if ($conn->query($sql) === true) {
    echo "Table Stores created successfully<br>";
} else {
    echo "Error creating table: " . $conn->error;
}

//TABLA PRODUCTS
$sql = "CREATE TABLE IF NOT EXISTS mydatabase.Products (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(50) NOT NULL,
    `description` VARCHAR(200),
    brand VARCHAR(50),
    price FLOAT(6, 2) NOT NULL,
    cost FLOAT(6, 2),
    `weight` FLOAT(6, 2),
    dimensions FLOAT(6, 2),
    last_updated DATETIME
    )";

if ($conn->query($sql) === true) {
    echo "Table Products created successfully<br>";
} else {
    echo "Error creating table: " . $conn->error;
}

//TABLA STORES_PRODUCTS
$sql = "CREATE TABLE IF NOT EXISTS mydatabase.Stores_Products (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_store INT(6) UNSIGNED NOT NULL,
    id_product INT(6) UNSIGNED NOT NULL,
    stock_quantity INT(6),
    FOREIGN KEY (id_store) REFERENCES Stores(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_product) REFERENCES Products(id) ON DELETE CASCADE ON UPDATE CASCADE
    )";

if ($conn->query($sql) === true) {
    echo "Table Stores_Products created successfully<br>";
} else {
    echo "Error creating table: " . $conn->error;
}
