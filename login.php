<?php
session_start();
include("connection.php");
include("functions.php");
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    //something was posted
    $user_name = $_POST['user_name'];
    $password = $_POST['password'];
    if (!empty($user_name) && !empty($password)) {
        //read from db
        $query = "select * from users where user_name = '$user_name' limit 1";

        $result = mysqli_query($con, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);
            if ($user_data['password'] === $password) {
                $_SESSION['user_id'] = $user_data['user_id'];
                if (isset($_GET['advert'])) {
                    header("Location: advert.php?advert=" . $_GET['advert']);
                    die;
                } else {
                    header("Location: index.php");
                    die;
                }
            }
        }
        echo "<script>alert('Wrong username or password');</script>";
    } else {
        echo "<script>alert('Please enter valid information');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

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
                <h1>Login</h1>
                <br>
                <input type="text" name="user_name" placeholder="Username" class="w-100"><br><br>
                <input type="password" name="password" placeholder="Password" class="w-100"><br><br>
                <input type="submit" value="Login" class="btn btn-primary w-100"><br><br>
                <a href="signup.php" class="btn btn-secondary w-100">Click to signup</a>
                <a href="index.php">Go to home page</a>
            </form>
        </div>
    </div>
</body>

</html>