<!--ID : 2050093-BSE -->
<!--Name : Mak Hon Sang -->
<!--Topic : Product Read One Page-->
<!DOCTYPE HTML>
<html>

<head>
    <title>Product Details</title>
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
        if (isset($_GET['msg']) && $_GET['msg'] == 'prod_updateSuccess') {
            echo "<div class='alert alert-success mt-4'>Product details has updated successfully.</div>";
        }
        if (isset($_GET['msg']) && $_GET['msg'] == 'prod_createSuccess') {
            echo "<div class='alert alert-success mt-4'>Product has created successfully.</div>";
        }
        ?>
        
        <div class="page-header">
            <h1>Read Product</h1>
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
            $query = "SELECT products.product_id, products.name, products.description, products.product_img, products.price, products.promotion_price, products.manufacture_date, products.expired_date, categories.category_name 
            FROM products 
            INNER JOIN categories 
            ON products.category_id=categories.category_id
            WHERE product_id = :product_id ";
            $stmt = $con->prepare($query);

            // Bind the parameter
            $stmt->bindParam(":product_id", $id);

            // execute our query
            $stmt->execute();

            // store retrieved row to a variable
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // values to fill up our form

            $product_img = $row['product_img'];
            $name = $row['name'];
            $description = $row['description'];
            $price = $row['price'];
            $promo_price = $row['promotion_price'] == 0 ? 'No Promotion Price' : $row['promotion_price'];
            $manu_date = $row['manufacture_date'];
            $exp_date = $row['expired_date'] == '0000-00-00' ? 'This Product has no expired date' : $row['expired_date'];
            $product_category = $row['category_name'];

            // shorter way to do that is extract($row)
        }

        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>

        <!--we have our html table here where the record will be displayed-->
        <table class='table table-hover table-responsive table-bordered'>
            <tr>
                <td>Product Image</td>

                <?php
                if ($product_img == '') {
                    echo '<td><img src="prod_img/nopic.png" width="200px"></td>';
                } else {
                    echo '<td><img src="prod_img/' . $product_img . '"width="200px"></td>';
                }

                ?>
            </tr>
            <tr>
                <td>Name</td>
                <td><?php echo htmlspecialchars($name, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Description</td>
                <td><?php echo htmlspecialchars($description, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Category</td>
                <td><?php echo htmlspecialchars($product_category, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Price</td>
                <td><?php echo htmlspecialchars($price, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Promo Price</td>
                <td><?php echo htmlspecialchars($promo_price, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Manufacture Date</td>
                <td><?php echo htmlspecialchars($manu_date, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Expired Date</td>
                <td><?php echo htmlspecialchars($exp_date, ENT_QUOTES);  ?></td>
            </tr>

            <tr>
                <td></td>
                <td>
                    <?php
                    echo "<a href='product_read.php' class='btn btn-primary me-3'>Back to Product Read</a>";
                    echo "<a href='product_update.php?id=$id' class='btn btn-danger'>Edit product</a>";
                    ?>
                </td>
            </tr>
        </table>


    </div> <!-- end .container -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>