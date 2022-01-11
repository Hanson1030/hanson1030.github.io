<!--ID : 2050093-BSE -->
<!--Name : Mak Hon Sang -->
<!--Topic : Product Read Page-->
<!DOCTYPE HTML>
<html>

<head>
    <title>Product List</title>
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
            <h1>Products List</h1>
        </div>

        <?php
        include 'config/database.php';

        //delete record
        $action = isset($_GET['action']) ? $_GET['action'] : "";

        // if it was redirected from delete.php
        if ($action == 'deleted') {
            echo "<div class='alert alert-success my-3'>Record was deleted.</div>";
        }

        $query_category = "SELECT * FROM categories ORDER BY category_id ASC";
        $stmt_category = $con->prepare($query_category);
        $stmt_category->execute();

        $query_allProd = "SELECT categories.category_name, products.product_id, products.name, products.description, products.price, products.promotion_price, products.product_img
    FROM categories
    INNER JOIN products 
    ON products.category_id = categories.category_id 
    ORDER BY product_id DESC";

        $stmt_allProd = $con->prepare($query_allProd);
        $stmt_allProd->execute();
        $table = $stmt_allProd->fetchAll();

        $flag = 0;
        $message = '';
        if (isset($_POST['filter'])) {

            $category_option = $_POST['category'];

            if ($category_option != "show_all") {
                $query_selectedCat = "SELECT product_id, name, description, price, promotion_price, product_img
            FROM products
            WHERE category_id = :category_id
            ORDER BY product_id DESC";

                $stmt_selectedCat = $con->prepare($query_selectedCat);
                $stmt_selectedCat->bindParam(':category_id', $category_option);
            } else {
                $query_selectedCat = "SELECT categories.category_name, products.product_id, products.name, products.description, products.price, products.promotion_price, products.product_img
            FROM categories
            INNER JOIN products 
            ON products.category_id = categories.category_id 
            ORDER BY product_id DESC";

                $stmt_selectedCat = $con->prepare($query_selectedCat);
            }
            $stmt_selectedCat->execute();
            $num = $stmt_selectedCat->rowCount();
            $table = $stmt_selectedCat->fetchAll();
        } elseif (isset($_POST['search'])) {

            if (empty($_POST['search_field'])) {
                echo "<div class='alert alert-danger mt-4'>Nothing was searched.</div>";
            }

            $query_search = "SELECT products.product_id, products.name, products.description, products.price, products.promotion_price, categories.category_name, products.product_img
        FROM products
        INNER JOIN categories
        ON products.category_id = categories.category_id
        WHERE products.name LIKE :name
        ORDER BY product_id ";

            $search_field = "%" . $_POST['search_field'] . "%";
            $stmt_search = $con->prepare($query_search);
            $stmt_search->bindParam(':name', $search_field);
            $stmt_search->execute();
            $num = $stmt_search->rowCount();
            $table = $stmt_search->fetchAll();
        }


        if (isset($_POST['filter']) || !isset($_POST['filter']) || $category_option == "show_all" || isset($_POST['search']) || !isset($_POST['search'])) {
            $category_option = $_POST ? $_POST['category'] : ' ';
            $table_content = '';
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            foreach ($table as $row) {

                $category_header = $category_option == "show_all" || !isset($_POST['filter']) ? "<td>" . $row['category_name'] . "</td>" : ' ';



                if ($row['product_img'] == '') {
                    $product_img = "<td><img src='prod_img/nopic.png' style='object-fit: cover;height:100px;width:100px;'><br>";
                } else {
                    $product_img = "<td><img src='prod_img/" . $row['product_img'] . "'style='object-fit: contain;height:100px;width:100px;'></td>";
                }

                if ($row['promotion_price'] == 0) {
                    $product_price = $row['price'];
                } else {
                    $product_price = $row['promotion_price'];
                }



                //set a variable for table content
                $table_content = $table_content . "<tr class='text-center'>"
                    . "$product_img"
                    . "<td>" . $row['product_id'] . "</td>"
                    . "<td>" . $row['name'] . "</td>"
                    . "<td>" . $row['description'] . "</td>"
                    . $category_header
                    . "<td class='text-end'>" . $product_price . "</td>"

                    . "<td>"
                    //read one record
                    . "<a href='product_read_one.php?id={$row['product_id']}' class='btn btn-info'>Read</a>"

                    //edit record
                    . "<a href='product_update.php?id={$row['product_id']}' class='btn btn-primary mx-2 my-2'>Edit</a>"

                    //delete record
                    . "<a href='#' onclick='delete_product({$row['product_id']});'  class='btn btn-danger'>Delete</a>"
                    . "</td>"
                    . "</tr>";
            }
        }

        if ($_POST) {
            if ($num <= 0) {
                echo "<div class='alert alert-danger mt-4'>No records found.</div>";
                echo "<div class='d-flex justify-content-center m-3'>";
                echo "<a href='product_read.php' class='btn btn-warning'>Back to Product Read</a>";
                echo "</div>";
            }
        }
        ?>




        <div class="d-flex justify-content-center m-3">
            <a href='product_create.php' class='btn btn-primary'>Create New Product</a>
        </div>

        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
            <div class="row d-flex justify-content-center m-3">
                <select class="fs-4 rounded col-4" name="category">
                    <option value="show_all">Show All</option>

                    <?php
                    $category_list = $_POST ? $_POST['category'] : ' ';
                    while ($row = $stmt_category->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $selected_category = $row['category_id'] == $category_list ? 'selected' : '';
                        echo "<option class='bg-white' value='$category_id' $selected_category>$category_name</option>";
                    }
                    ?>

                </select>
                <input type="submit" value="Go" name="filter" class="btn-sm btn btn-success col-1 mx-2 fs-5" />
            </div>

            <div class="row d-flex justify-content-center m-3">
                <input type="text" placeholder="Search..." name="search_field" value="<?php $search_field ?>" class="fs-4 rounded col-4" />
                <input type="submit" value="Search" name="search" class="btn-sm btn btn-success col-1 mx-2 fs-5">
            </div>
        </form>

        <table class='table table-hover table-responsive table-bordered'>

            <tr class="text-center">
                <th>Product Image</th>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <?php
                echo $_POST && $category_option == "show_all" || !isset($_POST['filter']) ? "<th>Category</th>" : '';
                ?>
                <th>Price</th>
                <th>Action</th>
            </tr>

            <?php
            //check if more than 0 record found
            echo $table_content;
            ?>

        </table>

    </div>


    </div> <!-- end .container -->

    <script type='text/javascript'>
        // confirm record deletion
        function delete_product(id) {

            if (confirm('Are you sure?')) {
                // if user clicked ok,
                // pass the id to delete.php and execute the delete query
                window.location = 'product_delete.php?id=' + id;
            }
        }
    </script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>