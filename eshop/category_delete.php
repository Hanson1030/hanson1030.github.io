<?php
// include database connection
include 'config/database.php';
try {
    // get record ID
    // isset() is a PHP function used to verify if a value is there or not
    $id = isset($_GET['id']) ? $_GET['id'] :  die('ERROR: Record ID not found.');

    $query_del_category = 'SELECT categories.category_id, products.category_id 
    FROM categories 
    INNER JOIN products 
    ON products.category_id = categories.category_id 
    WHERE categories.category_id=?';

    $stmt_del_category = $con->prepare($query_del_category);
    $stmt_del_category->bindParam(1, $id);
    $stmt_del_category->execute();
    $num = $stmt_del_category->rowCount();

    if ($num > 0) { 
        //if there is product related to selected category
        header('Location: category_read.php?action=delErr');
    } else {
        // delete query
        $query = "DELETE FROM categories WHERE category_id = ?";
        $stmt_del_category = $con->prepare($query);
        $stmt_del_category->bindParam(1, $id);

        if ($stmt_del_category->execute()) {
            // redirect to read records page and
            // tell the user record was deleted
            header('Location: category_read.php?action=deleted');
        } else {
            die('Unable to delete record.');
        }
    }
}
// show error
catch (PDOException $exception) {
    die('ERROR: ' . $exception->getMessage());
}
