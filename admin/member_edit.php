<?php
require_once("../function.php");
require_once("../pref_cotegory.php");

if (isset($_POST['member_edit_submit'])) {

    header('Location: member_confirm.php');
    exit();
}

if ($_SESSION['admin_login'] == "") {
    header('Location: login.php');
    exit();
}

if (isset($_GET['member_id'])) {
    $member_id = $_GET['member_id'];
    $_SESSION['edit_display'] = 1;
}


$sql = "SELECT * FROM `members` WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $member_id, PDO::PARAM_STR);
$stmt->execute();
$stmt_result = $stmt->fetch();

$member_sei = $stmt_result['name_sei'];
$member_mei = $stmt_result['name_mei'];
$member_gender = $stmt_result['gender'];
$member_pref = $stmt_result['pref_name'];
$member_address = $stmt_result['address'];
$member_password = $stmt_result['password'];
$member_mail = $stmt_result['email'];

$validation = isset($_SESSION['validation_errors']) ? $_SESSION['validation_errors'] : [];
unset($_SESSION['validation_errors']);

$post_items = isset($_SESSION['input_items']) ? $_SESSION['input_items'] : [];
unset($_SESSION['input_items']);
?>

<?php 
require_once("utilities/member_display.php");
?>