<!--ID : 2050093-BSE -->
<!--Name : Mak Hon Sang -->
<!--Topic : Order Read One Page-->
<!DOCTYPE HTML>
<html>

<head>
    <title>Order Details</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>

<body>
    <?php
    include 'config/session.php';
    include 'config/navbar.php';
    ?>
    <!-- container -->
    <div class="container">

        <?php
        if (isset($_GET['msg']) && $_GET['msg'] == 'orderCreate_success') {
            echo "<div class='alert alert-success mt-4'>Order Created Successfully.</div>";
        }
        if (isset($_GET['msg']) && $_GET['msg'] == 'orderUpdate_success') {
            echo "<div class='alert alert-success mt-4'>Order Updated Successfully.</div>";
        }
        ?>

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
            $query = "SELECT order_details.orderdetail_id, order_details.order_id, order_details.product_id, products.name, order_details.quantity, products.price
            FROM order_details 
            INNER JOIN products 
            ON order_details.product_id = products.product_id 
            WHERE order_id = :order_id";

            $stmt = $con->prepare($query);

            // Bind the parameter
            $stmt->bindParam(":order_id", $id);
            // execute our query
            $stmt->execute();
            // this is how to get number of rows returned
            $num = $stmt->rowCount();

            $query2 = "SELECT order_summary.order_id, customers.first_name, customers.last_name, customers.username, customers.email, order_summary.purchase_date
            FROM order_summary
            INNER JOIN customers
            ON order_summary.username = customers.username
            WHERE order_id=$id";

            $stmt2 = $con->prepare($query2);
            $stmt2->execute();

            // store retrieved row to a variable
            $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
            $username = $row2['username'];
            $first_name = $row2['first_name'];
            $last_name = $row2['last_name'];
            $email = $row2['email'];
            $purchase_date = $row2['purchase_date'];



            // values to fill up our form
            if ($num > 0) {

                echo "<table class='table table-bordered'>";
                echo "<tr>";
                echo "<th class='col-3'>Order ID</th>";
                echo "<td>$id</td>";
                echo "</tr>";

                echo "<tr>";
                echo "<th class='col-3'>Customer Username</th>";
                echo "<td>$username</td>";
                echo "</tr>";

                echo "<tr>";
                echo "<th class='col-3'>Customer Name</th>";
                echo "<td>" . $first_name . " " . $last_name . "</td>";
                echo "</tr>";

                echo "<tr>";
                echo "<th class='col-3'>Email</th>";
                echo "<td>$email</td>";
                echo "</tr>";

                echo "<tr>";
                echo "<th class='col-3'>Order created date</th>";
                echo "<td>$purchase_date</td>";
                echo "</tr>";
                echo "</table>";

                echo "<table class='table table-hover table-responsive table-bordered'>"; //start table
                //creating our table heading
                echo "<tr class='text-center bg-light'>";
                echo "<th>Name</th>";
                echo "<th>Quantity</th>";
                echo "<th>Unit Price</th>";
                echo "<th>Total (RM)</th>";
                echo "</tr>";

                $grand_total = 0;
                // retrieve our table contents
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    // extract row
                    // this will make $row['firstname'] to just $firstname only
                    extract($row);
                    // creating new table row per record
                    echo "<tr>";
                    echo "<td class='col-5'>{$name}</td>";
                    echo "<td class='col-3 text-center'>{$quantity}</td>";
                    echo "<td class='col text-end'>{$price}</td>";
                    $total = $price * $quantity;
                    echo "<td class='col text-end'>" . number_format($total, 2) . "</td>";
                    echo "</tr>";

                    $grand_total = $grand_total + $total;
                }
                echo "<tr class='fw-bold fs-5'>";
                echo "<td colspan='3'>Grand Total:</td>";
                echo "<td class='col text-end'>" . number_format($grand_total, 2) . "</td>";
                echo "</tr>";

                // end table
                echo "</table>";
                echo "<a href='order_read.php' class='btn btn-primary'>Back to Order list</a>";
                echo "<a href='order_update.php?id={$id}' class='btn btn-danger m-r-1em mx-3'>Edit</a>";
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