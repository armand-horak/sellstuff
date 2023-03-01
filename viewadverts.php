<?php
session_start();
include("connection.php");
include("functions.php");
if (isset($_GET['category'])) {
    $category_id = $_GET['category'];
}
$user_data = check_login($con);
if (isset($_POST['sort'])) {
    $sorting = $_POST['sort'];
} elseif (isset($_GET['sort'])) {
    $sorting = $_GET['sort'];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View adverts</title>

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
        <?php if (isset($_GET['category'])) {
            if ($_GET['category'] != 'all') { ?>
                <div class="text-center current-category">
                    <?php
                    $query = "select * from categories where category_id = $category_id limit 1";
                    $result = mysqli_query($con, $query);
                    $category_main = mysqli_fetch_assoc($result);
                    $current_category_name = $category_main['category_name'];
                    echo "<h2>$current_category_name</h2>";
                    ?>
                </div>
        <?php }
        } ?>
        <br>
        <form id="form-sort" method="post" action="<?php
                                                    if (isset($_GET['sort_select'])) {
                                                        $query = $_GET;
                                                        // replace parameter(s)
                                                        $query['category'] = $_GET['category'];
                                                        $query['sort'] = $sorting;
                                                        $query['city'] = $_GET['city'];
                                                        // rebuild url
                                                        $query_result = http_build_query($query);
                                                        header("Location: viewadverts.php?$query_result");
                                                    }
                                                    ?>">
            <select class="form-control" name="sort" id="sort-select" onchange="this.form.submit()">
                <option value="mr" <?php if ($sorting == 'mr') : ?>selected="selected" <?php endif; ?>>Sort by Most Recent</option>
                <option value="lh" <?php if ($sorting == 'lh') : ?>selected="selected" <?php endif; ?>>Sort by Price Low to High</option>
                <option value="hl" <?php if ($sorting == 'hl') : ?>selected="selected" <?php endif; ?>>Sort by Price High to Low</option>
            </select>
            <?php echo "<input type='hidden' name='sort_select' value=1>"; ?>
        </form>
        <?php
        if (isset($_GET['category'])) if ($_GET['category'] != 'all') { {
                $query = "select * from categories where category_id != $category_id";
                $result = mysqli_query($con, $query);
                $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
                echo "<div class='row text-center'>";
                foreach ($categories as $key => $category) {
                    $category_name = $category['category_name'];
                    echo "<a style='text-decoration:none;' href='viewadverts.php?sort=mr&category=" . $category['category_id'] . "' class='other-categories' > $category_name</a>";
                }
                echo "</div>";
                mysqli_free_result($result);
            }
        }
        ?>
        <?php
        if (isset($_GET['category'])) {
            if ($_GET['category'] != 'all') {
                $query = "select * from advertisements where category_id='$category_id' and status='approved'";
            } else {
                $query = "select * from advertisements where status='approved'";
            }
            if (isset($_GET['city'])) {
                if ($_GET['city'] != 'all') {
                    $city_filter = $_GET['city'];
                    $query = $query . " and city_id=$city_filter";
                }
            }
            if (isset($_GET['search'])) {
                $search_val = $_GET['search'];
                if ($search_val != '') {
                    $query = $query . " and (title like '%$search_val%' or description like '%$search_val%')";
                }
            }
            if ($sorting == 'hl') {
                $sort_string = " order by ad_price DESC";
            } elseif ($sorting == 'lh') {
                $sort_string = " order by ad_price ASC";
            } elseif ($sorting == 'mr') {
                $sort_string = " order by ad_date DESC";
            } else {
                $sort_string = " order by ad_date DESC";
            }

            $query = $query . $sort_string;
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
                echo "</div>";
                echo "</div></a>";
            }
        }
        //mysqli_free_result($result);
        ?>
    </div>

</body>

</html>