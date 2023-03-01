<?php
include("connection.php");
$approve = $_GET['approve'];
$advert_id = $_GET['ad'];
if ($approve == 1) {
    $query = "update advertisements set status='approved' where advert_id=$advert_id";
} else {
    $query = "update advertisements set status='rejected' where advert_id=$advert_id";
}

if (mysqli_query($con, $query)) {
    if ($approve == 1) {
        echo "<script>alert('Advertisement approved');";
    } else {
        echo "<script>alert('Advertisement rejected');";
    }
    echo "window.location.href='reviewads.php';</script>";
} else {
    echo "<script>alert('Error updating record');
            window.location.href='reviewads.php';
        </script>";
}
