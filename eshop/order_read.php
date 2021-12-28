<?php
include 'config/navbar.php';
include 'config/session.php';
?>
    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Read Order</h1>
        </div>

        <?php
        // include database connection
        include 'config/database.php';

        // delete message prompt will be here

        // select all data
        $query = "SELECT order_summary.order_id, order_summary.username, customers.email, order_summary.purchase_date FROM order_summary INNER JOIN customers ON order_summary.username=customers.username ORDER BY order_id";
        $stmt = $con->prepare($query);
        $stmt->execute();

        // this is how to get number of rows returned
        $num = $stmt->rowCount();

        // link to create record form
        //echo "<a href='customer_create.php' class='btn btn-primary m-b-1em'>Create New Order</a>";

        //check if more than 0 record found
        if ($num > 0) {

            echo "<table class='table table-hover table-responsive table-bordered'>"; //start table

            //creating our table heading
            echo "<tr>";
            echo "<th>Order ID </th>";
            echo "<th>Customer Username</th>";
            echo "<th>Customer Email</th>";
            echo "<th>Purchase Date</th>";
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
                echo "<td>";
                // read one record
                echo "<a href='order_read_one.php?id={$order_id}' class='btn btn-info m-r-1em'>Read</a>";

                // we will use this links on next part of this post
                echo "<a href='order_update.php?id={$order_id}' class='btn btn-primary m-r-1em'>Edit</a>";

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

    <!-- confirm delete record will be here -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>