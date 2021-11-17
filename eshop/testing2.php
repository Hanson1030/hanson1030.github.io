<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Read One Record - PHP CRUD Tutorial</title>
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
            <h1>Read Order</h1>
        </div>

        <?php
        // get passed parameter value, in this case, the record ID
        // isset() is a PHP function used to verify if a value is there or not
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');

        //include database connection
        include 'config/database.php';

        // read current record's data
        try {
            // prepare select query
            $query = "SELECT orderdetail_id, order_id, product_id, quantity FROM order_details WHERE order_id = :order_id";
            //$query = "SELECT order_details.orderdetail_id, order_details.order_id, order_details.product_id, products.name, order_details.quantity FROM order_details INNER JOIN products ON order_details.product_id = products.product_id WHERE orderdetail_id = :orderdetail_id";

            $stmt = $con->prepare($query);

            // Bind the parameter
            $stmt->bindParam(":order_id", $id);

            // execute our query
            $stmt->execute();

            // this is how to get number of rows returned
            $num = $stmt->rowCount();

            // store retrieved row to a variable
            //$row = $stmt->fetch(PDO::FETCH_ASSOC);

            // values to fill up our form
            //$username = $row['username'];
            //$orderdetail_id = $row['orderdetail_id'];
            //$order_id = $row['order_id'];
            //$product_id = $row['product_id'];
            //$quantity = $row['quantity'];
            // shorter way to do that is extract($row)
            if ($num > 0) {

                echo "<table class='table table-hover table-responsive table-bordered'>"; //start table
    
                //creating our table heading
                echo "<tr>";
                echo "<th>Order Detail ID </th>";
                echo "<th>Order ID</th>";
                echo "<th>Product ID</th>";
                echo "<th>Quantity</th>";
                echo "</tr>";
    
                // retrieve our table contents
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    // extract row
                    // this will make $row['firstname'] to just $firstname only
                    extract($row);
                    // creating new table row per record
                    echo "<tr>";
                    echo "<td>{$orderdetail_id}</td>";
                    echo "<td>{$order_id}</td>";
                    echo "<td>{$product_id}</td>";
                    echo "<td>{$quantity}</td>";
                    echo "</tr>";
                }
                
                echo "<tr>";
                echo "<td></td>";
                echo "<td></td>";
                echo "<td></td>";
                echo "<td> <a href='order_read.php' class='btn btn-danger'>Back to read Order</a></td>";
                echo "</tr>";
                // end table
                echo "</table>";
            } 
        }

        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>

    </div> <!-- end .container -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>