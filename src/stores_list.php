<?php
session_start();
session_unset();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/list.css">
    <title>Store List</title>
</head>

<body>

<div class="container mt-5">
    <h1>Lista de Productos</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>City</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php
        # Need SQL connection
        require_once "link_db.php";

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_id"])) {
            $delete_id = $_POST["delete_id"];
            $delete_sql = "DELETE FROM mydatabase.Stores WHERE id = $delete_id";
            if ($conn->query($delete_sql)) {
                echo "Row deleted successfully.";
            } else {
                echo "Error deleting row: " . $conn->error;
            }
        }
        $sql = "SELECT id, city, email, phone FROM   mydatabase.Stores";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["city"] . "</td>";
                echo "<td>" . $row["email"] . "</td>";
                echo "<td>" . $row["phone"] . "</td>";
                echo "<td>
                    <a class='btn btn-primary update_button' href='/stores_update.php?id=" . $row["id"] . "'>
                        Update Shop
                    </a>
                    <form method='post' style='display:inline;'>
                        <input type='hidden' name='delete_id' value='" . $row["id"] . "'>
                        <button type='submit' class='btn btn-danger btn-delete'>Delete</button>
                    </form>
                    <a class='btn btn-primary update_button' href='/stores_products_list.php?store_id=" .
                    $row["id"] . "'>See Store Products</a>

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
    
    <a class="btn btn-success" href="/stores_create.php">Create a new Shop!</a>
   
</div>
</body>
</html>
