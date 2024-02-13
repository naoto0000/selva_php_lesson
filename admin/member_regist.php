<?php
require_once("../function.php");
require_once("../pref_cotegory.php");

if (isset($_POST['member_regi_submit'])) {

    header('Location: member_confirm.php');
    exit();
}

if ($_SESSION['admin_login'] == "") {
    header('Location: login.php');
    exit();
}

$validation = isset($_SESSION['validation_errors']) ? $_SESSION['validation_errors'] : [];
unset($_SESSION['validation_errors']);

$post_items = isset($_SESSION['input_items']) ? $_SESSION['input_items'] : [];
unset($_SESSION['input_items']);

?>

<?php 
require_once("utilities/member_display.php");
?>