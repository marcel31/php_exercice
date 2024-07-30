<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Shop</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/list.css">
</head>
<body>
    <?php
    # Input verification
    require_once "link_db.php";

    $idErr = $citiesErr = $emailErr = $addressErr = $phoneErr = $openingErr = $closingErr = "";

    function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        # Input verification
        $validation = true;

        # Id verification
        if (empty($_POST['id'])) {
            $id = null;
        } else {
            $id = $_POST['id'];
        }

        # Cities verification
        if (empty($_POST['cities'])) {
            $citiesErr = "Tienes que seleccionar una ciudad";
            $validation = false;
        } else {
            $cities = $_POST['cities'];
        }

        # Email verification
        if (empty($_POST['email'])) {
            $emailErr = "Email es obligatorio";
            $validation = false;
        } else {
            if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                $emailErr = "El email no tiene el formato correcto.";
                $validation = false;
            }
            $email = $_POST["email"];
        }

        # Address verification
        if (empty($_POST['address'])) {
            $addressErr = "Tienes que introducir una dirección";
            $validation = false;
        } else {
            $address = $_POST['address'];
        }

        # Phone verification
        if (empty($_POST['phone'])) {
            $phoneErr = "Teléfono es obligatorio";
            $validation = false;
        } else {
            if (strlen($_POST["phone"]) < 9) {
                $phoneErr = "El teléfono no es válido.";
                $validation = false;
            }
            $phone = $_POST["phone"];
        }

        # Opening time verification
        if (!empty($_POST['opening_time'])) {
            if (!validateDate($_POST['opening_time'], 'H:i')) {
                $phoneErr = "El tiempo no es válido";
                $validation = false;
            }
            $opening_time = $_POST["opening_time"];
        }

        # Closing time verification
        if (!empty($_POST['closing_time'])) {
            if (!validateDate($_POST['closing_time'], 'H:i')) {
                $phoneErr = "El tiempo no es válido";
                $validation = false;
            }
            $closing_time = $_POST["closing_time"];
        }
    }
    ?>

    <div class="container">
        
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <h2>Create a New Store</h2>
            <div class="form-group">
                <label for="cities">City name: </label>
                <select id="cities" name="cities" class="form-control" required>
                    <?php
                    $sql = "SELECT id, city FROM  mydatabase.Cities";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            print "<option value=" . $row["id"] . ">" . $row["city"] . "</option>";
                        }
                    } else {
                        print "<option value='0'>No cities</option>";
                    }
                    ?>
                </select>
                <small class="text-danger"><?php print $citiesErr;?></small>
            </div>

            <div class="form-group">
                <label for="email">Email: </label>
                <input type="email" id="email" name="email" class="form-control" required>
                <small class="text-danger"><?php print $emailErr;?></small>
            </div>

            <div class="form-group">
                <label for="address">Address: </label>
                <input type="text" id="address" name="address" class="form-control" required>
                <small class="text-danger"><?php echo $addressErr;?></small>
            </div>

            <div class="form-group">
                <label for="phone">Phone: </label>
                <input type="tel" id="phone" name="phone" pattern="[0-9]{9}" class="form-control" required>
                <small class="text-danger"><?php echo $phoneErr;?></small>
            </div>

            <div class="form-group">
                <label for="opening_time">Opening time: </label>
                <input type="time" id="opening_time" name="opening_time" class="form-control">
                <small class="text-danger"><?php echo $openingErr;?></small>
            </div>

            <div class="form-group">
                <label for="closing_time">Closing time: </label>
                <input type="time" id="closing_time" name="closing_time" class="form-control">
                <small class="text-danger"><?php echo $closingErr;?></small>
            </div>

            <button type="submit" class="btn btn-primary">Crear</button>
        </form>
        <a class="btn btn-secondary mt-3" href="./stores_list.php">Lista de productos</a>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            # DB connection

            if ($validation) {
                $stmt = $conn->prepare("INSERT INTO  
                mydatabase.Stores (city, `address`, email, phone, opening_time, closing_time) 
                VALUES (?, ?, ?, ?, ?, ?)");

                $stmt->bind_param(
                    "isssss",
                    $cities,
                    $address,
                    $email,
                    $phone,
                    $opening_time,
                    $closing_time,
                );
                $stmt->execute();
            }
        }
        ?>
    </div>
</body>
</html>
