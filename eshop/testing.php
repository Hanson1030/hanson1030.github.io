<!DOCTYPE HTML>
<html>

<head>
    <title>Delete Row Individually</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <table id="order_table" class='table table-hover table-responsive'>
            <tr class='productQuantity'>
                <td>Product</td>
                <td>
                    <div>
                        <select class='form-select' name='productID[]'>
                            <option value=''>-- Select Product --</option>
                        </select>
                    </div>
                </td>
                <td>
                    <div>
                        <select class='form-select' name='quantity[]'>
                            <option value=''>-- Select Quantity --</option>
                            <option value='1'>1</option>
                            <option value='2'>2</option>
                            <option value='3'>3</option>
                        </select>
                    </div>
                </td>
                <td>
                    <button type="button" class="btn mb-3 mx-2" onclick="deleteMe(this)">Remove</button>
                </td>
            </tr>
        </table>
        <div class="d-flex justify-content-center flex-column flex-lg-row">
            <div class="d-flex justify-content-center">
                <button type="button" class="add_one btn mb-3 mx-2">Add More Product</button>
                <button type="button" class="del_last btn mb-3 mx-2">Delete Last Product</button>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4"
        crossorigin="anonymous"></script>
    <script>
        document.addEventListener('click', function (event) {
            if (event.target.matches('.add_one')) {
                var element = document.querySelector('.productQuantity');
                var clone = element.cloneNode(true);
                element.after(clone);
            }
            if (event.target.matches('.del_last')) {
                var total = document.querySelectorAll('.productQuantity').length;
                if (total > 1) {
                    var element = document.querySelector('.productQuantity');
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
    </script>

</body>

</html>