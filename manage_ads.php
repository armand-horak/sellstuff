<?php
session_start();
include("connection.php");
include("functions.php");
$user_data = check_login_and_role($con);


if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['deletepress'])) {
    $advert_delete_id = $_POST['delete_ad_id'];
    // $query = "delete from advertisements where advert_id=$advert_delete_id";
    //   mysqli_query($con, $query);
    header("Location: delete.php?deletewhat=ad&delete_ad_id=$advert_delete_id");
}
if (isset($_GET['delete_confirm'])) {
    $advert_delete_id = $_GET['delete_confirm'];
    $query = "delete from advertisements where advert_id=$advert_delete_id";
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
            <h1 style="margin-top: 2em;margin-bottom: 1em;">Manage Advertisements</h1>
            <hr>
        </div>

        <?php
        $query = "select * from advertisements";
        $result = mysqli_query($con, $query);
        $advertisements = mysqli_fetch_all($result, MYSQLI_ASSOC);
        foreach ($advertisements as $key => $advert) {
            $advert_id = $advert['advert_id'];
            $advert_title = $advert['title'];
            $advert_price =  format_money($advert['ad_price']);
            $advert_description = $advert['description'];
            $advert_city = $advert['city_id'];
            $query = "select * from images where advert_id=$advert_id limit 1";
            $result_img = mysqli_query($con, $query);
            $image_url = mysqli_fetch_assoc($result_img);
            echo "<a style='text-decoration:none; color:inherit' href='advert.php?advert=" . $advert["advert_id"] . "'>";
            echo "<div class='row advert-list-item'><div class='col col-3'>";

            if ($result_img && mysqli_num_rows($result_img) > 0) {
                $image_url = $image_url['image_url'];
                echo "<img src='$image_url'/>";
            } else {
                echo "<img src='https://via.placeholder.com/250x250'>";
            }

            echo "</div><div class='col col-9'>";

            mysqli_free_result($result_img);
            echo "<h4>$advert_title</h4>";

            echo "<p class='ad-list-price'>$advert_price</p>";
            echo "<p>$advert_description</p>";

            $query = "select * from cities where city_id=$advert_city limit 1";
            $result_city = mysqli_query($con, $query);
            $city = mysqli_fetch_assoc($result_city);
            $city = $city['city_name'];
            echo "<p>$city</p>";
            echo "<form method='post'>
            <button type='submit' class='btn btn-danger' name='delete_ad_id' value=$advert_id>Delete</button>";
            echo "<input type='hidden' name='deletepress' value=1>";
            echo "</form>";

            echo "</div>";
            echo "</div></a>";
        }
        ?>
    </div>
</body>

</html>