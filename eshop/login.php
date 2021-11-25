<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Read One Record - PHP CRUD Tutorial</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">

</head>

<body>

    <!-- container -->
    <div class="container vh-100">

        <?php
        include 'config/database.php';

        session_start();

        if (isset($_POST['submit'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $sql = "SELECT * FROM customers WHERE username = '$username' AND password = '$password'";
            $result = $con->query($sql);


            $flag = 0;
            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                if (empty($_POST["username"])) {
                    $flag = 1;
                    $message = "Please fill in every field.";
                }

                if (empty($_POST["password"])) {
                    $flag = 1;
                    $message = "Please fill in every field.";
                }
            }


            if ($flag == 0) {
                if ($result->rowCount() > 0) {
                    $row = $result->fetch(PDO::FETCH_ASSOC);
                    $_SESSION['username'] = $row['username'];
                    header("Location:home.php");
                } else {
                    echo "<div class='alert alert-danger'>";
                    echo "Username or Password is INCORRECT!";
                    echo "</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>";
                echo $message;
                echo "</div>";
            }
        }

        ?>




        <div class="wrapper">



        </div>


        <div class="text-center  d-flex align-items-center h-100">

            <div class="container w-50 w-md-25">
                <h2>Login</h2>

                <form action="" method="post">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control">
                        
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control ">
                    </div>
                    <div class="form-group">
                        <input type="submit" name="submit" class="btn btn-primary" value="Login">
                    </div>
                    <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
                </form>

            </div>

        </div>


    </div> <!-- end .container -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>