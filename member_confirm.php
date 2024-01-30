<?php
require_once("function.php");
require_once("pref_cotegory.php");

if (isset($_POST['member_confirm_submit'])) {
    header('Location: member_complete.php');
    exit();
} elseif (isset($_POST['member_confirm_back_submit'])) {
    header('Location: member_regist.php');
    exit();
}

$name = "";
$gender = "";
$address_pref = "";
$address = "";

if (isset($_SESSION['first_name']) && isset($_SESSION['second_name'])) {
    $name = $_SESSION['first_name'].$_SESSION['second_name'];
}

if (isset($_SESSION['pref']) && isset($_SESSION['address'])) {
    foreach ($prefCotegory as $pref) {
        if ($_SESSION['pref'] == $pref['value']) {
            $address_pref = $pref['value'];
        }
    }
    $address = $address_pref.$_SESSION['address'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>福岡直斗　課題</title>
</head>

<body>
    <main>
        <div class="container">
            <h1 class="main_title">会員情報確認画面</h1>
            <div class="confirm_display">
                <div class="confirm_items">
                    <p class="sub_title">氏名</p>
                    <p class="confirm_item_contents"><?php echo $name ?></p>
                </div>
                <div class="confirm_items">
                    <p class="sub_title">性別</p>
                    <p class="confirm_item_contents"><?php echo $_SESSION['gender'] ?></p>
                </div>
                <div class="confirm_items">
                    <p class="sub_title">住所</p>
                    <p class="confirm_item_contents"><?php echo $address ?></p>
                </div>
                <div class="confirm_items">
                    <p class="sub_title">パスワード</p>
                    <p class="confirm_item_contents">セキュリティのため非表示</p>
                </div>
                <div class="confirm_items">
                    <p class="sub_title">メールアドレス</p>
                    <p class="confirm_item_contents"><?php echo $_SESSION['mail'] ?></p>
                </div>
            </div>

            <form action="" method="post">
                <div class="member_confirm_btn">
                    <input type="submit" name="member_confirm_submit" value="登録完了" class="member_confirm_submit">
                </div>
                <div class="member_confirm_btn">
                    <input type="submit" name="member_confirm_back_submit" value="前に戻る" class="member_confirm_back_submit">
                </div>
            </form>
        </div>
    </main>
</body>

</html>