<?php
session_start();
include("connection.php");
include("functions.php");
$offer = $_GET['offer'];
$user_data = check_login_and_redirect($con);

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['pay'])) {
    $advert_id = $_POST['advert_id'];
    $delivery = $_POST['delivery'];
    $commission = $_POST['commission'];
    $sale_price = $_POST['sellingprice'];
    $total = $sale_price + $commission + $delivery;
    $user_name = $user_data['user_name'];
    $user_actual_password = $user_data['password'];
    $user_id = $user_data['id'];
    $password = $_POST['password'];
    $user_balance = $user_data['user_balance'];
    if ($user_actual_password == $password) {
        if ($user_balance >= $total) {
            $query = "select advertisements.owner_id from offers,advertisements where offers.offer_id=$offer and offers.advert_id = advertisements.advert_id and offers.advert_id=$advert_id limit 1";
            $result = mysqli_query($con, $query);
            $seller = mysqli_fetch_assoc($result);
            $seller_id = $seller['owner_id'];
            mysqli_free_result($result);

            $query_sell = "insert into sales (user_id,selling_price,delivery_price,commission) values ('$seller_id','$sale_price','$delivery','$commission')";
            $query_purchase = "insert into purchases (user_id,purchase_total) values ('$user_id','$total')";

            mysqli_query($con, $query_sell);
            $sale_id = mysqli_insert_id($con);
            mysqli_query($con, $query_purchase);
            $purchase_id = mysqli_insert_id($con);
            $query_transaction = "insert into transactions (advert_id,purchase_id,sale_id) values ('$advert_id','$purchase_id','$sale_id')";
            mysqli_query($con, $query_transaction);

            $query_buyer_balance = "update users set user_balance=user_balance-$total where id=$user_id";
            $query_seller_balance = "update users set user_balance=user_balance+$sale_price where id=$seller_id";
            $query_admin_balance =  "update users set user_balance=user_balance+$commission where role='owner'";
            $query_ad_status =  "update advertisements set status='sold' where advert_id=$advert_id";
            mysqli_query($con, $query_buyer_balance);
            mysqli_query($con, $query_seller_balance);
            mysqli_query($con, $query_admin_balance);
            mysqli_query($con, $query_ad_status);
            $query_offer = "update offers set status='completed' where offer_id=$offer";
            mysqli_query($con, $query_offer);
            header("Location: private_profile.php?ads=history&paymsg=1");
            die;
        } else {
            header("Location: private_profile.php?ads=active&paymsg=0");
            die;
        }
    } else {
        echo '<script type ="text/JavaScript">';
        echo 'alert("The password you have entered is incorrect")';
        echo '</script>';
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
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
                <h1>Confirm Payment</h1>
                <?php
                $query = "select * from offers,advertisements where offers.offer_id = $offer and advertisements.advert_id = offers.advert_id";
                $result = mysqli_query($con, $query);
                $advert = mysqli_fetch_assoc($result);
                $advert_title = $advert['title'];
                $advert_id = $advert['advert_id'];
                $offer_price = format_money_no_sym($advert['offer_price']);
                mysqli_free_result($result);
                $delivery = 100;
                $commission = ($offer_price + $delivery) * 0.045;
                if($commission > 1000){
                    $commission = 1000;
                }
                $sale_price = $offer_price;
                $total = format_money($offer_price + $commission + $delivery);
                $offer_price = format_money($offer_price);
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
                        echo "<tr><td>$advert_title</td>";
                        echo "<td>$offer_price</td></tr>";

                        echo "<tr><td>Delivery</td>";
                        echo "<td>R$delivery.00</td></tr>";

                        echo "<tr><td>Admin Fee</td>";
                        echo "<td>4.5%</td></tr>";

                        echo "<tr><td style='font-weight:bold; font-style:italic'>Total</td>";
                        echo "<td style='font-weight:bold; font-style:italic'>$total</td></tr>";
                        ?>
                    </tbody>
                </table>
                <br>
                <?php
                echo "<input type='hidden' name='sellingprice' value=";
                echo htmlspecialchars($sale_price);
                echo ">";
                echo "<input type='hidden' name='commission' value=";
                echo htmlspecialchars($commission);
                echo ">";
                echo "<input type='hidden' name='delivery' value=";
                echo htmlspecialchars($delivery);
                echo ">";
                echo "<input type='hidden' name='advert_id' value=";
                echo htmlspecialchars($advert_id);
                echo ">";
                echo "<input type='hidden' name='pay' value=1>";
                ?>
                <input type="password" name="password" placeholder="Password" class="w-100"><br><br>
                <input type="submit" value="Click to confirm payment" class="btn btn-primary w-100"><br><br>
                <a href="index.php" class="btn btn-danger w-100">Cancel</a>
            </form>
        </div>
    </div>
</body>

</html>