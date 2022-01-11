<!--ID : 2050093-BSE -->
<!--Name : Mak Hon Sang -->
<!--Topic : Order Delete Page-->
<?php
// include database connection
include 'config/database.php';
try {
    // get record ID
    // isset() is a PHP function used to verify if a value is there or not
    $id = isset($_GET['id']) ? $_GET['id'] :  die('ERROR: Record ID not found.');

    // delete query
    $query_del_OD = "DELETE FROM order_details WHERE order_id = ?";
    $stmt_del_OD = $con->prepare($query_del_OD);
    $stmt_del_OD->bindParam(1, $id);
    $stmt_del_OD->execute();


    $query_del_OS = "DELETE FROM order_summary WHERE order_id = ?";
    $stmt_del_OS = $con->prepare($query_del_OS);
    $stmt_del_OS->bindParam(1, $id);
    $stmt_del_OS->execute();

    header('Location: order_read.php?action=deleted');
}
// show error
catch (PDOException $exception) {
    die('ERROR: ' . $exception->getMessage());
}
?>
