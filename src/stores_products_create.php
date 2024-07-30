<?php

session_start();

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

    $id_storeErr = $citiesErr = $emailErr = $addressErr = $phoneErr = $openingErr = $closingErr = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        # Input verification
        $validation = true;

        # Address verification
        if (empty($_POST['id_product'])) {
            $addressErr = "Tienes que introducir un producto";
            $validation = false;
        } else {
            $id_product = $_POST['id_product'];
        }

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
            <h2>Add a new produt to the Store <?php print $_SESSION["id_store"] ?></h2>
            <div class="form-group">
                <label for="id_product">Select Product: </label>
                <select id="id_product" name="id_product" class="form-control" required>
                    <?php
                    $sql = "SELECT id, `name` FROM  mydatabase.Products";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            print "<option value=" . $row["id"] . ">" . $row["name"] . "</option>";
                        }
                    } else {
                        print "<option value='0'>No Products</option>";
                        echo $conn->error;
                    }
                    ?>
                </select>
                <small class="text-danger"><?php print $citiesErr;?></small>
            </div>

            <div class="form-group">
                <label for="stock_quantity">Default Stock: </label>
                <input type="number" id="stock_quantity" name="stock_quantity" class="form-control" value="0">
                <small class="text-danger"><?php echo $openingErr;?></small>
            </div>
            <button type="submit" class="btn btn-primary">Crear</button>
        </form>
        <a class="btn btn-secondary mt-3" href="./stores_products_list.php">Lista de productos</a>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            # DB connection
            $id_store = $_SESSION["id_store"];
            if ($validation) {
                $stmt = $conn->prepare("INSERT INTO  
                mydatabase.Stores_Products (id_store, id_product, stock_quantity) 
                VALUES (?, ?, ?)");

                $stmt->bind_param(
                    "iii",
                    $id_store,
                    $id_product,
                    $stock_quantity
                );
                $stmt->execute();
            }
        }
        ?>
    </div>
</body>
</html>
