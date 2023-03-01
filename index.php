<?php
session_start();
include("connection.php");
include("functions.php");

$user_data = check_login($con);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="./styles/styles.css?v=<?php echo time(); ?>">
    <title>Store</title>
</head>

<body>
    <?php
    include("nav.php");
    ?>

    <div id="navigation"></div>
    <div class="container">
        <div class="text-center" style="margin-top: 3em;margin-bottom: 3em;">
            <h1 style="font-size: 80px;">SellStuff.com</h1>
        </div>

        <?php
        $query = "select * from categories";
        $stmt = $con->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        $categories = $result->fetch_all(MYSQLI_ASSOC);
        // $query = "select * from categories";
        // $result = mysqli_query($con, $query);
        // $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $loop_count = 0;
        $count = 0;
        $row_counter = 0;
        foreach ($categories as $key => $category) {
            $loop_count = $loop_count + 1;
            $category_name = $category['category_name'];
            $count = $count + 1;

            if (($count - 1 % 3 == 0 || $count == 1) && $row_counter == 0) {
                echo "<div class='row justify-content-center text-center' style='margin-bottom:1em;margin-top:1em;'>";
            }
            $row_counter = $row_counter + 1;
            echo "<div class='col-3 category'><a style='text-decoration:none' href='viewadverts.php?sort=mr&category=" . $category["category_id"] . "' class='category-inner'>$category_name</a></div>";

            if ($count % 3 == 0 && $row_counter == 3) {
                echo "</div>";
                $row_counter = 0;
                $count = 0;
            }
            if ($loop_count == count($categories)) {
                echo "</div>";
            }
        }
        mysqli_free_result($result);
        ?>
    </div>

    <?php
    /*$query = "select * from users";
    $result = mysqli_query($con,$query);
    $users = mysqli_fetch_all($result,MYSQLI_ASSOC);
    foreach ($users as $key=>$user) {
        $u_name = $user['user_name'];
        echo "<tr><td style='color:blue;border:solid black'>$u_name</td></tr>";
    }
    mysqli_free_result($result);*/
    ?>
</body>

</html>