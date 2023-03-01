<?php
session_start();
include("connection.php");
include("functions.php");
$user_id = $_GET['user'];
$query = "select * from users where id=$user_id limit 1";
$result = mysqli_query($con, $query);
$public_user_data = mysqli_fetch_assoc($result);
mysqli_free_result($result);
$ads = 'active';
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
    <link rel="stylesheet" href="./styles/styles.css?v=<?php echo time(); ?>">
</head>

<body style="  overflow: hidden">
    <div style="height: 100vh;">
        <?php
        include("nav.php");
        ?>

        <div class="banner">
            <h1><?php echo $public_user_data['first_name'] ?>'s Profile</h1>
        </div>


        <div class="row user-info">
            <div class="col col-3 w-100">
                <ul class="list-group " style="margin-top: 3em;">
                    <li class="list-group-item ">
                        <a href="public_profile.php?ads=active&user=<?php echo $user_id ?>">Active ads</a>
                    </li>
                    <li class="list-group-item ">
                        <a href="public_profile.php?ads=sold&user=<?php echo $user_id ?>">Sold Items</a>
                    </li>

                    <li class="list-group-item ">
                        <span>Name: </span><?php echo $public_user_data['first_name'] ?>
                    </li>
                    <li class="list-group-item ">
                        <span>Surame: </span><?php echo $public_user_data['last_name'] ?>
                    </li>
                    <li class="list-group-item ">
                        <?php
                        $query = "select * from cities where city_id=" . $public_user_data['city_id'] . " limit 1";
                        $result = mysqli_query($con, $query);
                        $city = mysqli_fetch_assoc($result);
                        $city_name = $city['city_name'];
                        mysqli_free_result($result);
                        ?>
                        <span>City: </span><?php echo  $city_name ?>
                    </li>
                </ul>
            </div>
            <div class="col col-9 w-100" style="overflow:scroll; height: 80vh;">
                <?php

                if (isset($_GET['ads'])) {
                    $ads = $_GET['ads'];
                }
                if ($ads != 'history') {

                    $query = "select * from advertisements where owner_id=" . $public_user_data['id'];

                    if ($ads == 'active') {
                        $filter_string = " and status='approved'";
                    } elseif ($ads == 'pending') {
                        $filter_string = " and status='pending'";
                    } elseif ($ads == 'sold') {
                        $filter_string = " and status='sold'";
                    } else {
                        $filter_string = " and status='approved'";
                    }

                    $query = $query . $filter_string;
                    $ads = ucfirst($ads);
                    echo "<h3 style='margin-top: 1em'>$ads advertisements</h3>";
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
                        echo "<div class='row advert-profile-item'>";

                        echo "<div class='col col-3'>";

                        if ($result_img && mysqli_num_rows($result_img) > 0) {
                            $image_url = $image_url['image_url'];
                            echo "<img src='$image_url'/>";
                        } else {
                            echo "<img src='https://via.placeholder.com/250x250'>";
                        }

                        echo "</div><div class='col col-7'>";

                        mysqli_free_result($result_img);
                        echo "<h4>$advert_title</h4>";

                        echo "<p class='ad-list-price'>$advert_price</p>";
                        echo "<p>$advert_description</p>";

                        $query = "select * from cities where city_id=$advert_city limit 1";
                        $result_city = mysqli_query($con, $query);
                        $city = mysqli_fetch_assoc($result_city);
                        $city = $city['city_name'];
                        echo "<p>$city</p>";
                        echo "</div>";
                        echo "</div></a>";
                    }
                    mysqli_free_result($result);
                } elseif ($ads == 'history') {

                    echo "<h3 style='margin-top: 1em'>Sales</h3>";
                    $user_id = $public_user_data['id'];
                    $query = "select title,selling_price from sales,advertisements,transactions where sales.user_id=$user_id and transactions.sale_id=sales.sale_id and advertisements.advert_id=transactions.advert_id";
                    $result = mysqli_query($con, $query);
                    $sales = mysqli_fetch_all($result, MYSQLI_ASSOC);
                ?>
                    <table class="table" style="margin-top: 3em;">
                        <thead>
                            <tr>
                                <th scope="col">Item</th>
                                <th scope="col">Price</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            $running_sum = 0;
                            foreach ($sales as $key => $sale) {
                                $sale_title = $sale['title'];
                                $selling_price = $sale['selling_price'];
                                $sell_price_display = "+" . format_money($selling_price);
                                echo "<tr><td>$sale_title</td>";
                                echo "<td>$sell_price_display</td></tr>";
                                $running_sum = $running_sum + $selling_price;
                            }
                            echo "<tr><td style='font-weight:bold; font-style:italic'>Total</td>";
                            $running_sum = format_money($running_sum);
                            echo "<td style='font-weight:bold; font-style:italic'>$running_sum</td></tr>";
                            mysqli_free_result($result);
                            ?>
                        </tbody>
                    </table>

                    <?php
                    echo "<h3 style='margin-top: 1em'>Purchases</h3>";
                    $user_id = $public_user_data['id'];
                    $query = "select title,purchase_total from purchases,advertisements,transactions where purchases.user_id=$user_id and transactions.purchase_id=purchases.purchase_id and advertisements.advert_id=transactions.advert_id";
                    $result = mysqli_query($con, $query);
                    $purchases = mysqli_fetch_all($result, MYSQLI_ASSOC);
                    ?>

                    <table class="table" style="margin-top: 3em;">
                        <thead>
                            <tr>
                                <th scope="col">Item</th>
                                <th scope="col">Price</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            $running_sum = 0;
                            foreach ($purchases as $key => $purchase) {
                                $purchase_title = $purchase['title'];
                                $purchase_price = $purchase['purchase_total'];
                                $purchase_price_display = "-" . format_money($purchase_price);
                                echo "<tr><td>$purchase_title</td>";
                                echo "<td>$purchase_price_display</td></tr>";
                                $running_sum = $running_sum + $purchase_price;
                            }
                            echo "<tr><td style='font-weight:bold; font-style:italic'>Total</td>";
                            $running_sum = format_money($running_sum);
                            echo "<td style='font-weight:bold; font-style:italic'>-$running_sum</td></tr>";
                            mysqli_free_result($result);
                            ?>
                        </tbody>
                    </table>
                <?php } ?>

            </div>
        </div>
    </div>
</body>


</html>