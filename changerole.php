<?php
session_start();
include("connection.php");
include("functions.php");
$user_data = check_login_and_role($con);

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['bchangerole'])) {
    $user_id = $_POST['userid'];
    $role = $_POST['role'];
    $query = "update users set role='$role' where id=$user_id";
    mysqli_query($con, $query);
    header("Location: manage_users.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Delete</title>

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
                $user_id = $_GET['userid'];
                $role = $_GET['role'];
                //header("Location:manage_ads.php?delete_ad_id=$ad_id");
                echo "<input type='hidden' name='userid' value=$user_id>";
                echo "<input type='hidden' name='role' value=$role>";
                ?>
                <h2>Are you sure you want to change the role of the user?</h2>
                <button type="submit" name="bchangerole" value="1" class="btn btn-primary w-100">Yes</button><br><br>
                <a href="manage_users.php">Go back</a>

            </form>
        </div>
    </div>
</body>

</html>