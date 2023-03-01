<?php
session_start();
include("connection.php");
include("functions.php");
$user_data = check_login_and_role($con);

if (isset($_GET['delete_confirm'])) {
    $user_id = $_GET['delete_confirm'];
    $query = "delete from users where id=$user_id";
    mysqli_query($con, $query);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Advertisements</title>
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
        <div class="text-center">
            <h1 style="margin-top: 2em;margin-bottom: 1em;">Manage Users</h1>
            <hr>
        </div>

        <?php
        $query = "select * from users";
        $result = mysqli_query($con, $query);
        $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
        foreach ($users as $key => $user) {
            $user_id = $user['id'];
            $user_name = $user['user_name'];
            $user_f_name = $user['first_name'];
            $user_s_name = $user['last_name'];
            $user_role = $user['role'];
            echo "<div class='row'>
            <div class='col col-5'><a href='public_profile.php?ads=active&user=$user_id'>@$user_name: $user_f_name $user_s_name ($user_role)</a></div>";

            if ($user_role != 'admin' && $user_role != 'owner') {
                echo "<a class='col col-3 btn btn-success' href='changerole.php?userid=$user_id&role=admin'>Make Admin</a>";
            } else if ($user_role == 'admin') {
                echo "<a class='col col-3 btn btn-success' href='changerole.php?userid=$user_id&role=user'>Make User</a>";
            } else {
                echo "<div class='col col-4></div>";
            }
            echo " <div class='col col-1'></div>";
            echo "<a class='col col-3 btn btn-danger' href='delete.php?deletewhat=user&userid=$user_id'>Delete</a>";
            echo "</div><hr>";
        }
        ?>
    </div>
</body>

</html>