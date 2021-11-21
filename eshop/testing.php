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
                <span class="navbar-text d-flex">
                    <a class="nav-link text-secondary" href="order_read.php">Read Order</a>
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

        $product_arrID = array();
        $product_arrName = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($product_arrID, $row['product_id']);
            array_push($product_arrName, $row['name']);
        }

        $cus_username = "SELECT username FROM customers";

        $cu = $con->prepare($cus_username);
        $cu->execute();


        if ($_POST) {
            //var_dump($_POST);
            $flag = 0;
            $product_flag = 0;
            $fail_flag = 0;
            $message = '';


            for ($count1 = 0; $count1 < count($_POST['product']); $count1++) {
                if (!empty($_POST['product'][$count1]) && !empty($_POST['quantity'][$count1])) {
                    $product_flag++;
                }
                if (empty($_POST['product'][$count1]) || empty($_POST['quantity'][$count1])) {
                    $fail_flag++;
                }
            }
            if (empty($_POST['cus_username'])) {
                $flag = 1;
                $message = 'Please select Username.';
            } elseif ($product_flag < 1) {
                $flag = 1;
                $message = 'Please select the at least one prouct and the associated quantity';
            } elseif ($fail_flag > 0) {
                $flag = 1;
                $message = 'Please enter prouct and the associated quantity';
            } elseif (count($_POST['product']) !== count(array_unique($_POST['product']))) {
                $flag = 1;
                $message = 'Duplicate product is not allowed.';
            }

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

                if ($flag == 0) {
                    if ($stmt->execute()) {
                        $last_id = $con->lastInsertId();
                        for ($count = 0; $count < count($_POST['product']); $count++) {
                            $query2 = "INSERT INTO order_details SET order_id=:order_id, product_id=:product_id, quantity=:quantity";
                            $stmt = $con->prepare($query2);
                            $stmt->bindParam(':order_id', $last_id);
                            $stmt->bindParam(':product_id', $_POST['product'][$count]);
                            $stmt->bindParam(':quantity', $_POST['quantity'][$count]);
                            if (!empty($_POST['product'][$count]) && !empty($_POST['quantity'][$count])) {
                                $stmt->execute();
                            }
                        }
                        echo "<div class='alert alert-success'>Record was saved.Last inserted ID is: $last_id</div>";
                    } else {
                        echo "<div class='alert alert-danger'>Unable to save record.</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger'>";
                    echo $message;
                    echo "</div>";
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
                    <th>Customer Name<span class="text-danger">*</span>:</th>
                    <?php
                    
                    echo "<td>";
                    echo '<select class="w-100 fs-4 rounded" name="cus_username">';
                    echo  "<option value=''>Select Your Username</option>";
                   
                    while ($row = $cu->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        //$selected_username = $username == $_POST['cus_username'] ? 'selected' : '';
                        $selected_username = $row['username'] == $_POST['cus_username'] ? 'selected' : '';
                        echo "<option class='bg-white' value='" . $username . "' $selected_username>" . $username . "</option>";
                    }
                    //if ($row['username'] == $_POST['cus_username']) {
                    //$selected = 'selected';
                    //}

                    echo "</td>";
                    ?>

                </tr>

                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                </tr>
                <?php
                $quantity = 1;

                //echo $_POST['product'];
                //print_r($product_arrID);
                echo '<br>';
                echo "<tr class='productRow'>";
                echo '<td>
                       <select class="fs-4 rounded" name="product[]">';
                echo  "<option value=''>--Select--</option>";
                for ($product_count = 0; $product_count < count($product_arrName); $product_count++) {
                    $selected_product = $product_arrID[$product_count] == $_POST['product'][0] ? 'selected' : '';
                    echo  "<option value='" . $product_arrID[$product_count] . "' $selected_product>" . $product_arrName[$product_count] . "</option>";
                }
                
                /*
                if ($product_arrID[$product_count] == $_POST['product'][$product_arrID]) {
                    $selected_product = 'selected';
                    } else{
                        $selected_product = '';
                    }
                    */
                    
                echo "</select>";
                echo '</td>';
                echo "<td>";
                echo '<select class="w-100 fs-4 rounded" name="quantity[]" >';
                echo "<option value=''>Please Select Your Quantity</option>";
                for ($quantity = 1; $quantity <= 5; $quantity++) {
                    $selected_quantity = $quantity == $_POST['quantity'][0] ? 'selected' : '123';
                    echo "<option value='$quantity' $selected_quantity>$quantity</option>";
                }
                echo '</td>';
                echo "</tr>";
                ?>


                <tr>
                    <td>
                        <div class="d-flex justify-content-center flex-column flex-lg-row">
                            <div class="d-flex justify-content-center">
                                <button type="button" class="btn btn-primary add_one btn mb-3 mx-2">Add More Product</button>
                                <button type="button" class="btn btn-danger delete_one btn mb-3 mx-2">Delete Last Product</button>
                            </div>
                        </div>
                    </td>
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
    <script>
        document.addEventListener('click', function(event) {
            if (event.target.matches('.add_one')) {
                var element = document.querySelector('.productRow');
                var clone = element.cloneNode(true);
                element.after(clone);
            }
            if (event.target.matches('.delete_one')) {
                var total = document.querySelectorAll('.productRow').length;
                if (total > 1) {
                    var element = document.querySelector('.productRow');
                    element.remove(element);
                }
            }
        }, false);

        function incrementValue() {
            var value = parseInt(document.getElementById('number').value, 10);
            value = isNaN(value) ? 0 : value;
            value++;
            document.getElementById('number').value = value;
        }
    </script>
</body>

</html>