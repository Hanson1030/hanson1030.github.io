<!--ID : 2050093-BSE -->
<!--Name : Mak Hon Sang -->
<!--Topic : Customer Delete Page-->
<?php
// include database connection
include 'config/database.php';
try {
    // get record ID
    // isset() is a PHP function used to verify if a value is there or not
    $id = isset($_GET['id']) ? $_GET['id'] :  die('ERROR: Record ID not found.');

    $qconfirm = 'SELECT order_summary.username, customers.username FROM customers INNER JOIN order_summary ON order_summary.username = customers.username WHERE customers.username=?';
    $stmt = $con->prepare($qconfirm);
    $stmt->bindParam(1, $id);
    $stmt->execute();
    $num = $stmt->rowCount();
    echo $num;
    if ($num > 0) {
        header('Location: customer_read.php?action=delErr');
    } else {
        // delete query
        $query = "DELETE FROM customers WHERE username = ?";
        $stmt = $con->prepare($query);
        $stmt->bindParam(1, $id);

        if ($stmt->execute()) {
            // redirect to read records page and
            // tell the user record was deleted
            header('Location: customer_read.php?action=deleted');
        } else {
            die('Unable to delete record.');
        }
    }
}
// show error
catch (PDOException $exception) {
    die('ERROR: ' . $exception->getMessage());
}
