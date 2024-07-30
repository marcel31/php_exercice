<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Productos</title>
</head>
<body>

<div class="container mt-5">
    <h1>Lista de Productos</h1>
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
        require_once "link_db.php";

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_id"])) {
            $delete_id = $_POST["delete_id"];
            $delete_sql = "DELETE FROM mydatabase.Products WHERE id = $delete_id";
            if ($conn->query($delete_sql)) {
                echo "Fila eliminada correctamente.";
            } else {
                echo "Error al eliminar la fila: " . $conn->error;
            }
        }

        $sql = "SELECT * FROM mydatabase.Products";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["name"] . "</td>";
                echo "<td>
                        <a class='btn btn-primary btn-edit' href='./edit_products.php?id=" . $row["id"] . "'>Editar</a>
                        <form method='post' style='display:inline;'>
                            <input type='hidden' name='delete_id' value='" . $row["id"] . "'>
                            <button type='submit' class='btn btn-danger btn-delete'>Eliminar</button>
                        </form>
                    </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No se encontraron resultados.</td></tr>";
        }
        $conn->close();
        ?>
        </tbody>
    </table>
    
    <a class="btn btn-success" href="./create_product.php">Crear Nuevo</a>
</div>

</body>
</html>
