<?php
session_start();
include("connection.php");
include("functions.php");
$user_data = check_login_and_redirect($con);

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['ad'])) {
    //something was posted
    $title = $_POST['ad_title'];
    $description = $_POST['ad_description'];
    $category = $_POST['ad_category'];
    $condition = $_POST['ad_condition'];
    $city = $_POST['ad_city'];
    $price = $_POST['ad_price'];
    $negotiable = (isset($_POST['ad_price_negotiable'])) ? 1 : 0;
    $status = 'pending';
    $image_url = NULL;
    $post = 1;
    $ad_user_id = $user_data['id'];

    if ($post) {
        //save to db
        $query = "insert into advertisements (title,description,ad_price,negotiable,category_id,city_id,status,owner_id,condition_id) values ('$title','$description','$price','$negotiable','$category','$city','$status','$ad_user_id','$condition')";
        mysqli_query($con, $query);
        $advert_id = mysqli_insert_id($con);

        echo '<script>alert("Ad successfully created")</script>';
    } else {
        echo "Please enter some valid information!";
    }

    $error = array();
    $allowed_exs = array("jpg", "jpeg", "png", "avif", "webp");
    foreach ($_FILES['ad_image']['tmp_name'] as $key => $tmp_name) {

        $maxDim = 250;
        $img_tmp_name = $_FILES['ad_image']['tmp_name'][$key];
        list($width, $height) = getimagesize($img_tmp_name);

        if ($width > $maxDim || $height > $maxDim) {
            $target_filename = $img_tmp_name;
            $ratio = $width / $height;
            if ($ratio > 1) {
                $new_width = $maxDim;
                $new_height = $maxDim / $ratio;
            } else {
                $new_width = $maxDim * $ratio;
                $new_height = $maxDim;
            }
            $src = imagecreatefromstring(file_get_contents($img_tmp_name));
            $dst = imagecreatetruecolor($new_width, $new_height);

            imagecopyresampled($dst, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
            imagedestroy($src);
            imagepng($dst, $img_tmp_name, 9, PNG_ALL_FILTERS); // adjust format as needed
            imagedestroy($dst);
        }

        $img_name = $_FILES['ad_image']['name'][$key];
        $img_size = $_FILES['ad_image']['size'][$key];
        $img_tmp_name = $_FILES['ad_image']['tmp_name'][$key];
        $img_error = $_FILES['ad_image']['error'][$key];

        if ($img_error === 0) {

            //        $error_msg =
            //           "Sorry, image's resolution is too high. It must be less than 1024x1024 for: " . $img_name;
            //    echo "<script>alert('$error_msg')</script>";

            $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
            $img_ex_lc = strtolower($img_ex);

            if (in_array($img_ex_lc, $allowed_exs)) {
                $img_new_name = uniqid('IMG-', true) . '.' . $img_ex_lc;
                $img_upload_path = 'uploads/' . $img_new_name;
                move_uploaded_file($img_tmp_name, $img_upload_path);

                // INTO DB
                $image_url = $img_upload_path;
                $query = "insert into images (image_url,advert_id) values ('$image_url','$advert_id')";
                mysqli_query($con, $query);
            } else {
                $error_msg = "File type not allowed for: " . $img_name;
                echo "<script>alert('$error_msg')</script>";
                $post = 0;
            }
        } else {
            $error_msg =
                "Unknown error occurred for:" . $img_name;
            echo "<script>alert('$error_msg')</script>";
            $post = 0;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create ad</title>
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
    <div class="row vh-100 justify-content-center align-items-center text-center">
        <form method="post" class="w-50" id="ad_create_form" enctype="multipart/form-data">
            <div class="form-group">
                <label for="ad_title">Title</label>
                <input type="text" class="form-control" name="ad_title">
            </div>
            <div class="form-group">
                <label for="ad_description">Description</label>
                <textarea name="ad_description" class="form-control"></textarea>
            </div>

            <div class="form-group">
                <label for="ad_category">Category</label>
                <?php
                $query = "select * from categories";
                $result = mysqli_query($con, $query);
                $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
                echo '<select name="ad_category" class="form-control">';
                foreach ($categories as $key => $category) {
                    echo '<option value="' . htmlspecialchars($category['category_id']) . '">'
                        . htmlspecialchars($category['category_name'])
                        . '</option>';
                }
                echo '</select>';
                mysqli_free_result($result);
                ?>
            </div>
            <div class="form-group">
                <label for="ad_condition">Condition</label>
                <?php
                $query = "select * from conditions";
                $result = mysqli_query($con, $query);
                $conditions = mysqli_fetch_all($result, MYSQLI_ASSOC);
                echo '<select name="ad_condition" class="form-control">';
                foreach ($conditions as $key => $condition) {
                    echo '<option value="' . htmlspecialchars($condition['condition_id']) . '">'
                        . htmlspecialchars($condition['condition_name'])
                        . '</option>';
                }
                echo '</select>';
                mysqli_free_result($result);
                ?>
            </div>
            <div class="form-group">
                <label for="ad_city">City</label>
                <?php
                $query = "select * from cities";
                $result = mysqli_query($con, $query);
                $cities = mysqli_fetch_all($result, MYSQLI_ASSOC);
                echo '<select name="ad_city" class="form-control">';
                foreach ($cities as $key => $city) {
                    echo '<option value="' . htmlspecialchars($city['city_id']) . '">'
                        . htmlspecialchars($city['city_name'])
                        . '</option>';
                }
                echo '</select>';
                mysqli_free_result($result);
                ?>
            </div>
            <div class="form-group">
                <p>Price</p>
                <label for="ad_price">R</label>
                <input type="number" min="0" step="any" name="ad_price" />
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="ad_price_negotiable">
                    <label class="form-check-label" for="ad_price_negotiable">Negotiable</label>
                </div>
            </div>
            <div class="form-group">
                <input type="file" name="ad_image[]" multiple>
            </div>
            <?php echo "<input type='hidden' name='ad' value=1>"; ?>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</body>

</html>