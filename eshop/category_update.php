<!--ID : 2050093-BSE -->
<!--Name : Mak Hon Sang -->
<!--Topic : Category Update Page-->
<!DOCTYPE HTML>
<html>

<head>
    <title>Update Category</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>

<body>

<?php
include 'config/session.php';
include 'config/navbar.php';
?>

<div class="container">
    <div class="page-header">
        <h1>Update Category</h1>
    </div>
    
    <!-- PHP read record by ID will be here →
        <?php
        // get passed parameter value, in this case, the record ID
        // isset() is a PHP function used to verify if a value is there or not
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');
        //include database connection
        include 'config/database.php';
        // read current record's data
        try {
            // prepare select query
            $query = "SELECT * FROM categories WHERE category_id = ? LIMIT 0,1";
            $stmt = $con->prepare($query);
            // this is the first question mark
            $stmt->bindParam(1, $id);
            // execute our query
            $stmt->execute();
            // store retrieved row to a variable
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            // values to fill up our form
            $cat_name = $row['category_name'];
            $cat_description = $row['description'];
        }
        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>
         HTML form to update record will be here -->
    <!-- PHP post to update record will be here -->
    <?php
    // check if form was submitted
    if ($_POST) {
        try {
            // write update query
            // in this case, it seemed like we have so many fields to pass and
            // it is better to label them and not use question marks

            $flag = 0;
            $message = '';

            if (empty($cat_name)) {
                $flag = 1;
                $message = 'Please enter category name.';
            }

            if (empty($cat_description)) {
                $flag = 1;
                $message = 'Please enter category name.';
            }

            if ($flag == 0) {
                $query = "UPDATE categories
                  SET category_name=:category_name, description=:description WHERE category_id = :category_id";
                // prepare query for excecution
                $stmt = $con->prepare($query);
                // posted values
                $cat_name = htmlspecialchars(strip_tags($_POST['name']));
                $cat_description = htmlspecialchars(strip_tags($_POST['description']));
                // bind the parameters
                $stmt->bindParam(':category_name', $cat_name);
                $stmt->bindParam(':description', $cat_description);
                $stmt->bindParam(':category_id', $id);
                // Execute the query

                if ($stmt->execute()) {
                    echo "<script>location.replace('category_read_one.php?id=".$id."&msg=cat_updateSuccess')</script>";
                    echo "<div class='alert alert-success'>Record was saved.</div>";
                } else {
                    $message = 'Unable to save record.';
                }
            } else {

                echo "<div class='alert alert-danger'>" . $message . "</div>";
            }
        }
        // show errors
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
    } ?>



    <!--we have our html form here where new record information can be updated-->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$id}"); ?>" method="post">
        <table class='table table-hover table-responsive table-bordered'>
            <tr>
                <td>Name</td>
                <td><input type='text' name='name' value="<?php echo htmlspecialchars($cat_name, ENT_QUOTES);  ?>" class='form-control' /></td>
            </tr>
            <tr>
                <td>Description</td>
                <td><textarea name='description' class='form-control'><?php echo htmlspecialchars($cat_description, ENT_QUOTES);  ?></textarea></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input type='submit' value='Save Changes' class='btn btn-primary' />
                    <a href='category_read.php' class='btn btn-danger'>Back to Category List</a>
                </td>
            </tr>
        </table>
    </form>


</div>
<!-- end .container -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>

</body>

</html>