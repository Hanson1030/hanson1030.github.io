<!--ID : 2050093-BSE -->
<!--Name : Mak Hon Sang -->
<!--Topic : Category Read Page-->
<!DOCTYPE HTML>
<html>

<head>
    <title>Category List</title>
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
        <h1>Category List</h1>
    </div>

    <?php
    // include database connection
    include 'config/database.php';

    // delete message prompt will be here
    $action = isset($_GET['action']) ? $_GET['action'] : "";

    // if it was redirected from delete.php
    if ($action == 'deleted') {
        echo "<div class='alert alert-success'>Category was deleted.</div>";
    } else if ($action == 'delErr') {
        echo "<div class='alert alert-danger'>Unable to delete category with product assigned.</div>";
    }

    // select all data
    $query = "SELECT * FROM categories ORDER BY category_id ";
    $stmt = $con->prepare($query);
    $stmt->execute();

    // this is how to get number of rows returned
    $num = $stmt->rowCount();


    // link to create record form
    echo "<div class='text-center'>";
    echo "<a href='category_create.php' class='btn btn-primary m-b-1em my-3'>Create New Category</a>";
    echo "</div>";

    //check if more than 0 record found
    if ($num > 0) {

        echo "<table class='table table-hover table-responsive table-bordered text-center'>"; //start table

        //creating our table heading
        echo "<tr>";
        echo "<th>ID</th>";
        echo "<th>Category Name</th>";
        echo "<th>Description</th>";
        echo "<th>Action</th>";
        echo "</tr>";

        // retrieve our table contents
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // extract row
            // this will make $row['firstname'] to just $firstname only
            extract($row);
            // creating new table row per record
            echo "<tr>";
            echo "<td>{$category_id}</td>";
            echo "<td>{$category_name}</td>";
            echo "<td>{$description}</td>";
            echo "<td>";
            // read one record
            echo "<a href='category_read_one.php?id={$category_id}' class='btn btn-info m-r-1em'>Read</a>";

            // we will use this links on next part of this post
            echo "<a href='category_update.php?id={$category_id}' class='btn btn-primary m-r-1em mx-2 my-2'>Edit</a>";

            // we will use this links on next part of this post
            echo "<a href='#' onclick='delete_category({$category_id});'  class='btn btn-danger'>Delete</a>";
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
<script type='text/javascript'>
    // confirm record deletion
    function delete_category(category_id) {

        if (confirm('Are you sure?')) {
            // if user clicked ok,
            // pass the id to delete.php and execute the delete query
            window.location = 'category_delete.php?id=' + category_id;
        }
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>