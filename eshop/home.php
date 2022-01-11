<!--ID : 2050093-BSE -->
<!--Name : Mak Hon Sang -->
<!--Topic : Home Page-->
<!DOCTYPE HTML>
<html>

<head>
    <title>Home Page</title>
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
    include 'config/database.php';
    if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
        $query = 'SELECT first_name, last_name, gender from customers WHERE email= ?';
    } else {
        $query = 'SELECT first_name, last_name, gender FROM customers WHERE username=?';
    }

    $stmt = $con->prepare($query);
    $stmt->bindParam(1, $_SESSION['username']);
    $stmt->execute();
    $customer_list = $stmt->rowCount();

    $gender_option = '';

    if ($customer_list > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $first_name = $row['first_name'];
        $last_name = $row['last_name'];
        $gender = $row['gender'];
        
        if ($gender == 'Male') {
            $gender_option = "Mr. $first_name $last_name";
        } else {
            $gender_option = "Mrs. $first_name $last_name";
        }
    }

    $query_totalOrder = 'SELECT * FROM order_summary';
    $stmt_totalOrder = $con->prepare($query_totalOrder);
    $stmt_totalOrder->execute();
    $totalOrder = $stmt_totalOrder->rowCount();

    $query_totalCustomer = 'SELECT * FROM customers';
    $stmt_totalCustomer = $con->prepare($query_totalCustomer);
    $stmt_totalCustomer->execute();
    $totalCustomer = $stmt_totalCustomer->rowCount();

    $query_lastOrder = 'SELECT * FROM order_summary ORDER BY order_id DESC LIMIT 1';
    $stmt_lastOrder = $con->prepare($query_lastOrder);
    $stmt_lastOrder->execute();
    $lastOrder = $stmt_lastOrder->rowCount();

    ?>
    <div class="page-header">
        <h1 class="d-flex justify-content-center mt-5 fw-bold">
            Welcome! <?php echo $gender_option; ?>
        </h1>
    </div>

    <div class="d-flex justify-content-center text-center">
        <div class="w-50 me-3">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td class='bg-light fw-bold fs-5'>Total Order</td>
                </tr>
                <tr>
                    <td><?php echo $totalOrder ?></td>
                </tr>

            </table>
        </div>
        <div class="w-50 ms-3">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td class='bg-light fw-bold fs-5'>Total Customer</td>
                </tr>
                <tr>
                    <td><?php echo $totalCustomer ?></td>
                </tr>
            </table>
        </div>
    </div>

    <div class="mt-5">
        <h2 class="text-center fw-bold">Latest Order</h2>
        <?php
        if ($lastOrder > 0) {
            while ($row_lastOrder = $stmt_lastOrder->fetch(PDO::FETCH_ASSOC)) {
                extract($row_lastOrder);
                $order_id = $row_lastOrder['order_id'];
                $purchase_date = $row_lastOrder['purchase_date'];

                echo "<table class='table table-hover table-responsive table-bordered text-center'>";
                echo "<tr class='bg-light fw-bold fs-5'>";
                echo "<th class='col-6'>Order ID</th>";
                echo "<th class='col-6'>Order Create Date</th>";
                echo "</tr>";

                echo "<tr>";
                echo "<td>$order_id</td>";
                echo "<td>$purchase_date</td>";
                echo "</tr>";
                echo "</table>";
            }
        }

        ?>
        
    </div>
</div>
<!-- end .container -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>