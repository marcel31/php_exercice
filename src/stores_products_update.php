<?php
session_start();

if (empty($_SESSION["id_store_products"])) {
    $_SESSION["id_store_products"] = $_GET["id"];
}
$id_store_products = $_SESSION["id_store_products"];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Shop</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        form {
            max-width: 600px;
            margin: auto;
            margin-top: 20px;
        }

        .error {
            color: red;
        }

        .success {
            color: green;
        }
    </style>
</head>
<body>
    <?php
    # Input verification
    require_once "link_db.php";

    $stmt = $conn->prepare("SELECT * FROM mydatabase.Stores_Products WHERE id = ?");
    $stmt->bind_param("i", $id_store_products);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $stock = $row['stock_quantity'];
    } else {
        $errorMessage = "No se encontró el producto.";
    }
    $stockErr = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        # Input verification
        $validation = true;

        # Address verification
        if (empty($_POST['stock_quantity'])) {
            $addressErr = "Tienes que introducir una cantidad";
            $validation = false;
        } else {
            $stock_quantity = $_POST['stock_quantity'];
        }
    }
    ?>

<div class="container">
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <h2>Add a new produt to the Store</h2>
        <div class="form-group">
            <label for="stock_quantity">Stock: </label>
            <input type="number" id="stock_quantity" name="stock_quantity" class="form-control" 
            value="<?php echo $stock ?>">
            <small class="text-danger"><?php echo $stockErr;?></small>
        </div>
        <button type="submit" class="btn btn-primary">Crear</button>
    </form>
    <a href="/stores_products_list.php" class="btn btn-secondary mt-3">Volver a la lista</a>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        # DB connection
        $id = $_SESSION["id_store_products"];
        if ($validation) {
            $stmt = $conn->prepare(
                "UPDATE mydatabase.Stores_Products 
                SET stock_quantity=?
                WHERE id=?"
            );
            $stmt->bind_param("ii", $stock_quantity, $id);
            $stmt->execute();

            if ($stmt->error) {
                print ("Error: " . $stmt->error);
            } else {
                print '<p class="success mt-3">Actualización exitosa</p>';
            }
        }
    }
    ?>
    </div>
</body>
</html>
