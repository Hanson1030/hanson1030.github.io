<?php
include 'config/navbar.php';
?>
<!-- container -->
<div class="container">

    <?php
    include 'config/database.php';

    $query_category = "SELECT * FROM categories ORDER BY category_id ASC";
    $stmt_category = $con->prepare($query_category);
    $stmt_category->execute();

    $query_allProd = "SELECT categories.category_name, products.product_id, products.name, products.description, products.price
    FROM categories
    INNER JOIN products 
    ON products.category_id = categories.category_id 
    ORDER BY product_id DESC";

    $stmt_allProd = $con->prepare($query_allProd);
    $stmt_allProd->execute();
    $table = $stmt_allProd->fetchAll();

    $table_content = '';
    foreach ($table as $row) {

        //set a variable for table content
        $table_content = $table_content . "<tr>"
            . "<td>" . $row['product_id'] . "</td>"
            . "<td>" . $row['name'] . "</td>"
            . "<td>" . $row['description'] . "</td>"
            . "<td>" . $row['category_name'] . "</td>"
            . "<td>" . $row['price'] . "</td>"

            . "<td>"
            //read one record
            . "<a href='product_read_one.php?id={$row['product_id']}' class='btn btn-info'>Read</a>"

            //edit record
            . "<a href='product_update.php?id={$row['product_id']}' class='btn btn-primary'>Edit</a>"

            //delete record
            . "<a href='#' onclick='delete_product({$row['product_id']});'  class='btn btn-danger'>Delete</a>"

            . "</td>"

            . "</tr>";
    }

    if (isset($_POST['search'])) {

        $category_option = $_POST['category'];
        $table_content = '';

        if ($category_option != "select_category") {
            $query_seletedCat = "SELECT categories.category_name, products.product_id, products.name, products.description, products.price
            FROM categories
            INNER JOIN products   
            ON products.category_id = categories.category_id 
            WHERE category_name 
            LIKE :category_name 
            ORDER BY product_id DESC";
            $stmt_seletedCat = $con->prepare($query_seletedCat);
            $stmt_seletedCat->execute(array(':category_name' => $category_option));
            $table = $stmt_seletedCat->fetchAll();

            foreach ($table as $row) {

                //set a variable for table content
                $table_content = $table_content . "<tr>"
                    . "<td>" . $row['product_id'] . "</td>"
                    . "<td>" . $row['name'] . "</td>"
                    . "<td>" . $row['description'] . "</td>"
                    . "<td>" . $row['category_name'] . "</td>"
                    . "<td>" . $row['price'] . "</td>"

                    . "<td>"
                    //read one record
                    . "<a href='product_read_one.php?id={$row['product_id']}' class='btn btn-info'>Read</a>"

                    //edit record
                    . "<a href='product_update.php?id={$row['product_id']}' class='btn btn-primary'>Edit</a>"

                    //delete record
                    . "<a href='#' onclick='delete_product({$row['product_id']});'  class='btn btn-danger'>Delete</a>"

                    . "</td>"

                    . "</tr>";
            }
        }

        if ($category_option == "select_category") {

            $query_seletedCat = "SELECT categories.category_name, products.product_id, products.name, products.description, products.price
            FROM categories
            INNER JOIN products 
            ON products.category_id = categories.category_id 
            ORDER BY product_id DESC";

            $stmt_seletedCat = $con->prepare($query_seletedCat);
            $stmt_seletedCat->execute();
            $table = $stmt_seletedCat->fetchAll();

            foreach ($table as $row) {

                //set a variable for table content
                $table_content = $table_content . "<tr>"
                    . "<td>" . $row['product_id'] . "</td>"
                    . "<td>" . $row['name'] . "</td>"
                    . "<td>" . $row['description'] . "</td>"
                    . "<td>" . $row['category_name'] . "</td>"
                    . "<td>" . $row['price'] . "</td>"

                    . "<td>"
                    //read one record
                    . "<a href='product_read_one.php?id={$row['product_id']}' class='btn btn-info'>Read</a>"

                    //edit record
                    . "<a href='product_update.php?id={$row['product_id']}' class='btn btn-primary'>Edit</a>"

                    //delete record
                    . "<a href='#' onclick='delete_product({$row['product_id']});'  class='btn btn-danger'>Delete</a>"

                    . "</td>"

                    . "</tr>";
            }
        }
    }
    ?>

    <div class="container">
        <div class="page-header">
            <h1>Read Products</h1>
        </div>

        <div class="d-flex justify-content-center m-3">
            <a href='product_create.php' class='btn btn-primary'>Create New Product</a>
        </div>

        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
            <div class="row d-flex justify-content-center m-3">
                <select class="fs-4 rounded col-4" name="category">
                    <option value="select_category">-----SELECT Category-----</option>

                    <?php
                    $category_list = $_POST ? $_POST['category'] : ' ';
                    while ($row = $stmt_category->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $selected_category = $row['category_name'] == $category_list ? 'selected' : '';
                        echo "<option class='bg-white' value='$category_name' $selected_category>$category_name</option>";
                    }
                    ?>

                </select>
                <input type="submit" value="Search" name="search" class="btn-sm btn btn-success col-1 mx-2 fs-5" />
            </div>

            <table class='table table-hover table-responsive table-bordered'>

                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Category</th>
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

<!-- confirm delete record will be here -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>