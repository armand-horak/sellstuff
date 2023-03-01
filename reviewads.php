<?php
session_start();
include("connection.php");
include("functions.php");

$user_data = check_login_and_role($con);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review ads</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="./styles/styles.css">
</head>

<body>
    <?php
    include("nav.php");
    ?>
    <div class="container">
        <div class="text-center">
            <h1 style="margin-top: 2em;margin-bottom: 1em;">Approve/Reject Ads</h1>
        </div>
        <hr>
        <?php
        $query = "select * from advertisements where status='pending'";
        $result = mysqli_query($con, $query);
        $ads = mysqli_fetch_all($result, MYSQLI_ASSOC);
        foreach ($ads as $key => $ad) {
            $ad_title = $ad['title'];
            $ad_id = $ad['advert_id'];
            echo "<div class='row'>
            <div class='col col-5'><a href='advert.php?advert=$ad_id&preview=1'>$ad_title</a></div>
            <a class='col col-3 btn btn-success' href='handle_ad_review.php?ad=$ad_id&approve=1'>Approve</a>
             <div class='col col-1'></div>
            <a class='col col-3 btn btn-danger' href='handle_ad_review.php?ad=$ad_id&approve=0'>Reject</a>
            </div><hr>";
        }
        mysqli_free_result($result);
        ?>
    </div>
</body>

</html>