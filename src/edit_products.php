<?php
require_once "link_db.php";

$id = $_GET['id'];
$nameErr = $descriptionErr = $brandErr = $priceErr = $costErr = $weightErr = $dimensionsErr = "";
$name = $description = $brand = $price = $cost = $weight = $dimensions = "";
$successMessage = $errorMessage = "";

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
        $last_updated = date("Y-m-d h:i:s");
        $stmt = $conn->prepare("UPDATE mydatabase.Products 
        SET `name`=?, `description`=?, brand=?, price=?, cost=?, `weight`=?, dimensions=?, last_updated=? WHERE id=?");
        $stmt->bind_param(
            "ssssdddsi",
            $name,
            $description,
            $brand,
            $price,
            $cost,
            $weight,
            $dimensions,
            $last_updated,
            $id
        );

        $stmt->execute();

        if ($stmt->error) {
            $errorMessage = "Error: " . $stmt->error;
        } else {
            $successMessage = "Producto actualizado!";
        }
    } else {
        $errorMessage = "Corrige los errores";
    }
} else {
    $stmt = $conn->prepare("SELECT * FROM mydatabase.Products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = $row['name'];
        $description = $row['description'];
        $brand = $row['brand'];
        $price = $row['price'];
        $cost = $row['cost'];
        $weight = $row['weight'];
        $dimensions = $row['dimensions'];
    } else {
        $errorMessage = "No se encontró el producto.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/list.css">
</head>
<body>

<div class="container">
    <h2>Edit Product</h2>
    <form class="form" method="post" action="<?php ($_SERVER["PHP_SELF"]); ?>">
        <div class="form-group">
            <label for="name">Nombre</label>
            <input type="text" class="form-control" name="name" value="<?php echo $name; ?>">
            <span class="error">* <?php echo $nameErr; ?></span>
        </div>

        <div class="form-group">
            <label for="description">Descripción</label>
            <input type="text" class="form-control" name="description" value="<?php echo $description; ?>">
            <span class="error"><?php echo $descriptionErr; ?></span>
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
            <span class="error"><?php echo $dimensionsErr; ?></span>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <span class="error"><?php echo $errorMessage; ?></span>
        <span style="color: green;"><?php echo $successMessage; ?></span>
    </form>
    <a class="btn btn-secondary mt-3" href="./crud_products.php">Lista de productos</a>
</div>

</body>
</html>
