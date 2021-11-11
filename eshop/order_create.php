<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Create a Record - PHP CRUD Tutorial</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-md-light bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand text-light" href="home.php">Hanson1030</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active text-light" aria-current="page" href="home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-secondary" href="product_read.php">Read Product</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-secondary" href="product_create.php">Create Product</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-secondary" href="customer_read.php">Read Customer</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-secondary" href="customer_create.php">Create Customer</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-secondary" href="contact_us.php">Contact Us</a>
                    </li>
                </ul>
                <span class="navbar-text">
                    <a class="nav-link text-secondary" href="order_create.php">Create Order</a>
                </span>
            </div>
        </div>
    </nav>
    <!-- container -->
    <div class="container">

        <div class="page-header">
            <h1>Create New Order</h1>
        </div>


        <?php
        include 'config/database.php';


        $q = "SELECT product_id, name, price FROM products";

        $stmt = $con->prepare($q);
        $stmt->execute();

        $cus_username = "SELECT username FROM customers";

        $cu = $con->prepare($cus_username);
        $cu->execute();


        if ($_POST) {
            // include database connection
            include 'config/database.php';

            var_dump($_POST);
            /*
            try {
                // insert query
                $query = "INSERT INTO order_details SET name=:product1, quantity=:quantity1, price=:price1";
                //name=:product2, quantity=:quantity2";
                // prepare query for execution
                $stmt = $con->prepare($query);
                $product1 = $_POST['product1'];
                $quantity1 = $_POST['quantity1'];
                //$price1 = $p1 * $_POST['quantity1'];
                //$product2 = $_POST['product2'];
                //$quantity2 = $_POST['quantity2'];
                // bind the parameters
                $stmt->bindParam(':product1', $product1);
                $stmt->bindParam(':quantity1', $quantity1);
                $stmt->bindParam(':price1', $price1);
                //$stmt->bindParam(':product2', $product2);
                //$stmt->bindParam(':quantity2', $quantity2);


                if ($stmt->execute()) {
                    echo "<div class='alert alert-success'>Record was saved.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Unable to save record.</div>";
                }
            } catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
*/
            try {
                // insert query
                $query = "INSERT INTO order_summary SET username=:cus_username, purchase_date=:purchase_date";
                // prepare query for execution
                $stmt = $con->prepare($query);
                $cus_username = $_POST['cus_username'];
                // bind the parameters
                $stmt->bindParam(':cus_username', $cus_username);
                $purchase_date = date('Y-m-d H:i:s'); // get the current date and time
                $stmt->bindParam(':purchase_date', $purchase_date);

                if ($stmt->execute()) {
                    $last_id = $con->lastInsertId();
                    for ($count = 0; $count < 3; $count++) {
                        $query2 = "INSERT INTO order_details SET order_id=:order_id, product_id=:product_id, quantity=:quantity";
                        $stmt = $con->prepare($query2);
                        $stmt->bindParam(':order_id', $last_id);
                        $stmt->bindParam(':product_id', $_POST['product'][$count]);
                        $stmt->bindParam(':quantity', $_POST['quantity'][$count]);
                        $stmt->execute();
                    }
                    echo "<div class='alert alert-success'>Record was saved.Last inserted ID is: $last_id</div>";
                } else {
                    echo "<div class='alert alert-danger'>Unable to save record.</div>";
                }
            } catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        }

        ?>

        <!-- html form here where the product information will be entered -->
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">

            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Customer Name:</td>
                    <?php
                    echo "<td>";
                    echo '<select class="w-100 fs-4 rounded" id="" name="cus_username">';
                    echo  '<option class="bg-white" disable selected value>Select Your Username</option>';
                    while ($row = $cu->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        echo "<option class='bg-white' value='" . $row['username'] . "'>" . $row['username'] . "</option>";
                    }
                    echo "</td>";
                    ?>
                </tr>
                <tr>
                    <th>Products 1</th>
                    <th>Quantity</th>
                </tr>
                <?php
                $quantity = 1;

                $product_arrID = array();
                $product_arrName = array();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    array_push($product_arrID, $row['product_id']);
                    array_push($product_arrName, $row['name']);
                }
                //print_r($product_arr);

                for ($x = 0; $x <= 2; $x++) {
                    echo "<tr>";
                    echo '<td>
                       <select class="fs-4 rounded" name="product[]">';
                    echo  '<option disable selected value>--Select--</option>';
                    for ($product_count = 0; $product_count < count($product_arrName); $product_count++) {
                        echo  "<option value='" . $product_arrID[$product_count] . "'>" . $product_arrName[$product_count] . "</option>";
                    }
                    echo "</select>";
                    echo '</td>';
                    echo "<td>";
                    echo '<select class="w-100 fs-4 rounded" name="quantity[]" class="form-control">';
                    echo "<option class='bg-white' disable selected value>Please Select Your Quantity</option>";
                    for ($quantity = 1; $quantity <= 5; $quantity++) {
                        echo "<option value='$quantity'>$quantity</option>";
                    }
                    echo '</td>';
                    echo "</tr>";
                }
                ?>

                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save' class='btn btn-primary' />
                        <a href='index.php' class='btn btn-danger'>Back to read products</a>
                    </td>
                </tr>
            </table>
        </form>

    </div>


    <!-- end .container -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>