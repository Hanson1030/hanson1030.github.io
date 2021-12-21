<?php
include 'config/navbar.php';
?>
<!-- container -->
<div class="container">
    <div class="page-header">
        <h1>Update Order</h1>
    </div>
    <?php
    // get passed parameter value, in this case, the record ID
    // isset() is a PHP function used to verify if a value is there or not
    $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');

    //include database connection
    include 'config/database.php';

    // read current record's data
    try {

        $query = "SELECT order_details.orderdetail_id, order_details.order_id, order_details.product_id, order_details.quantity, products.name 
        FROM order_details 
        INNER JOIN products 
        ON order_details.product_id = products.product_id 
        WHERE order_id = :order_id ";

        $stmt = $con->prepare($query);
        $stmt->bindParam(":order_id", $id);
        $stmt->execute();
        $num = $stmt->rowCount();

        $query2 = "SELECT order_summary.order_id, customers.first_name, customers.last_name,customers.username 
        FROM order_summary 
        INNER JOIN customers 
        ON order_summary.username = customers.username 
        WHERE order_id=$id";

        $stmt2 = $con->prepare($query2);
        $stmt2->execute();

        // store retrieved row to a variable
        $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
        $username = $row2['username'];
        $order_id = $row2['order_id'];
        $first_name = $row2['first_name'];
        $last_name = $row2['last_name'];

        $query3 = "SELECT * FROM products ORDER BY product_id DESC";
        $stmt3 = $con->prepare($query3);
        $stmt3->execute();

        $product_arrID = array();
        $product_arrName = array();

        while ($row = $stmt3->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            array_push($product_arrID, $row['product_id']);
            array_push($product_arrName, $row['name']);
        }
    }
    // show error
    catch (PDOException $exception) {
        die('ERROR: ' . $exception->getMessage());
    }
    ?>

    <?php
    // check if form was submitted
    if ($_POST) {

        $flag = 0;
        $product_flag = 0;
        $fail_flag = 0;
        $message = '';

        for ($count1 = 0; $count1 < count($_POST['product']); $count1++) {
            if (!empty($_POST['product'][$count1]) && !empty($_POST['quantity'][$count1])) {
                $product_flag++;
            }
            if (empty($_POST['product'][$count1]) || empty($_POST['quantity'][$count1])) {
                $fail_flag++;
            }
        }
        if ($product_flag < 1) {
            $flag = 1;
            $message = 'Please select the at least one prouct and the associated quantity';
        } elseif ($fail_flag > 0) {
            $flag = 1;
            $message = 'Please enter prouct and the associated quantity';
        } elseif (count($_POST['product']) !== count(array_unique($_POST['product']))) {
            $flag = 1;
            $message = 'Duplicate product is not allowed.';
        }


        try {
            $query_del = "DELETE FROM order_details WHERE order_id = ?";
            $stmt_del = $con->prepare($query_del);
            $stmt_del->bindParam(1, $id);

            if ($flag == 0) {
                if ($stmt_del->execute()) {
                    for ($product_ins = 0; $product_ins < count($_POST['product']); $product_ins++) {
                        $query_ins = 'INSERT INTO order_details SET order_id=:order_id, product_id=:product_id, quantity=:quantity';
                        $stmt_ins = $con->prepare($query_ins);
                        $stmt_ins->bindParam(':order_id', $id);
                        $stmt_ins->bindParam(':product_id', $_POST['product'][$product_ins]);
                        $stmt_ins->bindParam(':quantity', $_POST['quantity'][$product_ins]);
                        if (!empty($_POST['product'][$product_ins]) && !empty($_POST['quantity'][$product_ins])) {
                            $stmt_ins->execute();
                            header("Location:order_read_one.php?id=" . $id);
                        }
                    }
                    echo "<div class='alert alert-success'>Record was saved.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Unable to save record.</div>";
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
    }
    ?>


    <!--we have our html form here where new record information can be updated-->
    <?php
    echo "Order ID : $order_id <br>";
    echo "Username : $username <br>";
    echo "Customer Name : $first_name  $last_name <br>";
    ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$id}"); ?>" method="post">
        <table id="order_table" class='table table-hover table-responsive table-bordered'>
            <tr>
                <th>Product Name</th>
                <th>Quantity</th>
                <th></th>
            </tr>

            <?php
            if ($num > 0) {

                $arrayPost_product = array('');
                if ($_POST) {
                    if (count($_POST['product']) !== 1) {
                        for ($y = 0; $y <= count($_POST['product']); $y++) {
                            if (empty($_POST['product'][$y])  && empty($_POST['quantity'][$y])) {

                                unset($_POST['product'][$y]);
                                unset($_POST['quantity'][$y]);
                            }

                            if (count($_POST['product']) != count(array_unique($_POST['product']))) {

                                unset($_POST['product'][$y]);
                                unset($_POST['quantity'][$y]);
                            }
                        }
                    }
                    $arrayPost_product = $_POST['product'];
                }


                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    echo "<tr class='productRow'>";
                    echo '<td class="col-6"><select class="w-100 fs-4 rounded my-2" name="product[]">';

                    $product_list = $_POST ? $_POST['product'] : ' ';

                    for ($product_count = 0; $product_count < count($product_arrName); $product_count++) {
                        $selected_product = $product_arrID[$product_count] == $row['product_id'] || $product_arrID[$product_count] == $product_list[$product_count] ? 'selected' : ' ';
                        $after_post = $product_arrID[$product_count] == $_POST['product_id'] ? 'selected' : ' ';
                        $posted_product = $_POST[$product_count] ?  $after_post : $selected_product;
                        if ($_POST) {
                            echo  "<option value='" . $product_arrID[$product_count] . "' $selected_product>" . $product_arrName[$product_count] . "</option>";
                        } else {
                            echo  "<option value='" . $product_arrID[$product_count] . "' $selected_product>" . $product_arrName[$product_count] . "</option>";
                        }
                    }
                    echo "</select>";
                    echo '</td>';
                    echo "<td class='col-6'>";
                    echo '<select class="w-100 fs-4 rounded my-2" name="quantity[]" >';
                    $quantity_list = $_POST ? $_POST['quantity'] : ' ';
                    for ($quantity = 1; $quantity <= 5; $quantity++) {
                        $selected_quantity = $row['quantity'] == $quantity || $quantity == $quantity_list[$quantity] ? 'selected' : '';
                        echo "<option value='$quantity' $selected_quantity>$quantity</option>";
                    }
                    echo "</select>";
                    echo "</td>";

                    echo "<td>";
                    echo "<button type='button' class='btn btn-danger my-2' onclick='deleteMe(this)'>Remove</button>";
                    echo "</td>";

                    echo "</tr>";
                }
            }
            ?>
            <tr>
                <td>
                    <div class="d-flex justify-content-center flex-column flex-lg-row">
                        <div class="d-flex justify-content-center">
                            <button type="button" class="btn btn-primary add_one btn mb-3 mx-2">Add More Product</button>
                            <button type="button" class="btn btn-danger delete_one btn mb-3 mx-2">Delete Last Product</button>
                        </div>
                    </div>
                </td>
                <td colspan="2">
                    <div class="d-flex justify-content-center flex-column flex-lg-row">
                        <div class="d-flex justify-content-center">
                            <input type='submit' value='Save Changes' class='btn btn-primary mx-2' />
                            <a href='order_read.php' class='btn btn-danger'>Back to read Order</a>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </form>

</div>
<!-- end .container -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
<script>
    document.addEventListener('click', function(event) {
        if (event.target.matches('.add_one')) {
            var element = document.querySelector('.productRow');
            var clone = element.cloneNode(true);
            element.after(clone);
        }
        if (event.target.matches('.delete_one')) {
            var total = document.querySelectorAll('.productRow').length;
            if (total > 1) {
                var element = document.querySelector('.productRow');
                element.remove(element);
            }
        }
    }, false);

    function deleteMe(row) {
        var table = document.getElementById('order_table')
        var allrows = table.getElementsByTagName('tr');
        if (allrows.length == 1) {
            alert("You are not allowed to delete.");
        } else {
            if (confirm("Confirm to delete?")) {
                row.parentNode.parentNode.remove();
            }
        }
    }

    function incrementValue() {
        var value = parseInt(document.getElementById('number').value, 10);
        value = isNaN(value) ? 0 : value;
        value++;
        document.getElementById('number').value = value;
    }
</script>
</body>

</html>