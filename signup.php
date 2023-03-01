<?php
session_start();
include("connection.php");
include("functions.php");
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    //something was posted
    $user_name = $_POST['user_name'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $addressLine1 = $_POST['addressLine1'];
    $city_id = $_POST['city'];
    $postalcode = $_POST['postalcode'];
    $role = 'user';
    if (1) {
        //save to db
        $user_id = random_num(20);
        $query = "insert into users (user_id,role,email,user_name,password,first_name,last_name,addressLine1,city_id,postal_code) values ('$user_id','$role','$email','$user_name','$password','$first_name','$last_name','$addressLine1','$city_id','$postalcode')";

        mysqli_query($con, $query);
        header("Location: login.php");
        die;
    } else {
        echo "Please enter some valid information!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>

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
                <h1>Signup</h1>
                <br>
                <input type="text" name="user_name" placeholder="Username" class="form-control w-100" required><br>
                <input type="password" name="password" placeholder="Password" class="form-control w-100" required><br>
                <input type="email" name="email" placeholder="example@example.com" class="form-control w-100" required><br>
                <input type="text" name="first_name" placeholder="John" class="form-control w-100" required><br>
                <input type="text" name="last_name" placeholder="Doe" class="form-control w-100" required><br>
                <input type="text" name="addressLine1" placeholder="10 Pierneef Street" class="form-control w-100" required><br>
                <label for="city">City</label>
                <?php
                $query = "select * from cities";
                $result = mysqli_query($con, $query);
                $cities = mysqli_fetch_all($result, MYSQLI_ASSOC);
                echo '<select name="city" class="form-control">';
                foreach ($cities as $key => $city) {
                    echo '<option value="' . htmlspecialchars($city['city_id']) . '">'
                        . htmlspecialchars($city['city_name'])
                        . '</option>';
                }
                echo '</select><br>';
                mysqli_free_result($result);
                ?>
                <input type="text" name="postalcode" placeholder="1947" class="form-control w-100" required><br>
                <input type="submit" value="Signup" class="btn btn-primary w-100"><br>
                <a href="login.php">Click to login</a>
            </form>
        </div>
    </div>
</body>

</html>