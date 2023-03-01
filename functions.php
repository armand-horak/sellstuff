<?php

function check_login($con)
{
    if (isset($_SESSION['user_id'])) {
        $id = $_SESSION['user_id'];
        $query = "select * from users where user_id = '$id' limit 1";
        $result = mysqli_query($con, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);
            return $user_data;
        }
    }

    // redirect to login
    //header("Location: login.php");
    //die;
}
function check_login_and_redirect($con)
{
    if (isset($_SESSION['user_id'])) {
        $id = $_SESSION['user_id'];
        $query = "select * from users where user_id = '$id' limit 1";
        $result = mysqli_query($con, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);
            return $user_data;
        }
    }

    // redirect to login
    header("Location: login.php");
    die;
}
function random_num($length)
{
    $text = "";
    if ($length < 5) {
        $length = 5;
    }
    $len = rand(4, $length);

    for ($i = 0; $i < $len; $i++) {
        $text .= rand(0, 9);
    }

    return $text;
}

function check_login_and_role($con)
{
    if (isset($_SESSION['user_id'])) {
        $id = $_SESSION['user_id'];
        $query = "select * from users where user_id = '$id' limit 1";
        $result = mysqli_query($con, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);
            if ($user_data['role'] == 'admin' || $user_data['role'] == 'owner') {
                return $user_data;
            } else {
                header("Location: index.php");
                die;
            }
        }
    }

    // redirect to login
    header("Location: login.php");
    die;
}

function format_money($value)
{
    return 'R' . number_format((float)$value, 2, '.', '');
}
function format_money_no_sym($value)
{
    return number_format((float)$value, 2, '.', '');
}
