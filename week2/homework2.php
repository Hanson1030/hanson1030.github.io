<!DOCTYPE html>
<html>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-uWxY/CJNBR+1zjPWmfnSnVxwRheevXITnMqoEIeG1LJrdI0GlVs/9cVSyPYXdcSF" crossorigin="anonymous" />
    <title>
        Homework 1
    </title>
</head>

<body>
    <form class="text-center">
        <div class="text-center">
            <h1>What is your birthday? Please fill in !</h1>

            <?php
            //Day
            echo '<select class="bg-primary fs-2 rounded" id="day" name="day">' . "\n";
            echo "<option class='bg-white' selected>Day</option>" . "\n";

            for ($day = 1; $day <= 31; $day++) {
                echo "<option class='bg-white'>" . $day . "</option>" . "\n";
            }
            echo '</select>' . "\n";
            ?>

            <?php
            //Month
            $month = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

            echo '<select class="bg-secondary fs-2 rounded" id="month" name="month">' . "\n";
            echo "<option class='bg-white' selected>Month</option>" . "\n";

            for ($x = 0; $x < 12; $x++) { 
                //echo "<option class='bg-white'>". date('F', mktime(0,0,0,$month))."</option>"."\n";
                //echo "<option class='bg-white'>" . $i_month . "</option>" . "\n";
                echo "<option class='bg-white'>" . $month[$x] . "</option>" . "\n";

            }
            echo '</select>' . "\n";
            ?>

            <?php
            //Year
            $staring_year  = 1900;
            $curr_year = date('Y');

            echo '<select class="bg-warning fs-2 rounded" id="year" name="year">' . "\n";
            echo "<option class='bg-white' selected>Year</option>" . "\n";

            for ($year = $staring_year; $year <= $curr_year; $year++) {
                echo "<option class='bg-white'>" . $year . "</option>" . "\n";
            }
            echo '</select>' . "\n";
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