<?php
session_start();
include("connection.php");
include("functions.php");
$user_data = check_login_and_role($con);

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['additem'])) {
    $additem = $_POST['additem'];
    if ($additem == 1) {
        $category_name = $_POST['category_name'];
        $query = "insert into categories (category_name) values ('$category_name')";
        mysqli_query($con, $query);
        header('Location: adminmenu.php?msg=1');
    }
    if ($additem == 2) {
        $city_name = $_POST['city_name'];
        $query = "insert into cities (city_name) values ('$city_name')";
        mysqli_query($con, $query);
        header('Location: adminmenu.php?msg=2');
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous">
    </script>
</head>

<body>
    <div class="container vh-100">
        <div class="row vh-100 justify-content-center align-items-center text-center">
            <form method="post" class="w-50">

                <?php
                if (isset($_GET['add'])) {
                    $add = $_GET['add'];
                }
                if ($add == 'category') {
                    echo    "<h1>Add Category</h1><br><input type='text' name='category_name' placeholder='Category name, e.g. Vehicles' class='w-100'><br><br>";
                    echo "<input type='hidden' name='additem' value=1>";
                }
                if ($add == 'city') {
                    echo    "<h1>Add City</h1><br><input type='text' name='city_name' placeholder='City name, e.g. Cape Town' class='w-100'><br><br>";
                    echo "<input type='hidden' name='additem' value=2>";
                }
                ?>
                <input type="submit" value="Add Item" class="btn btn-primary w-100"><br><br>
                <a href="adminmenu.php">Go back</a>
            </form>
        </div>
    </div>
</body>

</html>