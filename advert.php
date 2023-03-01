<?php
session_start();
include("connection.php");
include("functions.php");
$advert_id = $_GET['advert'];
$query = "select * from cities,users,advertisements,conditions where  advertisements.city_id=cities.city_id and advertisements.owner_id=users.id and advertisements.advert_id=$advert_id limit 1";
$result = mysqli_query($con, $query);
$ad = mysqli_fetch_assoc($result);
$ad_title = $ad['title'];
$query = "select * from advertisements where ";
$user_data = check_login($con);

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $user_data = check_login_and_redirect($con);
    $user_id = $user_data['id'];
    $price = $_POST['offer_price'];
    $status = 'pending';

    $query = "insert into offers (user_offer_id,advert_id,offer_price,status) values ('$user_id','$advert_id','$price','$status')";
    mysqli_query($con, $query);
    echo '<script>alert("Offer Made")</script>';
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $ad_title ?></title>


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

    <?php
    if (isset($_GET['preview'])) {
        echo "<hr><div class='container text-center'><h1 style='font-style:italic'>THIS IS A PREVIEW</h1></div><hr>";
    }
    $query = "select * from cities,users,advertisements,conditions where  advertisements.city_id=cities.city_id and advertisements.owner_id=users.id and advertisements.advert_id=$advert_id and conditions.condition_id=advertisements.condition_id limit 1";
    $result = mysqli_query($con, $query);
    $ad = mysqli_fetch_assoc($result);
    $ad_title = $ad['title'];
    $ad_img = $ad['image_url'];
    $ad_description = $ad['description'];
    $ad_owner = $ad['first_name'];
    $ad_owner_id = $ad['owner_id'];
    $ad_city = $ad['city_name'];
    $ad_condition = $ad['condition_name'];
    $ad_price = format_money($ad['ad_price']);
    $ad_price_no_sym = format_money_no_sym($ad['ad_price']);
    $ad_negotiable = $ad['negotiable'];
    ?>
    <div class="container advert-page vh-100">
        <div class="row text-center">


            <div class="col col-9">
                <?php

                echo "<h1 style='margin-top:1em'>$ad_title</h1>";
                echo "<hr><p style='font-size:x-large;font-weight:bold;color:orange'>$ad_price</p>";
                echo "<hr><div id='ad_carousel' class='carousel slide' data-ride='carousel' data-interval='false'>";
                echo "<div class='carousel-inner'>";


                $query = "select * from images where images.advert_id=$advert_id";
                $result_images = mysqli_query($con, $query);
                $images = mysqli_fetch_all($result_images, MYSQLI_ASSOC);
                $counter = 0;
                foreach ($images as $key => $image) {
                    $image_url = $image['image_url'];
                    $counter = $counter + 1;
                    if ($counter == 1) {
                        echo "<div class='carousel-item active'><img style='margin:auto' class='d-block h-20' src='$image_url' alt='First slide'></div>";
                    } else {
                        echo "<div class='carousel-item'><img style='margin:auto'  class='d-block h-20' src='$image_url' alt='Second slide'></div>";
                    }
                }
                if (count($images) == '0') {
                    echo "<div class='carousel-item active'><img style='margin:auto' class='d-block h-20' src='https://via.placeholder.com/250x250' alt='First slide'></div>";
                }
                echo "</div>";

                mysqli_free_result($result_images);

                if ($counter > 1) {
                    echo "<a class='carousel-control-prev' href='#ad_carousel' role='button' data-slide='prev'>";
                    echo "<span class='carousel-control-prev-icon' aria-hidden='true'></span>";
                    echo " <span class='sr-only'>Previous</span></a>";

                    echo "<a class='carousel-control-next' href='#ad_carousel' role='button' data-slide='next'>";
                    echo "<span class='carousel-control-next-icon' aria-hidden='true'></span>";
                    echo " <span class='sr-only'>Next</span></a>";
                }
                echo "</div>";


                echo "<hr><p>$ad_condition</p>";

                mysqli_free_result($result);
                if ($ad_negotiable) {
                    echo "<label for='offer_price'>Price is negotiable</label>";
                } else {
                    echo "<label for='offer_price'>Price is not negotiable</label>";
                }
                echo "<hr><p>$ad_description</p>";
                ?>
            </div>
            <div class="col col-3">

                <div class="ad-profile-info w-100">
                    <h3> <?php
                            echo "Seller:<a href=public_profile.php?user=$ad_owner_id>$ad_owner</a>";
                            ?></h3>
                    <p>Location: <?php echo $ad_city ?></p>
                </div>
                <div class="ad-profile-info w-100" style="margin-top: 4em;">
                    <h3>Make an offer</h3>
                    <?php
                    echo "<form method='post'>";
                    if (isset($user_data)) {
                        if ($ad_negotiable) {

                            echo "R <input class='money' type='number' min='0' step='any' name='offer_price' value='$ad_price_no_sym'/>";
                        } else {

                            echo "R <input class='money' type='number' min='0' step='any' name='offer_price' value='$ad_price_no_sym' readonly/>";
                        }
                        if ($ad_owner_id == $user_data['id']) {
                            echo "<input type='submit' value='Make an offer' class='btn btn-secondary w-100' disabled><br>";
                        } else {
                            echo "<input type='submit' value='Make an offer' class='btn btn-secondary w-100'><br>";
                        }
                    } else {
                        echo "<p><a href='login.php?advert=$advert_id'>Login</a> to make an offer</p>";
                    }


                    echo "</form>";
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>