<!--ID : 2050093-BSE -->
<!--Name : Mak Hon Sang -->
<!--Topic : Customer Read Page-->
<!DOCTYPE HTML>
<html>

<head>
    <title>Customer List</title>
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
        <h1>Customers List</h1>
    </div>

    <?php
    // include database connection
    include 'config/database.php';

    $action = isset($_GET['action']) ? $_GET['action'] : "";

    if ($action == 'deleted') {
        echo "<div class='alert alert-success'>Customer was deleted.</div>";
    }else if($action == 'delErr'){
        echo "<div class='alert alert-danger'>Unable to delete customer that have order.</div>";
    }

    // select all data
    $query = "SELECT username, email, first_name, last_name, registration_date, customer_img FROM customers ORDER BY registration_date DESC";
    $stmt = $con->prepare($query);
    $stmt->execute();

    // this is how to get number of rows returned
    $num = $stmt->rowCount();

    echo "<div class='text-center'>";
    echo "<a href='customer_create.php' class='btn btn-primary m-b-1em my-3'>Create New Customer</a>";
    echo "</div>";

    //check if more than 0 record found
    if ($num > 0) {

        echo "<table class='table table-hover table-responsive table-bordered'>"; //start table

        //creating our table heading
        echo "<tr class='text-center'>";
        echo "<th>Profile Image</th>";
        echo "<th>Username </th>";
        echo "<th>Email</th>";
        echo "<th>First Name</th>";
        echo "<th>Last Name</th>";
        echo "<th>Registration Date & Time</th>";
        echo "<th>Action</th>";
        echo "</tr>";

        // retrieve our table contents
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // extract row
            // this will make $row['firstname'] to just $firstname only
            extract($row);
            // creating new table row per record
            echo "<tr class='text-center'>";

            if ($customer_img == '') {
                echo "<td><img src='cus_img/noimg.png' style='object-fit: cover;height:100px;width:100px;'><br>";
            } else {
                echo "<td><img src='cus_img/" . $customer_img . "'style='object-fit: cover;height:100px;width:100px;'></td>";
            }

            echo "<td>{$username}</td>";
            echo "<td>{$email}</td>";
            echo "<td>{$first_name}</td>";
            echo "<td>{$last_name}</td>";
            echo "<td>{$registration_date}</td>";
            echo "<td>";
            // read one record
            echo "<a href='customer_read_one.php?id={$username}' class='btn btn-info m-r-1em'>Read</a>";

            // we will use this links on next part of this post
            echo "<a href='customer_update.php?id={$username}' class='btn btn-primary m-r-1em mx-2 my-2'>Edit</a>";

            // we will use this links on next part of this post
            echo "<a href='#' onclick='delete_customer(\"{$username}\");'  class='btn btn-danger'>Delete</a>";
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
        function delete_customer(username) {

            if (confirm('Are you sure?')) {
                // if user clicked ok,
                // pass the id to delete.php and execute the delete query
                window.location = 'customer_delete.php?id=' + username;
            }
        }
    </script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>