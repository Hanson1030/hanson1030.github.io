<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Create a Record - PHP CRUD Tutorial</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-md-light bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand text-light" href="home.php">Hanson1030</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active text-light" aria-current="page" href="home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-secondary" href="product_read.php">Read Product</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-secondary" href="product_create.php">Create Product</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-secondary" href="customer_read.php">Read Customer</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-secondary" href="customer_create.php">Create Customer</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-secondary" href="contact_us.php">Contact Us</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Create Product</h1>
        </div>



        <?php
        if ($_POST) {
            // include database connection
            include 'config/database.php';
            try {
                // insert query
                $query = "INSERT INTO products SET name=:name, description=:description, price=:price, promotion_price=:promo_price, manufacture_date=:manu_date, expired_date=:exp_date ,created=:created";
                // prepare query for execution
                $stmt = $con->prepare($query);
                $name = $_POST['name'];
                $description = $_POST['description'];
                $price = $_POST['price'];
                $promo_price = $_POST['promo_price'];
                $manu_date = $_POST['manu_date'];
                $exp_date = $_POST['exp_date'];
                // bind the parameters
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':price', $price);
                $stmt->bindParam(':promo_price', $promo_price);
                $stmt->bindParam(':manu_date', $manu_date);
                $stmt->bindParam(':exp_date', $exp_date);
                $created = date('Y-m-d H:i:s'); // get the current date and time
                $stmt->bindParam(':created', $created);


                //Error Statement
                $flag = 0;
                $message = "";

                if (!is_numeric($price) || !is_numeric($promo_price)) {
                    $flag = 1;
                    $message = "Price must be numerical.";
                } elseif ($price < 0 || $promo_price < 0) {
                    $flag = 1;
                    $message = "Price cannot be negative.";
                } elseif ($promo_price > $price) {
                    $flag = 1;
                    $message = "Error: Promo Price cannot bigger than Normal Price";
                } elseif ($manu_date > $exp_date) {
                    $flag = 1;
                    $message = "Error: Expired date must be after Manufacture date";
                } elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
                    if (empty($_POST["name"])) {
                        $flag = 1;
                        $message = "Please fill in every field.";
                        $nameErr = "Name is required";
                    } else {
                        $name = trim(htmlspecialchars($_POST["name"]));
                    }

                    if (empty($_POST["description"])) {
                        $flag = 1;
                        $message = "Please fill in every field.";
                        $descriptionErr = "Email is required";
                    } else {
                        $description = trim(htmlspecialchars($_POST["description"]));
                    }

                    if (empty($_POST["price"])) {
                        $flag = 1;
                        $message = "Please fill in every field.";
                        $priceErr = "Password is required";
                    } else {
                        $price = trim(htmlspecialchars($_POST["price"]));
                    }

                    if (empty($_POST["promo_price"])) {
                        $flag = 1;
                        $message = "Please fill in every field.";
                        $promo_priceErr = "Confirm Password is required";
                    } else {
                        $promo_price = trim(htmlspecialchars($_POST["promo_price"]));
                    }

                    if (empty($_POST["manu_date"])) {
                        $flag = 1;
                        $message = "Please fill in every field.";
                        $manu_dateErr = "First Name is required";
                    } else {
                        $manu_date = trim(htmlspecialchars($_POST["manu_date"]));
                    }

                    if (empty($_POST["exp_date"])) {
                        $flag = 1;
                        $message = "Please fill in every field.";
                        $exp_dateErr = "Last Name is required";
                    } else {
                        $exp_date = trim(htmlspecialchars($_POST["exp_date"]));
                    }
                }

                if ($flag == 0) {
                    if ($stmt->execute()) {
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

        <!-- html form here where the product information will be entered -->
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Name</td>
                    <td><input type='text' name='name' class='form-control' />
                        <span>
                            <?php if (isset($nameErr)) echo "<div class='text-danger'>*$nameErr</div>  "; ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td><textarea name='description' class='form-control'></textarea>
                        <span>
                            <?php if (isset($descriptionErr)) echo "<div class='text-danger'>*$descriptionErr</div>  "; ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Price</td>
                    <td><input type='text' name='price' class='form-control' />
                        <span>
                            <?php if (isset($priceErr)) echo "<div class='text-danger'>*$priceErr</div>  "; ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Promotion Price</td>
                    <td><input type='text' name='promo_price' class='form-control' />
                        <span>
                            <?php if (isset($promo_priceErr)) echo "<div class='text-danger'>*$promo_priceErr</div>  "; ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Manufacture Date</td>
                    <td><input type='date' name='manu_date' class='form-control' />
                        <span>
                            <?php if (isset($manu_dateErr)) echo "<div class='text-danger'>*$manu_dateErr</div>  "; ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Expired Date</td>
                    <td><input type="date" name='exp_date' class='form-control' />
                        <span>
                            <?php if (isset($exp_dateErr)) echo "<div class='text-danger'>*$exp_dateErr</div>  "; ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save' class='btn btn-primary' />
                        <a href="product_read.php" class='btn btn-danger'>Back to read products</a>
                    </td>
                </tr>
            </table>
        </form>

    </div>
    <!-- end .container -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>