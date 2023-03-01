<?php
session_start();
include("connection.php");
include("functions.php");

$user_data = check_login_and_role($con);
if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
    if ($msg == 1) {
        echo "<script>alert('Category added successfully');</script>";
    } else if ($msg == 2) {
        echo "<script>alert('City added successfully');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="./styles/styles.css?v=<?php echo time(); ?>">
</head>

<body>
    <?php
    include("nav.php");
    ?>
    <div class="container">
        <ul class="list-group text-center" style="margin-top: 3em;">
            <li class="list-group-item ">
                <a class="btn btn-secondary w-100" href="reviewads.php">Review Advertisements</a>
            </li>
            <br><br>
            <li class="list-group-item">
                <a class="btn btn-secondary w-100" href="add_city_category.php?add=category">Add Category</a>
            </li>
            <li class="list-group-item">
                <a class="btn btn-secondary w-100" href="add_city_category.php?add=city">Add City</a>
            </li>
            <br><br>
            <li class="list-group-item">
                <a class="btn btn-secondary w-100" href="manage_ads.php">Manage Advertisements</a>
            </li>
            <?php
            if ($user_data['role'] == 'owner') {
                echo "<li class='list-group-item'>
                <a class='btn btn-secondary w-100' href='manage_users.php'>Manage Users</a>
                </li>";
            }
            ?>

        </ul>
    </div>

</body>

</html>