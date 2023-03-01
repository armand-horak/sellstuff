<?php
session_start();
include("connection.php");
include("functions.php");
$user_data = check_login_and_redirect($con);
$ads = 'active';
if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
    if ($msg == 0) {
        echo "<script>alert('You do not have enough money to withdraw that amount');</script>";
    } else if ($msg == 1) {
        echo "<script>alert('Withdrawal completed.');</script>";
    } else if ($msg == 2) {
        echo "<script>alert('Money uploaded successfully.');</script>";
    }
}
if (isset($_GET['paymsg'])) {
    $msg = $_GET['paymsg'];
    if ($msg == 0) {
        echo "<script>alert('You need to upload money more money');</script>";
    } else if ($msg == 1) {
        echo "<script>alert('Transaction completed. Your item will be delivered to your home address. Thank you for using SellStuff.com');</script>";
    }
}
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['cbx'])) {
    $advert_id = $_POST['advert_id'];
    if ($_POST['cbx'] == 1) {
        $query = "update advertisements set status='hidden' where advert_id=$advert_id";
    } else {
        $query = "update advertisements set status='approved' where advert_id=$advert_id";
    }
    mysqli_query($con, $query);
    header("Location: private_profile.php?ads=active");
}
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['money'])) {
    $action = $_POST['action'];
    $amount = $_POST['amount'];
    if ($action == 'withdraw') {
        echo "withdraw R" . $amount;
        if (!(floatval($user_data['user_balance']) - $amount < 0)) {
            $query = "update users set user_balance=user_balance-$amount where id=" . $user_data['id'];
            $msg = 1;
            mysqli_query($con, $query);
        } else {
            $msg = 0;
        }
    } else if ($action == 'upload') {
        echo "upload R" . $amount;
        $query = "update users set user_balance=user_balance+$amount where id=" . $user_data['id'];
        $msg = 2;
        mysqli_query($con, $query);
    }
    header("Location: private_profile.php?ads=active&msg=$msg");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="./styles/styles.css?v=<?php echo time(); ?>">
</head>

<body>
    <div style="height: 100vh;">
        <?php
        include("nav.php");
        ?>

        <div class="banner">
            <h1>Your Profile</h1>
        </div>


        <div class="row user-info">
            <div class="col col-3 w-100">
                <ul class="list-group " style="margin-top: 3em;">
                    <li class="list-group-item text-center">
                        <form method="post">
                            <button class="btn btn-success w-30" type="submit" name="action" value="upload">Upload Money</button>
                            <button class="btn btn-secondary w-30" type="submit" name="action" value="withdraw">Withdraw Money</button>
                            <label for="amount" style="margin-top: 0.5em;">Amount in Rands</label>
                            <input name="amount" type="number" class="w-100" type="number" min='0' step="any" value="0.00">
                            <input type='hidden' name='money' value=1>
                        </form>
                    </li>
                    <li class="list-group-item ">
                        <a href="private_profile.php?ads=history">Transaction History</a>
                    </li>
                    <li class="list-group-item ">
                        <a href="private_profile.php?ads=active">Approved Ads</a>
                    </li>
                    <li class="list-group-item ">
                        <a href="private_profile.php?ads=pending">Pending ads</a>
                    </li>
                    <li class="list-group-item ">
                        <a href="private_profile.php?ads=sold">Sold Items</a>
                    </li>
                    <li class="list-group-item ">
                        <a href="private_profile.php?ads=rejected">Rejected Ads</a>
                    </li>
                    <li class="list-group-item ">
                        <span>Name: </span><?php echo $user_data['first_name'] ?>
                    </li>
                    <li class="list-group-item ">
                        <span>Surame: </span><?php echo $user_data['last_name'] ?>
                    </li>
                    <li class="list-group-item ">
                        <span>Email address: </span><?php echo $user_data['email'] ?>
                    </li>
                    <li class="list-group-item ">
                        <?php
                        $query = "select * from cities where city_id=" . $user_data['city_id'] . " limit 1";
                        $result = mysqli_query($con, $query);
                        $city = mysqli_fetch_assoc($result);
                        $city_name = $city['city_name'];
                        mysqli_free_result($result);
                        ?>
                        <span>Home address: </span><?php echo $user_data['addressLine1'] . ", " . $city_name . ", " . $user_data['postal_code'] ?>
                    </li>
                </ul>
            </div>
            <div class="col col-9 w-100" style="overflow:scroll; height: 80vh;">
                <?php

                if (isset($_GET['ads'])) {
                    $ads = $_GET['ads'];
                }
                if ($ads != 'history') {

                    $query = "select * from advertisements where owner_id=" . $user_data['id'];

                    if ($ads == 'active') {
                        $filter_string = " and status='approved' || status='hidden'";
                    } elseif ($ads == 'pending') {
                        $filter_string = " and status='pending'";
                    } elseif ($ads == 'rejected') {
                        $filter_string = " and status='rejected'";
                    } elseif ($ads == 'sold') {
                        $filter_string = " and status='sold'";
                    } else {
                        $filter_string = " and status='approved' || status='hidden'";
                    }

                    $query = $query . $filter_string;
                    $ads = ucfirst($ads);
                    echo "<h3 style='margin-top: 1em'>$ads advertisements</h3>";
                    $result = mysqli_query($con, $query);
                    $advertisements = mysqli_fetch_all($result, MYSQLI_ASSOC);
                    foreach ($advertisements as $key => $advert) {
                        $advert_id = $advert['advert_id'];
                        $advert_status = $advert['status'];
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
                        if ($_GET['ads'] == 'active') {

                            echo "<form method='post'>";
                            echo "<div class='form-check'><input onChange='this.form.submit()' class='form-check-input' type='checkbox' value='' ";
                            if ($advert_status == 'approved') {
                                echo "checked";
                            }
                            echo ">
                        <label class='form-check-label' for='flexCheckDefault'>Active
                       
                        </label></div>";
                            echo "<input type='hidden' name='advert_id' value=$advert_id>";
                            echo "<input type='hidden' name='ads' value='active'>";
                            if ($advert_status == 'approved') {
                                echo "<input type='hidden' name='cbx' value=1>";
                            } else if ($advert_status == 'hidden') {
                                echo "<input type='hidden' name='cbx' value=2>";
                            }

                            echo "</form>";
                        }

                        echo "</div>";
                        echo "</div></a>";
                    }
                    mysqli_free_result($result);
                } elseif ($ads == 'history') {
                    if ($user_data['role'] == 'owner') {
                        echo "<h3 style='margin-top: 1em'>Commission</h3>";
                        $user_id = $user_data['id'];
                        $query = "select title,commission from sales,advertisements,transactions where transactions.sale_id = sales.sale_id and advertisements.advert_id = transactions.advert_id";
                        $result = mysqli_query($con, $query);
                        $incomes = mysqli_fetch_all($result, MYSQLI_ASSOC);
                ?>

                        <table class="table" style="margin-top: 3em;">
                            <thead>
                                <tr>
                                    <th scope="col">Item</th>
                                    <th scope="col">Commission</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                $running_sum = 0;
                                foreach ($incomes as $key => $income) {
                                    $income_title = $income['title'];
                                    $income_amount = $income['commission'];
                                    $income_amount_display = "+" . format_money($income_amount);
                                    echo "<tr><td>$income_title</td>";
                                    echo "<td>$income_amount_display</td></tr>";
                                    $running_sum = $running_sum + $income_amount;
                                }
                                echo "<tr><td style='font-weight:bold; font-style:italic'>Total</td>";
                                $running_sum = format_money($running_sum);
                                echo "<td style='font-weight:bold; font-style:italic'>+$running_sum</td></tr>";
                                mysqli_free_result($result);
                                ?>
                            </tbody>
                        </table>
                    <?php
                    }
                    echo "<h3 style='margin-top: 1em'>Sales</h3>";
                    $user_id = $user_data['id'];
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
                    $user_id = $user_data['id'];
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