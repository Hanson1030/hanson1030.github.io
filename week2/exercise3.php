<!DOCTYPE html>
<html>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-uWxY/CJNBR+1zjPWmfnSnVxwRheevXITnMqoEIeG1LJrdI0GlVs/9cVSyPYXdcSF" crossorigin="anonymous" />
    <title>
        Exercise 3
    </title>
</head>

<body>

    <?php
    $rand1 = rand(10, 100);
    $rand2 = rand(10, 100);

    //short if else method
    $bigger = ($rand1 > $rand2) ? $rand1 : $rand2 ;
    $smaller = ($rand1 > $rand2) ? $rand2 : $rand1 ;

/*
    //normal if else method
    $bigger = $rand2;
    $smaller = $rand1;

    if ($rand1 > $rand2) {
        $bigger = $rand1;
        $smaller = $rand2;
    }
*/
    echo "<div class='fw-bold'>$bigger</div>";
    echo $smaller;


    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-kQtW33rZJAHjgefvhyyzcGF3C5TFyBQBA13V1RKPf4uH+bwyzQxZ6CmMZHmNBEfJ" crossorigin="anonymous"></script>
</body>

</html>