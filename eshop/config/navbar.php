<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Create a Record - PHP CRUD Tutorial</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>

<body>
<?php
    include 'config/database.php';
    if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
        $query = 'SELECT username, first_name, last_name, gender, customer_img from customers WHERE email= ?';
    } else {
        $query = 'SELECT username, first_name, last_name, gender, customer_img FROM customers WHERE username=?';
    }

    $stmt = $con->prepare($query);
    $stmt->bindParam(1, $_SESSION['username']);
    $stmt->execute();
    $customer_list = $stmt->rowCount();


    if ($customer_list > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $username = $row['username'];
        $first_name = $row['first_name'];
        $last_name = $row['last_name'];
        $gender = $row['gender'];
        $customer_img = $row['customer_img'];
        
    $customer_name = "$first_name $last_name";
        
    }

    ?>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-md-light bg-dark">
        <div class="container-fluid d-flex justify-content-between">
            <div class="d-flex">
                <a class="navbar-brand text-light" href="home.php">Hanson1030</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active text-light" aria-current="page" href="home.php">Home</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">Product</a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <li><a class="dropdown-item" href="product_read.php">Read Product</a></li>
                                <li><a class="dropdown-item" href="product_create.php">Create Product</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink1" role="button" data-bs-toggle="dropdown" aria-expanded="false">Customer</a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink1">
                                <li><a class="dropdown-item" href="customer_read.php">Read Customer</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink2" role="button" data-bs-toggle="dropdown" aria-expanded="false">Order</a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink2">
                                <li><a class="dropdown-item" href="order_read.php">Read Order</a></li>
                                <li><a class="dropdown-item" href="order_create.php">Create Order</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-secondary" href="contact_us.php">Contact Us</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="collapse navbar-collapse d-flex justify-content-end me-5" id="navbarSupportedContent">
                <ul class="navbar-nav">
                    <li class="nav-item dropdown d-flex">
                        <a class="nav-link" href="#" id="navbarDropdownMenuLink2" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php
                            echo $customer_name;
                            echo '<td><img src="cus_img/' . $customer_img . '"width="50px" height="50px" class="rounded-circle ms-3"></td>';
                            ?>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink2">
                            <li><a class="dropdown-item" href="customer_update.php?id=<?php echo $username ?>">Edit Progile</a></li>
                            <li><a class="dropdown-item" href="logout.php">Log out</a></li>
                        </ul>
                    </li>
                </ul>
            </div>

        </div>
    </nav>