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

        try {
            // write update query
            // in this case, it seemed like we have so many fields to pass and
            // it is better to label them and not use question marks
           
            $query_OD = "UPDATE order_details
            SET product_id=:product_id, quantity=:quantity 
            WHERE order_id = :order_id";
            
            $stmt_OD = $con->prepare($query_OD);
            //$username = $_POST['username'];
            $product_id = $_POST['product_id'];
            $quantity = $_POST['quantity'];

            $stmt_OD->bindParam(':product_id', $product_id);
            $stmt_OD->bindParam(':quantity', $quantity);
            $stmt_OD->bindParam(':order_id', $id);

            if ($stmt3->execute()) {
                if ($stmt2->execute()) {
                    echo "<div class='alert alert-success'>Record was saved.</div>";
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
        <table class='table table-hover table-responsive table-bordered'>
            <tr>
                <th>Product Name</th>
                <th>Product Name</th>
            </tr>

            <?php
            if ($num > 0) {

                // creating new table row per record

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    echo "<tr>";
                    echo "<td><select class='form-control' name='product_id'>";
                    for ($product_count = 0; $product_count < count($product_arrName); $product_count++) {
                        $product_selected = $product_arrName[$product_count] == $name ? 'selected' : '';
                        echo "<option value='" . $product_arrID[$product_count] . "'$product_selected>" . $product_arrName[$product_count] . "</option>";
                    }
                    echo "</select></td>";
                    echo "<td><select class='form-select' name='quantity'>";
                    for ($quantity = 1; $quantity <= 5; $quantity++) {
                        $quantity_selected = $row['quantity'] == $quantity ? 'selected' : '';
                        echo "<option value='$quantity'$quantity_selected>$quantity</option>";
                    }
                    echo "</select></td>";
                    echo "</tr>";
                }
                echo "</table>";
                
            }
            ?>
            <tr>
                <td></td>
                <td>
                    <input type='submit' value='Save Changes' class='btn btn-primary' />
                    <a href='order_read.php' class='btn btn-danger'>Back to read Order</a>
                </td>
            </tr>
        </table>
    </form>

</div>
<!-- end .container -->
</body>

</html>