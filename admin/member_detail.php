<?php
require_once("../function.php");

if ($_SESSION['admin_login'] == "") {
    header('Location: login.php');
    exit();
}

$member_id = $_GET['member_id'];

$sql = "SELECT * FROM `members` WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $member_id, PDO::PARAM_STR);
$stmt->execute();
$stmt_result = $stmt->fetch();

$detail_name = $stmt_result['name_sei'] . $stmt_result['name_mei'];
$detail_gender = $stmt_result['gender'];
$detail_address = $stmt_result['pref_name'] . $stmt_result['address'];
$detail_mail = $stmt_result['email'];

if ($detail_gender == 1) {
    $gender = "男性";
} else {
    $gender = "女性";
}

if (isset($_POST['member_delete_submit'])) {
    if (isset($_POST['member_delete_submit'])) {
        date_default_timezone_set('Asia/Tokyo');
        $deleted_at = date('Y:m:d H:i:s');
    
        $delete_sql = "UPDATE members SET deleted_at = :deleted_at WHERE id = :id";
        $delete_stmt = $pdo->prepare($delete_sql);
        $delete_stmt->execute([
            ':id' => $member_id,
            ':deleted_at' => $deleted_at
        ]);
    }
    
    header("Location: member.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>福岡直斗　課題</title>
</head>

<body>
    <header class="admin_header">
        <div class="admin_header_left">
            <h2>会員詳細</h2>
        </div>
        <div class="admin_header_right">
            <button type="button" onclick="location.href='member.php'" class="btn member_back_btn">一覧に戻る</button>
        </div>
    </header>

    <main>
        <div class="container">

            <div class="confirm_display">
                <div class="confirm_items">
                    <p class="sub_title">ID</p>
                    <p class="confirm_item_contents"><?php echo $member_id ?></p>
                </div>
                <div class="confirm_items">
                    <p class="sub_title">氏名</p>
                    <p class="confirm_item_contents"><?php echo $detail_name ?></p>
                </div>
                <div class="confirm_items">
                    <p class="sub_title">性別</p>
                    <p class="confirm_item_contents"><?php echo $gender ?></p>
                </div>
                <div class="confirm_items">
                    <p class="sub_title">住所</p>
                    <p class="confirm_item_contents"><?php echo $detail_address ?></p>
                </div>
                <div class="confirm_items">
                    <p class="sub_title">パスワード</p>
                    <p class="confirm_item_contents">セキュリティのため非表示</p>
                </div>
                <div class="confirm_items">
                    <p class="sub_title">メールアドレス</p>
                    <p class="confirm_item_contents"><?php echo $detail_mail ?></p>
                </div>
            </div>

            <form action="" method="post">
                <div class="member_confirm_btn">
                    <button type="button" onclick="location.href='member_edit.php?member_id=<?php echo $member_id ?>'" class="member_detail_btn">編集</button>
                    <input type="submit" name="member_delete_submit" value="削除" class="member_detail_btn">
                </div>
            </form>

        </div>
    </main>
</body>

</html>