<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Read Records - PHP CRUD Tutorial</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- custom css -->
    <style>
        .m-r-1em {
            margin-right: 1em;
        }

        .m-b-1em {
            margin-bottom: 1em;
        }

        .m-l-1em {
            margin-left: 1em;
        }

        .mt0 {
            margin-top: 0;
        }
    </style>
</head>

<body>
    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Update Customer</h1>
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
            $query = "SELECT username, email, first_name, last_name, password FROM customers WHERE username = ? LIMIT 0,1";
            $stmt = $con->prepare($query);

            // this is the first question mark
            $stmt->bindParam(1, $id);

            // execute our query
            $stmt->execute();

            // store retrieved row to a variable
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // values to fill up our form
            $username = $row['username'];
            $email = $row['email'];
            $first_name = $row['first_name'];
            $last_name = $row['last_name'];
            $password = $row['password'];
        }

        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>

        <?php
        // check if form was submitted
        if ($_POST) {
            try {
                // write update query
                // in this case, it seemed like we have so many fields to pass and
                // it is better to label them and not use question marks
                $query = "UPDATE customers SET username=:username, email=:email, first_name=:first_name, last_name=:last_name, password=:new_password WHERE username = :username";
                // prepare query for excecution
                $stmt = $con->prepare($query);
                // posted values
                $username = htmlspecialchars(strip_tags($_POST['username']));
                $email = htmlspecialchars(strip_tags($_POST['email']));
                $first_name = htmlspecialchars(strip_tags($_POST['first_name']));
                $last_name = htmlspecialchars(strip_tags($_POST['last_name']));
                $old_password = htmlspecialchars(strip_tags($_POST['old_password']));
                $new_password = htmlspecialchars(strip_tags($_POST['new_password']));
                $confirm_new_password = htmlspecialchars(strip_tags($_POST['confirm_new_password']));
                // bind the parameters
                $stmt->bindParam(':username', $id);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':first_name', $first_name);
                $stmt->bindParam(':last_name', $last_name);
                $stmt->bindParam(':new_password', $new_password);
                //$stmt->bindParam(':product_id', $product_id);
                // Execute the query

                $flag = 0;
                $message = ' ';
                if (empty($old_password) && empty($new_password) && empty($confirm_new_password)) {
                    $flag = 0;
                    $unchange_new_password = htmlspecialchars(strip_tags($row['password']));
                    $unchange_confirm_new_password = htmlspecialchars(strip_tags($row['password']));
                    $stmt->bindParam(':new_password', $unchange_new_password);
                    $stmt->bindParam(':comfirm_new_password', $unchange_confirm_new_password);
                }

                if (!empty($old_password) || !empty($new_password) || !empty($confirm_new_password)) {
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {

                        if (empty($email)) {
                            $flag = 1;
                            $message = "Please fill in every field.";
                            $emailErr = "Name is required";
                        }

                        if (empty($first_name)) {
                            $flag = 1;
                            $message = "Please fill in every field.";
                            $first_nameErr = "First Name is required";
                        }

                        if (empty($last_name)) {
                            $flag = 1;
                            $message = "Please fill in every field.";
                            $last_nameErr = "Last Name is required";
                        }
                    }

                    if (empty($old_password)) {
                        $flag = 1;
                        $message = "Old Password CANNOT be empty if user want to change password";
                        $usernameErr = "Name is required";
                    } elseif (empty($new_password)) {
                        $flag = 1;
                        $message = "New Password CANNOT be empty if user want to change password";
                        $emailErr = "Email is required";
                    } elseif (empty($confirm_new_password)) {
                        $flag = 1;
                        $message = "Confirm New Password CANNOT be empty if user want to change password";
                        $passwordErr = "Password is required";
                    } elseif ($old_password !== $password) {
                        $flag = 1;
                        $message = 'Your Old Password is Incorrect!';
                    } elseif ($old_password == $new_password) {
                        $flag = 1;
                        $message = 'New Password cannot be same as your Old Password.';
                    } elseif (!preg_match("/[a-zA-Z]/", $new_password) || !preg_match("/[0-9]/", $new_password) || !preg_match("/[a-zA-Z0-9]{8,}/", $new_password)) {
                        $flag = 1;
                        $message = "Password must at least 8 character and must contain number and alphabets.";
                    } elseif ($new_password !== $confirm_new_password) {
                        $flag = 1;
                        $message = "New password and Confirm Password is NOT match.";
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
            // show errors
            catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        } ?>


        <!--we have our html form here where new record information can be updated-->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$id}"); ?>" method="post">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Username</td>
                    <td><input type='text' name='username' value="<?php echo htmlspecialchars($username, ENT_QUOTES);  ?>" class='form-control' readonly /></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><input type='text' name='email' value="<?php echo htmlspecialchars($email, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>First Name</td>
                    <td><input type='text' name='first_name' value="<?php echo htmlspecialchars($first_name, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Last Name</td>
                    <td><input type='text' name='last_name' value="<?php echo htmlspecialchars($last_name, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Old Password</td>
                    <td><input type='text' name='old_password' value="<?php echo htmlspecialchars($password, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>New Password</td>
                    <td><input type='text' name='new_password' value="<?php echo htmlspecialchars($password, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Confirm New Password</td>
                    <td><input type='text' name='confirm_new_password' value="<?php echo htmlspecialchars($password, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save Changes' class='btn btn-primary' />
                        <a href='customer_read.php' class='btn btn-danger'>Back to read products</a>
                    </td>
                </tr>
            </table>
        </form>

    </div>
    <!-- end .container -->
</body>

</html>