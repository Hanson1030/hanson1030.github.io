<!--Student ID: 2050093-BSE-->
<!--Name: Mak Hon Sang (Hanson)-->
<!--Topic:  W2-Homework 1 (Using php to select menu - Day, Month, Year)-->
<!DOCTYPE html>
<html>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-uWxY/CJNBR+1zjPWmfnSnVxwRheevXITnMqoEIeG1LJrdI0GlVs/9cVSyPYXdcSF" crossorigin="anonymous" />
    <title>
        W2-Homework 1 (Using php to select menu - Day, Month, Year)
    </title>
</head>

<body>
    <form class="text-center">
        <div class="text-center">
            <h1>What is your birthday? Please fill in !</h1>

            <?php
            //Day
            echo '<select class="bg-primary fs-2 rounded" id="day" name="day">';
            echo "<option class='bg-white' selected>Day</option>" . "\n";

            for ($day = 1; $day <= 31; $day++) {
                echo "<option class='bg-white' value='$day'>  $day  </option>";
            }
            echo '</select>';
            ?>

            <?php
            //Month
            echo '<select class="bg-secondary fs-2 rounded" id="month" name="month">';
            echo "<option class='bg-white' selected>Month</option>" . "\n";

            for ($month = 1; $month <= 12; $month++) {
                echo "<option class='bg-white' value='$month'>  $month  </option>";
            }
            echo '</select>';
            ?>

            <?php
            //Year
            $staring_year  = 1900;
            $curr_year = date('Y');

            echo '<select class="bg-warning fs-2 rounded" id="year" name="year">';
            echo "<option class='bg-white' selected>Year</option>" . "\n";

            for ($year = $staring_year; $year <= $curr_year; $year++) {
                echo "<option class='bg-white' value='$year'>  $year  </option>";
            }
            echo '</select>';
            ?>



            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-kQtW33rZJAHjgefvhyyzcGF3C5TFyBQBA13V1RKPf4uH+bwyzQxZ6CmMZHmNBEfJ" crossorigin="anonymous"></script>
        </div>

        <div class="m-3">
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal">Submit your birthday</button>
            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Thank you for joining our servey! ^^
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-secondary" data-bs-dismiss="modal" onClick="window.location.reload();">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</body>

</html>