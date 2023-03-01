<?php
include("connection.php");
$approve = $_GET['approve'];
$offer_id = $_GET['rel'];

if ($approve) {
    $query = "update offers set status='approved' where offer_id=$offer_id";
} else {
    $query = "update offers set status='rejected' where offer_id=$offer_id";
}

if (mysqli_query($con, $query)) {
    echo "<script>alert('Offer accepted');
            window.location.href='messages.php';
        </script>";
} else {
    echo "<script>alert('Something went wrong... :(');
            window.location.href='messages.php';
        </script>";
}
