<?php

require_once "link_db.php";

$nameErr = $descriptionErr = $brandErr = $priceErr = $costErr = $weightErr = $dimensionsErr = "";
$name = $description = $brand = $price = $cost = $weight = $dimensions = "";
$successMessage = $errorMessage = "";
$var = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $allGood = true;

    if (empty($_POST["name"])) {
        $nameErr = "El nombre de producto es obligatorio.";
        $allGood = false;
    } else {
        $name = test_input($_POST["name"]);
        if (!preg_match("/^[A-Z][a-zA-Z\s]*$/", $name)) {
            $nameErr = "El nombre debe comenzar con una letra mayúscula y solo puede contener letras.";
            $allGood = false;
        }
    }

    if (!empty($_POST["description"])) {
        $description = test_input($_POST["description"]);
        if (!preg_match("/^[A-Z][a-zA-Z\s,\.]*$/", $description)) {
            $descriptionErr = "La descripción solo puede contener letras y debe comenzar por mayuscula.";
            $allGood = false;
        }
    }

    if (!empty($_POST["brand"])) {
        $brand = test_input($_POST["brand"]);
        if (!preg_match("/^[A-Z][a-zA-Z\s]*$/", $brand)) {
            $brandErr = "La marca solo puede contener letras y debe comenzar por mayuscula.";
            $allGood = false;
        }
    }

    if (empty($_POST["price"])) {
        $priceErr = "El precio es obligatorio.";
        $allGood = false;
    } else {
        $price = test_input($_POST["price"]);
        $price = floatval($price);
        if (!is_float($price) || $price <= 0) {
            $priceErr = "El precio debe ser un número positivo.";
            $allGood = false;
        }
    }

    if (empty($_POST["cost"])) {
        $costErr = "El coste es obligatorio.";
        $allGood = false;
    } else {
        $cost = test_input($_POST["cost"]);
        if ($cost <= 0) {
            $costErr = "El coste debe ser un número positivo.";
            $allGood = false;
        }
    }

    if (empty($_POST["weight"])) {
        $descriptionGood = true;
    } else {
        $weight = test_input($_POST["weight"]);
        if ($weight <= 0) {
            $weightErr = "El peso solo puede ser numérico y positivo.";
            $allGood = false;
        }
    }
    //Modificar esto en los otros!!!!
    if (empty($_POST["dimensions"])) {
    } else {
        $dimensions = test_input($_POST["dimensions"]);
        $dimensions = floatval($dimensions);
        if ($dimensions <= 0) {
            $dimensionsErr = "Las dimensiones del producto solo pueden ser positivas.";
            $allGood = false;
        }
    }

    if ($allGood) {
        $stmt = $conn->prepare("INSERT INTO mydatabase.Products
        (`name`, `description`, brand, price, cost, `weight` , dimensions)
        VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssdddd", $name, $description, $brand, $price, $cost, $weight, $dimensions);

        $stmt->execute();

        if ($stmt->error) {
            $errorMessage = "Error: " . $stmt->error;
        } else {
            $successMessage = "Producto creado!";
        }
    } else {
        $errorMessage = "Corrige los errores";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product Form</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/list.css">
</head>
<body>

<div class="container">
    <h2>Create a New Product</h2>
    <form class="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="form-group">
            <label for="name">Nombre</label>
            <input type="text" class="form-control" name="name" value="<?php echo $name; ?>">
            <span class="error">* <?php echo $nameErr; ?></span>
        </div>

        <div class="form-group">
            <label for="description">Descripción</label>
            <input type="text" class="form-control" name="description" value="<?php echo $description; ?>">
            <span class="error"></span><?php echo $descriptionErr; ?></span>
        </div>

        <div class="form-group">
            <label for="brand">Marca</label>
            <input type="text" class="form-control" name="brand" value="<?php echo $brand; ?>">
            <span class="error"><?php echo $brandErr; ?></span>
        </div>

        <div class="form-group">
            <label for="price">Precio</label>
            <input type="number" step="0.01" class="form-control" name="price" value="<?php echo $price; ?>">
            <span class="error">* <?php echo $priceErr; ?></span>
        </div>

        <div class="form-group">
            <label for="cost">Coste</label>
            <input type="number" step="0.01" class="form-control" name="cost" value="<?php echo $cost; ?>">
            <span class="error">* <?php echo $costErr; ?></span>
        </div>

        <div class="form-group">
            <label for="weight">Peso</label>
            <input type="number" step="0.01" class="form-control" name="weight" value="<?php echo $weight; ?>">
            <span class="error"><?php echo $weightErr; ?></span>
        </div>

        <div class="form-group">
            <label for="dimensions">Dimensiones</label>
            <input type="number" step="0.01" class="form-control" name="dimensions" value="<?php echo $dimensions; ?>">
            <span class="error"><?php echo "$dimensionsErr $var"; ?></span>
        </div>

        <button type="submit" class="btn btn-primary">Crear producto</button>
        <span class="error"><?php echo $errorMessage; ?></span>
        <span style="color: green;"><?php echo $successMessage; ?></span>
    </form>
    <a class="btn btn-secondary mt-3" href="./crud_products.php">Lista de productos</a>
</div>

</body>
</html>
