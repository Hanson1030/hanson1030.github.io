<!--ID : 2050093-BSE -->
<!--Name : Mak Hon Sang -->
<!--Topic : Product Create Page-->
<!DOCTYPE HTML>
<html>

<head>
    <title>Create New Product</title>
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
        <h1>Create Product</h1>
    </div>




    <?php
    include 'config/database.php';

    $query_category = "SELECT * FROM categories ORDER BY category_id";
    $stmt_category = $con->prepare($query_category);
    $stmt_category->execute();

    $query_product = "SELECT * FROM products";
    $stmt_product = $con->prepare($query_product);
    $stmt_product->execute();

    if ($_POST) {
        // include database connection

        try {
            // insert query
            $query = "INSERT INTO products SET product_id=:product_id, name=:name, description=:description, category_id=:category_id, price=:price, promotion_price=:promo_price, manufacture_date=:manu_date, expired_date=:exp_date, product_img=:product_img, created=:created";
            // prepare query for execution
            $stmt = $con->prepare($query);
            $name = $_POST['name'];
            $description = $_POST['description'];
            $category_id = $_POST['category_id'];
            $price = $_POST['price'];
            $promo_price = $_POST['promo_price'];
            $manu_date = $_POST['manu_date'];
            $exp_date = $_POST['exp_date'];
            $product_img = basename($_FILES['prod_img']['name']);
            // bind the parameters
            $stmt->bindParam(':product_id', $product_id);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':category_id', $category_id);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':promo_price', $promo_price);
            $stmt->bindParam(':manu_date', $manu_date);
            $stmt->bindParam(':exp_date', $exp_date);
            $stmt->bindParam(':product_img', $product_img);
            $created = date('Y-m-d H:i:s'); // get the current date and time
            $stmt->bindParam(':created', $created);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            //Error Statement
            $flag = 0;
            $message = "";

            if (!empty($_FILES['prod_img']['name'])) {
                $target_dir = "prod_img/".$row['product_id'];
                $target_file = $target_dir . basename($_FILES["prod_img"]["name"]);
                $isUploadOK = TRUE;
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                $check = getimagesize($_FILES["prod_img"]["tmp_name"]);
                if ($check !== false) {
                    $isUploadOK = TRUE;
                } else {
                    $flag = 1;
                    $message .= "File is not an image.<br>";
                    $isUploadOK = FALSE;
                }


                if ($_FILES["prod_img"]["size"] > 5000000) {
                    $flag = 1;
                    $message .= "Sorry, your file is too large.<br>";
                    $isUploadOK = FALSE;
                }
                // Allow certain file formats
                if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                    $flag = 1;
                    $message .= "Sorry, only JPG, JPEG, PNG & GIF files are allowed.<br>";
                    $isUploadOK = FALSE;
                }
                // Check if $uploadOk is set to 0 by an error
                if ($isUploadOK == FALSE) {
                    $flag = 1;
                    $message .= "Sorry, your file was not uploaded."; // if everything is ok, try to upload file
                } else {
                    if (move_uploaded_file($_FILES["prod_img"]["tmp_name"], $target_file)) {
                        echo "";
                    } else {
                        $flag = 1;
                        $message .= "Sorry, there was an error uploading your file.<br>";
                    }
                }
            } else {

                $product_img = '';
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                if (empty($_POST["name"])) {
                    $flag = 1;
                    $message = "Please fill in every field.";
                    $nameErr = "Name is required";
                }

                if (empty($_POST["description"])) {
                    $flag = 1;
                    $message = "Please fill in every field.";
                    $descriptionErr = "Description is required";
                }

                if ($_POST["category_id"] == '') {
                    $flag = 1;
                    $message = "Please fill in every field.";
                    $categoryErr = "Category is required";
                }

                if (empty($_POST["price"])) {
                    $flag = 1;
                    $message = "Please fill in every field.";
                    $priceErr = "Price is required";
                }

                if (empty($_POST["manu_date"])) {
                    $flag = 1;
                    $message = "Please fill in every field.";
                    $manu_dateErr = "Manufacture date is required";
                }

                if (empty($_POST["promo_price"])) {
                    $promo_price = 0;
                }

                if (empty($_POST["exp_date"])) {
                    $exp_date = '0000-00-00';
                }
            }

            if (!is_numeric($price)) {
                $flag = 1;
                $message = "Price must be numerical1.";
            } elseif (!empty($promo_price)) {
                if (!is_numeric($promo_price)) {
                    $flag = 1;
                    $message = "Price must be numerical.";
                }
            } elseif (!empty($promo_price)) {
                if ($price < 0 || $promo_price < 0) {
                    $flag = 1;
                    $message = "Price cannot be negative.";
                }
            } elseif ($promo_price >= $price) {
                $flag = 1;
                $message = "Error: Promo Price must lesser than Normal Price";
            } elseif ($manu_date == '0000-00-00' ) {
                if ($manu_date >= $exp_date) {
                    $flag = 1;
                    $message = "Error: Expired date must be after Manufacture date";
                }
            }

            if ($flag == 0) {
                if ($stmt->execute()) {
                    $product_id = $con->lastInsertId();
                    echo "<script>location.replace('product_read_one.php?id=".$product_id."&msg=prod_createSuccess')</script>";
                    echo "<div class='alert alert-success'>Record was saved.</div>";
                } else {
                    echo "Unable to save record.";
                }
            } else {
                echo "<div class='alert alert-danger'>";
                echo $message;
                echo "</div>";
            }
        }
        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
    }


    ?>

    <?php $posted_name = $_POST ? $_POST['name'] : ''; ?>

    <!-- html form here where the product information will be entered -->
    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" enctype="multipart/form-data">
        <table class='table table-hover table-responsive table-bordered'>
            <tr>
                <td>Product Image</td>
                <td> <input type="file" name="prod_img" id="fileToUpload" />
                </td>
            </tr>
            <tr>
                <td>Name<span class="text-danger">*</span></td>
                <td>
                    <input type='text' name='name' class='form-control' value="<?php echo $posted_name ?>" />
                    <span>
                        <?php if (isset($nameErr)) echo "<div class='text-danger'>*$nameErr</div>  "; ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td>Description<span class="text-danger">*</span></td>
                <td>
                    <textarea name='description' class='form-control'><?php echo $_POST ? $_POST['description'] : ''; ?></textarea>
                    <span>
                        <?php if (isset($descriptionErr)) echo "<div class='text-danger'>*$descriptionErr</div>  "; ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td>Category<span class="text-danger">*</span></td>
                <td>
                    <select class="fs-3 rounded col-4" name="category_id">
                        <option value=" " class="text-center">--SELECT Category--</option>

                        <?php
                        $category_list = $_POST ? $_POST['category_id'] : ' ';
                        while ($row = $stmt_category->fetch(PDO::FETCH_ASSOC)) {
                            extract($row);
                            $selected_category = $row['category_id'] == $category_list ? 'selected' : '';
                            echo "<option class='bg-white' value='$category_id' $selected_category>$category_name</option>";
                        }
                        ?>

                    </select>
                    <span>
                        <?php if (isset($categoryErr)) echo "<div class='text-danger'>*$categoryErr</div>  "; ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td>Price<span class="text-danger">*</span></td>
                <td>
                    <input type='text' name='price' class='form-control' value="<?php echo $_POST ? $_POST['price'] : ''; ?>" />
                    <span>
                        <?php if (isset($priceErr)) echo "<div class='text-danger'>*$priceErr</div>  "; ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td>Promotion Price</td>
                <td>
                    <input type='text' name='promo_price' class='form-control' value="<?php echo $_POST ? $_POST['promo_price'] : ''; ?>" />
                </td>
            </tr>
            <tr>
                <td>Manufacture Date<span class="text-danger">*</span></td>
                <td><input type='date' name='manu_date' class='form-control' value="<?php echo $_POST ? $_POST['manu_date'] : ''; ?>" />
                    <span>
                        <?php if (isset($manu_dateErr)) echo "<div class='text-danger'>*$manu_dateErr</div>  "; ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td>Expired Date</td>
                <td>
                    <input type="date" name='exp_date' class='form-control' value="<?php echo $_POST ? $_POST['exp_date'] : ''; ?>" />
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input type='submit' value='Save' class='btn btn-primary' />
                    <a href="product_read.php" class='btn btn-danger'>Back to Product List</a>
                </td>
            </tr>
        </table>
    </form>

</div>
<!-- end .container -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>