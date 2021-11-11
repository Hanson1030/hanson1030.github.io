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
                <span class="navbar-text">
                    <a class="nav-link text-secondary" href="order_create.php">Create Order</a>
                </span>
            </div>
        </div>
    </nav>
    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Create Customer</h1>
        </div>

        <?php
        if ($_POST) {
            // include database connection
            include 'config/database.php';
            try {
                // insert query
                $query = "INSERT INTO customers SET username=:username, email=:email, password=:password, confirm_password=:confirm_password, first_name=:first_name, last_name=:last_name, gender=:gender, date_of_birth=:date_of_birth";
                // prepare query for execution
                $stmt = $con->prepare($query);
                $username = $_POST['username'];
                $email = $_POST['email'];
                $password = $_POST['password'];
                $confirm_password = $_POST['confirm_password'];
                $first_name = $_POST['first_name'];
                $last_name = $_POST['last_name'];
                $gender = $_POST['gender'];
                $date_of_birth = $_POST['date_of_birth'];
                //$reg_date = $_POST['reg_date'];
                //$acc_status = $_POST['acc_status'];
                // bind the parameters
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $password);
                $stmt->bindParam(':confirm_password', $confirm_password);
                $stmt->bindParam(':first_name', $first_name);
                $stmt->bindParam(':last_name', $last_name);
                $stmt->bindParam(':gender', $gender);
                $stmt->bindParam(':date_of_birth', $date_of_birth);
                //$reg_date = date('Y-m-d H:i:s'); // get the current date and time
                //$stmt->bindParam(':reg_date', $reg_date);
                //$stmt->bindParam(':acc_status', $acc_status);
                //$created = date('Y-m-d H:i:s'); // get the current date and time
                //$stmt->bindParam(':created', $created);

                // Execute the query
                // echo $password . "\n";
                // $check = !preg_match( "/[a-z]/", $password) && !preg_match( "/[A-Z]/", $password) || !preg_match( "/[0-9]/", $password);
                // echo $check;

                $flag = 0;
                $message = "";
                $cur_date = date('Y');
                $cust_age = ((int)$cur_date - (int)$date_of_birth);

                if (!preg_match("/[a-zA-Z]/", $password) || !preg_match("/[0-9]/", $password) || !preg_match("/[a-zA-Z0-9]{8,}/", $password)) {
                    $flag = 1;
                    $message = "Password must at least 8 character and must contain number and alphabets.";
                } elseif ($password !== $confirm_password) {
                    $flag = 1;
                    $message = "Please make sure Password and Confirm Password are same.";
                } elseif ($cust_age < 18) {
                    $flag = 1;
                    $message = "Customer must be age of 18 or above.";
                } elseif (!preg_match("/[a-zA-Z0-9]{6,}/", $username)) {
                    $flag = 1;
                    $message = "Username must be at least 6 characters";
                } elseif ($_SERVER["REQUEST_METHOD"] == "POST") {

                    if (empty($_POST["username"])) {
                        $flag = 1;
                        $message = "Please fill in every field.";
                        $usernameErr = "Name is required";
                    } else {
                        $username = trim(htmlspecialchars($_POST["username"]));
                    }

                    if (empty($_POST["email"])) {
                        $flag = 1;
                        $message = "Please fill in every field.";
                        $emailErr = "Email is required";
                    } else {
                        $email = trim(htmlspecialchars($_POST["email"]));
                    }

                    if (empty($_POST["password"])) {
                        $flag = 1;
                        $message = "Please fill in every field.";
                        $passwordErr = "Password is required";
                    } else {
                        $password = trim(htmlspecialchars($_POST["password"]));
                    }

                    if (empty($_POST["confirm_password"])) {
                        $flag = 1;
                        $message = "Please fill in every field.";
                        $confirm_passwordErr = "Confirm Password is required";
                    } else {
                        $confirm_password = trim(htmlspecialchars($_POST["confirm_password"]));
                    }

                    if (empty($_POST["first_name"])) {
                        $flag = 1;
                        $message = "Please fill in every field.";
                        $first_nameErr = "First Name is required";
                    } else {
                        $first_name = trim(htmlspecialchars($_POST["first_name"]));
                    }

                    if (empty($_POST["last_name"])) {
                        $flag = 1;
                        $message = "Please fill in every field.";
                        $last_nameErr = "Last Name is required";
                    } else {
                        $last_name = trim(htmlspecialchars($_POST["last_name"]));
                    }

                    if (empty($_POST["gender"])) {
                        $flag = 1;
                        $message = "Please fill in every field.";
                        $genderErr = "Gender is required";
                    } else {
                        $gender = trim(htmlspecialchars($_POST["gender"]));
                    }

                    if (empty($_POST["date_of_birth"])) {
                        $flag = 1;
                        $message = "Please fill in every field.";
                        $date_of_birthErr = "Date of Birth is required";
                    } else {
                        $date_of_birth = trim(htmlspecialchars($_POST["date_of_birth"]));
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

        <!-- html form here where the customer information will be entered -->
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Username</td>
                    <td><input type='text' name='username' class='form-control' />
                        <span>
                            <?php if (isset($usernameErr)) echo "<div class='text-danger'>*$usernameErr</div>  "; ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><input type='email' name='email' class='form-control' />
                        <span>
                            <?php if (isset($emailErr)) echo "<div class='text-danger'>*$emailErr</div>  "; ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><input type="password" name='password' class='form-control' />
                        <span>
                            <?php if (isset($passwordErr)) echo "<div class='text-danger'>*$passwordErr</div>  "; ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Confirm Password</td>
                    <td><input type="password" name='confirm_password' class='form-control' />
                        <span>
                            <?php if (isset($confirm_passwordErr)) echo "<div class='text-danger'>*$confirm_passwordErr</div>  "; ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>First Name</td>
                    <td><input type="text" name='first_name' class='form-control' />
                        <span>
                            <?php if (isset($first_nameErr)) echo "<div class='text-danger'>*$first_nameErr</div>  "; ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Last Name</td>
                    <td><input type="text" name='last_name' class='form-control' />
                        <span>
                            <?php if (isset($last_nameErr)) echo "<div class='text-danger'>*$last_nameErr</div>  "; ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Gender</td>
                    <td>
                        <div class="form-check form-check-inline">
                            <input type="radio" id="male" name='gender' value="Male" class="form-check-input">
                            <label class="form-check-label" for="male">Male</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" id="female" name='gender' value="Female" class="form-check-input">
                            <label class="form-check-label" for="female">Female</label>
                        </div>
                        <span>
                            <?php if (isset($genderErr)) echo "<div class='text-danger'>*$genderErr</div>  "; ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Date of birth</td>
                    <td><input type='date' name='date_of_birth' class='form-control' />
                        <span>
                            <?php if (isset($date_of_birthErr)) echo "<div class='text-danger'>*$date_of_birthErr</div>  "; ?>
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