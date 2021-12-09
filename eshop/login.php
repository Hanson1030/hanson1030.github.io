<?php
session_start();
?>

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
    if ($_POST) {

      // include database connection
      include 'config/database.php';

      $flag = 0;
      $message = '';

      if (empty($_POST['username'])) {
        $flag = 1;
        $message = "Please insert your username. <br>";
      }
      if (empty($_POST['password'])) {
        $flag = 1;
        $message = $message . "Please insert your password.";
        //$message .= "Please insert your password.";
      }

      if ($flag == 0) {
        $username = $_POST['username'];
        if (filter_var("$username", FILTER_VALIDATE_EMAIL)) {
          $query = 'SELECT username, email, password, account_status from customers WHERE email=?';
        } else {
          $query = 'SELECT username, email, password, account_status from customers WHERE username=?';
        }
        $stmt = $con->prepare($query);
        $stmt->bindParam(1, $username);
        $stmt->execute();
        $num = $stmt->rowCount();

        if ($num > 0) {
          $row = $stmt->fetch(PDO::FETCH_ASSOC);

          if (md5($_POST['password']) == $row['password']) {
            if ($row['account_status'] == 'Active') {
              $_SESSION['username'] = $username;
              header("Location:home.php");
            } else {
              $flag = 1;
              $message = 'Please tell admin to activate your account.';
            }
          } else {
            $flag = 1;
            $message = 'Your password is incorrect';
          }
        } else {
          $flag = 1;
          $message = 'Username is not exists.';
        }
      }
    }
    ?>

    <div class="text-center  d-flex align-items-center h-100">

      <div class="container w-50 w-md-25">
        <h2>Login</h2>
        <?php
        if (isset($flag) && $flag == 1) {
          echo "<div class='alert alert-danger'>$message</div>";
        }

        if (isset($_GET['msg']) && $_GET['msg'] == 'logout') {
          echo "<div class='alert alert-success'>Logout Successful</div>";
        }
        if (isset($_GET['msg']) && $_GET['msg'] == 'loginerr') {
          echo "<div class='alert alert-danger'>Unable to access. Please Login.</div>";
        }
        ?>
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
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
          <p>Don't have an account? <a href="customer_create.php">Sign up now</a>.</p>
        </form>

      </div>

    </div>


  </div> <!-- end .container -->

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>