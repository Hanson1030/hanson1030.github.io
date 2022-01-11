<!--ID : 2050093-BSE -->
<!--Name : Mak Hon Sang -->
<!--Topic : Customer Read One Page-->
<!DOCTYPE HTML>
<html>

<head>
    <title>Customer Details</title>
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
        if (isset($_GET['msg']) && $_GET['msg'] == 'cus_updateSuccess') {
            echo "<div class='alert alert-success mt-4'>Customer profile has been updated.</div>";
        }
        if (isset($_GET['msg']) && $_GET['msg'] == 'cus_createSuccess') {
            echo "<div class='alert alert-success mt-4'>New Customer had been created.</div>";
        }
        ?>

        <div class="page-header">
            <h1>Read Customer</h1>
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
            $query = "SELECT username, email, first_name, last_name, gender, date_of_birth, customer_img, registration_date FROM customers WHERE username = :username";
            $stmt = $con->prepare($query);

            // Bind the parameter
            $stmt->bindParam(":username", $id);

            // execute our query
            $stmt->execute();

            // store retrieved row to a variable
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // values to fill up our form
            $username = $row['username'];
            $email = $row['email'];
            $first_name = $row['first_name'];
            $last_name = $row['last_name'];
            $gender = $row['gender'];
            $date_of_birth = $row['date_of_birth'];
            $customer_img = $row['customer_img'];
            $registration_date = $row['registration_date'];
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
                <td>Customer Image</td>

                <?php
                if ($customer_img == '') {
                    echo '<td><img src="cus_img/noimg.png" width="200px"><br>';
                } else {
                    echo '<td><img src="cus_img/' . $customer_img . '"width="200px"></td>';
                }
                ?>
            </tr>
            <tr>
                <td>Username</td>
                <td><?php echo htmlspecialchars($username, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><?php echo htmlspecialchars($email, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>First Name</td>
                <td><?php echo htmlspecialchars($first_name, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Last Name</td>
                <td><?php echo htmlspecialchars($last_name, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Gender</td>
                <td><?php echo htmlspecialchars($gender, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Date of Birth</td>
                <td><?php echo htmlspecialchars($date_of_birth, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Registration Date & Time</td>
                <td><?php echo htmlspecialchars($registration_date, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <?php
                    echo "<a href='customer_read.php' class='btn btn-primary me-3'>Back to Customer List</a>";
                    echo "<a href='customer_update.php?id=$id' class='btn btn-danger'>Edit Profile</a>";
                    ?>
                </td>
            </tr>
        </table>

    </div> <!-- end .container -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>