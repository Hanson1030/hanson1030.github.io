<!--ID : 2050093-BSE -->
<!--Name : Mak Hon Sang -->
<!--Topic : Category Create Page-->
<!DOCTYPE HTML>
<html>

<head>
    <title>Create Category</title>
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
            <h1>Create Category</h1>
        </div>


        <?php
        if ($_POST) {
            // include database connection
            include 'config/database.php';
            try {
                // insert query
                $query = "INSERT INTO categories SET category_id=:category_id, category_name=:category_name, description=:description";
                // prepare query for execution
                $stmt = $con->prepare($query);
                $cat_name = $_POST['cat_name'];
                $cat_description = $_POST['cat_description'];

                // bind the parameters
                $stmt->bindParam(':category_id', $category_id);
                $stmt->bindParam(':category_name', $cat_name);
                $stmt->bindParam(':description', $cat_description);

               

                // Execute the query

                $flag = 0;
                $message = '';

                if (empty($cat_name)) {
                    $flag = 1;
                    $message .= 'Please enter category name.';
                }

                if (empty($cat_description)) {
                    $flag = 1;
                    $message .= 'Please enter description.';
                }

                if ($flag == 0) {

                    if ($stmt->execute()) {
                        $category_id = $con->lastInsertId();
                        echo "<script>location.replace('category_read_one.php?id=".$category_id."&msg=cat_createSuccess')</script>";
                        echo "<div class='alert alert-success'>Record was saved.</div>";
                    } else {
                        $message = 'Unable to save record.';
                    }
                } else {

                    echo "<div class='alert alert-danger'>" . $message . "</div>";
                }
            }
            // show error
            catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        }

        ?>

        <!-- html form here where the product information will be entered -->
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Category Name</td>
                    <td><input type='text' name='cat_name' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td><textarea type='text' name='cat_description' class='form-control'></textarea></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save' class='btn btn-primary' />
                        <a href='category_read.php' class='btn btn-danger'>Back to read category</a>
                    </td>
                </tr>
            </table>
        </form>

    </div>
    <!-- end .container -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>