<?php
if ($_POST) {
    for ($x = 0; $x < count($_POST['product']); $x++) {
        if ($_POST['product'][$x] === '' && $_POST['quantity'][$x] === '') {
            unset($_POST['product'][$x]);
            unset($_POST['quantity'][$x]);
        }
    }

    var_dump($_POST['product']);
    var_dump($_POST['quantity']);
}
?>

<form method="POST">
    <table>
        <?php
        $post_product = $_POST ? count($_POST['product']) : 1;
        for ($product_row = 0; $product_row < $post_product; $product_row++) {
        ?>
            <tr class="productRow">
                <td>
                    Product <?php $product_row ?>
                    <select name='product[]'>
                        <option value=''>--Select Product--</option>
                        <option value='a'>a</option>
                        <option value='b'>b</option>
                        <option value='c'>c</option>
                    </select>
                </td>
                <td>
                    <select name='quantity[]'>
                        <option value=''>--Select Quantity--</option>
                        <option value='1'>1</option>
                        <option value='2'>2</option>
                        <option value='3'>3</option>
                    </select>
                </td>
            </tr>
        <?php
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
            <td>
                <input type='submit' value='Save' class='btn btn-primary' />
            </td>
        </tr>
    </table>
</form>

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

        function incrementValue() {
            var value = parseInt(document.getElementById('number').value, 10);
            value = isNaN(value) ? 0 : value;
            value++;
            document.getElementById('number').value = value;
        }
    </script>