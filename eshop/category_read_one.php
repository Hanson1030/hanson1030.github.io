<!--ID : 2050093-BSE -->
<!--Name : Mak Hon Sang -->
<!--Topic : Category Read One Page-->
<!DOCTYPE HTML>
<html>

<head>
    <title>Category Details</title>
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
        if (isset($_GET['msg']) && $_GET['msg'] == 'cat_updateSuccess') {
            echo "<div class='alert alert-success mt-4'>Category detial has been updated.</div>";
        }
        if (isset($_GET['msg']) && $_GET['msg'] == 'cat_createSuccess') {
            echo "<div class='alert alert-success mt-4'>New Category has been created.</div>";
        }
        ?>

        <div class="page-header">
            <h1>Read Category</h1>
        </div>

        <!-- PHP read one record will be here -->
        <?php
        // get passed parameter value, in this case, the record ID
        // isset() is a PHP function used to verify if a value is there or not
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');

        //include database connection
        include 'config/database.php';

        // read current record's data
        try {
            // prepare select query
            $query = "SELECT * FROM categories WHERE category_id = :category_id ";
            $stmt = $con->prepare($query);

            // Bind the parameter
            $stmt->bindParam(":category_id", $id);

            // execute our query
            $stmt->execute();

            // store retrieved row to a variable
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // values to fill up our form
            $cat_name = $row['category_name'];
            $cat_description = $row['description'];
            // shorter way to do that is extract($row)
        }

        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>

        <!-- HTML read one record table will be here -->
        <!--we have our html table here where the record will be displayed-->
        <table class='table table-hover table-responsive table-bordered'>
            <tr>
                <td>Category Name</td>
                <td><?php echo htmlspecialchars($cat_name, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Description</td>
                <td><?php echo htmlspecialchars($cat_description, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <a href='category_read.php' class='btn btn-danger'>Back to read Category</a>
                    <?php echo "<a href='category_update.php?id={$id}' class='btn btn-primary m-r-1em'>Edit</a>"; ?>
                </td>
            </tr>
        </table>



    </div> <!-- end .container -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>


</body>

</html>