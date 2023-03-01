<?php
if (isset($_POST['nav'])) {
    $query = $_GET;
    // replace parameter(s)
    $query['category'] = $_POST['category'];
    $query['sort'] = 'mr';
    $query['nav'] = 1;
    $query['city'] = $_POST['city'];
    $query['search'] = $_POST['search'];
    // rebuild url
    $query_result = http_build_query($query);
    header("Location: viewadverts.php?$query_result");
}

?>
<nav class="navbar navbar-expand-lg" style="background-color: orange;">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php" style='color:black'>SellStuff.com</a>
        <div class="navbar-nav">

            <?php if (isset($user_data)) {
                echo "<a style='color:black' class='nav-item nav-link' href='private_profile.php?ads=active'>";
                echo "Welcome, ";
                echo $user_data['first_name'];
                echo "</a>";

                echo "<a style='color:black' href='messages.php?rel=" . $user_data["user_id"] . "' class='nav-item nav-link'>Messages</a>";

                echo "<a style='color:black' class='nav-item nav-link' href='logout.php'>Logout</a>";
            } else {
                echo " <a style='color:black' class='nav-item nav-link'  href='login.php'>Login</a>";
                echo " <a style='color:black' class='nav-item nav-link'  href='signup.php'>Sign up</a>";
            }
            ?>
        </div>
        <?php
        if (isset($user_data)) {
            $user_role = $user_data['role'];
            if ($user_role === 'admin' || $user_role === 'owner') {

                echo "<a class='nav-item nav-link' href='adminmenu.php' style='color:black'>Actions</a>";
            }
        }
        ?>
        <form class="form-inline mx-auto" method="post">
            <?php
            echo "<input type='hidden' name='nav' value=1>";
            if (isset($_GET['category'])) {
                echo "<input type='hidden' name='category' value=";
                echo htmlspecialchars($_GET['category']);
                echo ">";
            }
            if (isset($_GET['city'])) {
                echo "<input type='hidden' name='city' value=";
                echo htmlspecialchars($_GET['city']);
                echo ">";
            }
            if (isset($_GET['sort'])) {
                echo "<input type='hidden' name='sort' value=";
                echo htmlspecialchars($_GET['sort']);
                echo ">";
            } else {
                //  echo "<input type='hidden' name='sort' value='mr'>";
            }

            ?>
            <div class=" form-group">
                <?php
                $query = "select * from cities";
                $result = mysqli_query($con, $query);
                $cities = mysqli_fetch_all($result, MYSQLI_ASSOC);
                echo '<select name="city" class="form-control">';
                echo "<option value='all'>All cities</option>";
                foreach ($cities as $key => $city) {
                    echo '<option value="' . htmlspecialchars($city['city_id']) . '"';
                    if (isset($_GET['city'])) {
                        if ($_GET['city'] == htmlspecialchars($city['city_id'])) {
                            echo 'selected="selected"';
                        }
                    }
                    echo '>';
                    echo htmlspecialchars($city['city_name']);
                    echo '</option>';
                }
                echo '</select>';
                mysqli_free_result($result);
                ?>
            </div>
            <div class=" form-group">
                <?php
                $query = "select * from categories";
                $result = mysqli_query($con, $query);
                $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
                echo '<select name="category" class="form-control">';
                echo "<option value='all'>All categories</option>";
                foreach ($categories as $key => $category) {
                    echo '<option value="' . htmlspecialchars($category['category_id']) . '"';
                    if (isset($_GET['category'])) {
                        if ($_GET['category'] == htmlspecialchars($category['category_id'])) {
                            echo 'selected="selected"';
                        }
                    }
                    echo '>';
                    echo htmlspecialchars($category['category_name']);
                    echo '</option>';
                }
                echo '</select>';
                mysqli_free_result($result);
                ?>
            </div>
            <input class="form-control" type="search" placeholder="Search" aria-label="Search" name="search">
            <button class="btn btn-secondary" type="submit">Search</button>
        </form>
        <a class="nav-item nav-link btn btn-primary" href="adcreation.php" style="font-weight:bold">Create AD<span class="sr-only">(current)</span></a>
        <div class="nav-item nav-link" style="font-weight:bold">
            <?php
            if (isset($user_data)) {
                echo format_money($user_data['user_balance']);
            }
            ?>
        </div>
    </div>
</nav>