<?php
session_start();

if (empty($_SESSION["id_store"])) {
    $_SESSION["id_store"] = $_GET["store_id"];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Productos en tienda</title>
    <style>
        /* Add any additional styles specific to your page */
        body {
            padding-top: 20px;
        }

        .warp_create_shop {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h1>Lista de Productos <?php print $_SESSION["id_store"] ?></h1>
        <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php
        # Need SQL connection
        require_once "link_db.php";

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_id"])) {
            $delete_id = $_POST["delete_id"];

            $delete_sql = "DELETE FROM mydatabase.Stores_Products WHERE id = $delete_id";
            if ($conn->query($delete_sql)) {
                echo "Fila eliminada correctamente.";
            } else {
                echo "Error al eliminar la fila: " . $conn->error;
            }
        }

        $sql = "SELECT STPR.id, STPR.id_product, STPR.stock_quantity, PRO.name 
        FROM mydatabase.Stores_Products AS STPR
        INNER JOIN mydatabase.Products AS PRO ON STPR.id_product = PRO.id
        WHERE STPR.id_store = " . $_SESSION["id_store"] . ";";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>" . $row["id_product"] . "</td>
                    <td>" . $row["name"] . "</td>
                    <td>" . $row["stock_quantity"] . "</td>
                    <td>
                        <a class='btn btn-primary update_button' href='/stores_products_update.php?id=" .
                        $row["id"] . "'>Update Stock</a>
                        <form method='post' style='display:inline;'>
                            <input type='hidden' name='delete_id' value='" . $row["id"] . "'>
                            <button type='submit' class='btn btn-danger btn-delete'>Eliminar</button>
                        </form>
                    </td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No se encontraron resultados.</td></tr>";
        }
            $conn->close();
        ?>
        </tbody>
    </table>
    <a class="btn btn-success" href="./stores_products_create.php">Añadir Nuevo Produto</a>
</div>
</body>

</html>
