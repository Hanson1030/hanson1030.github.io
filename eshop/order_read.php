<!--ID : 2050093-BSE -->
<!--Name : Mak Hon Sang -->
<!--Topic : Order Read Page-->
<!DOCTYPE HTML>
<html>

<head>
    <title>Order List</title>
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
    <div class="page-header">
        <h1>Order List</h1>
    </div>

    <?php
    // include database connection
    include 'config/database.php';

    $action = isset($_GET['action']) ? $_GET['action'] : "";

    // if it was redirected from delete.php
    if ($action == 'deleted') {
        echo "<div class='alert alert-success'>Record was deleted.</div>";
    }

    // select all data
    $query = "SELECT order_summary.order_id, order_summary.username, customers.email, order_summary.purchase_date FROM order_summary INNER JOIN customers ON order_summary.username=customers.username ORDER BY order_id DESC";
    $stmt = $con->prepare($query);
    $stmt->execute();

    // this is how to get number of rows returned
    $num = $stmt->rowCount();

    // link to create record form
    echo "<div class='text-center'>";
    echo "<a href='order_create.php' class='btn btn-primary m-b-1em my-3'>Create New Order</a>";
    echo "</div>";

    //check if more than 0 record found
    if ($num > 0) {

        echo "<table class='table table-hover table-responsive table-bordered text-center'>"; //start table

        //creating our table heading
        echo "<tr>";
        echo "<th>Order ID </th>";
        echo "<th>Customer Username</th>";
        echo "<th>Customer Email</th>";
        echo "<th>Order created Date</th>";
        echo "<th>Action</th>";
        echo "</tr>";

        // retrieve our table contents
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // extract row
            // this will make $row['firstname'] to just $firstname only
            extract($row);
            // creating new table row per record
            echo "<tr>";
            echo "<td>{$order_id}</td>";
            echo "<td>{$username}</td>";
            echo "<td>{$email}</td>";
            echo "<td>{$purchase_date}</td>";
            echo "<td class='text-center'>";
            // read one record
            echo "<a href='order_read_one.php?id={$order_id}' class='btn btn-info m-r-1em'>Read</a>";

            // we will use this links on next part of this post
            echo "<a href='order_update.php?id={$order_id}' class='btn btn-primary m-r-1em mx-3'>Edit</a>";

            // we will use this links on next part of this post
            echo "<a href='#' onclick='delete_order({$order_id});'  class='btn btn-danger'>Delete</a>";
            echo "</td>";
            echo "</tr>";
        }



        // end table
        echo "</table>";
    } else {
        echo "<div class='alert alert-danger'>No records found.</div>";
    }
    ?>

</div> <!-- end .container -->

<script type='text/javascript'>
// confirm record deletion
function delete_order(id){
     
    if (confirm('Are you sure?')){
        // if user clicked ok,
        // pass the id to delete.php and execute the delete query
        window.location = 'order_delete.php?id=' + id;
    }
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>