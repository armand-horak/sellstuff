<?php
session_start();
include("connection.php");
include("functions.php");
$user_data = check_login_and_redirect($con);
if (isset($_GET['payment'])) {

    if ($_GET['payment'] == 'success') {
        echo '<script type ="text/JavaScript">';
        echo 'alert("Payment success")';
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
    <title>My Messages</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous">
    </script>
</head>

<body>
    <?php
    include("nav.php");
    ?>
    <div class="container">
        <div class="text-center">
            <h1 style="margin-top: 1em;">My messages</h1>
        </div>

        <?php
        $user_id = $user_data['id'];
        $query = "select * from offers, advertisements, users where users.id=offers.user_offer_id and offers.advert_id = advertisements.advert_id and advertisements.owner_id='$user_id' and offers.status='pending'";
        $result = mysqli_query($con, $query);
        $offers = mysqli_fetch_all($result, MYSQLI_ASSOC);
        foreach ($offers as $key => $offer) {
            $advert_id = $offer['advert_id'];
            $offer_id = $offer['offer_id'];
            $title = $offer['title'];
            $ad_price = $offer['ad_price'];
            $offer_price = $offer['offer_price'];
            $user_offer = $offer['first_name'];
            echo "<div style='border:solid lightgrey 1px; border-radius: 5px; margin-bottom:1em; padding:1em'>";
            echo "<h5>Type: Pending Offer</h5>";
            echo "<div class='row'>";
            echo "<p class='col col-5'>$user_offer offered R$offer_price for your <a href='advert.php?advert=$advert_id'>$title</a> which you listed for R$ad_price.</p>";
            echo "<div class='col col-2'></div>";
            echo "<a class='col col-2 btn btn-success' style='margin:1em;margin-top:0;' href='handle_offer.php?approve=1&rel=$offer_id'>Accept</a>";
            echo "<a class='col col-2 btn btn-danger' style='margin:1em;margin-top:0;' href='handle_offer.php?approve=0&rel=$offer_id'>Reject</a>";
            echo "</div>";
            echo "</div>";
        }
        mysqli_free_result($result);
        $user_id = $user_data['id'];
        $query = "select * from users,offers,advertisements where users.id=advertisements.owner_id and offers.advert_id = advertisements.advert_id and offers.user_offer_id='$user_id' and offers.status='approved'";
        $result = mysqli_query($con, $query);
        $offers = mysqli_fetch_all($result, MYSQLI_ASSOC);
        foreach ($offers as $key => $offer) {
            $advert_id = $offer['advert_id'];
            $offer_id = $offer['offer_id'];
            $title = $offer['title'];
            $ad_price = $offer['ad_price'];
            $offer_price = $offer['offer_price'];
            $user_offer = $offer['first_name'];
            echo "<div style='border:solid lightgrey 1px; border-radius: 5px; margin-bottom:1em; padding:1em'>";
            echo "<h5>Type: Approved Offer</h5>";
            echo "<div class='row'>";
            echo "<div class='col col-5'><p >$user_offer approved your offer of R$offer_price for their <a href='advert.php?advert=$advert_id'>$title</a></p></div>";
            echo "<a class='col col-2 btn btn-success' style='margin:1em;margin-top:0;' href='payment.php?offer=$offer_id'>Pay</a>";
            echo "<a class='col col-2 btn btn-danger' style='margin:1em;margin-top:0;'>Cancel</a>";
            echo "<div class='col col-2'></div>";
            echo "</div>";
            echo "</div>";
        }
        mysqli_free_result($result);
        $user_id = $user_data['id'];
        $query = "select * from users,offers,advertisements where users.id=advertisements.owner_id and offers.advert_id = advertisements.advert_id and offers.user_offer_id='$user_id' and offers.status='rejected'";
        $result = mysqli_query($con, $query);
        $offers = mysqli_fetch_all($result, MYSQLI_ASSOC);
        foreach ($offers as $key => $offer) {
            $advert_id = $offer['advert_id'];
            $offer_id = $offer['offer_id'];
            $title = $offer['title'];
            $ad_price = $offer['ad_price'];
            $offer_price = $offer['offer_price'];
            $user_offer = $offer['first_name'];
            echo "<div style='border:solid lightgrey 1px; border-radius: 5px; margin-bottom:1em; padding:1em'>";
            echo "<h5>Type: Rejected Offer</h5>";
            echo "<div class='row'>";
            echo "<p class='col col-5'>$user_offer rejected your offer of R$offer_price for their <a href='advert.php?advert=$advert_id'>$title</a></p>";
            echo "<div class='col col-2'></div>";
            echo "</div>";
            echo "</div>";
        }
        mysqli_free_result($result);
        ?>
    </div>

</body>

</html>