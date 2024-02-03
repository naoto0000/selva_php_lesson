<?php
require_once("function.php");
require_once("pref_cotegory.php");

try {
    $mail_sql = "SELECT email FROM members";
    $mail_stmt = $pdo->prepare($mail_sql);
    $mail_stmt->execute();
} catch (PDOException $e) {
    echo $e->getMessage();
}
// 取得できたデータを変数に入れておく
$mail_match_row = $mail_stmt->fetchAll(PDO::FETCH_ASSOC);

require_once("validation.php");

validate($_POST);

$_SESSION['input_items']['first_name'] = htmlspecialchars($_POST['first_name'], ENT_QUOTES, 'UTF-8');
$_SESSION['input_items']['second_name'] = htmlspecialchars($_POST['second_name'], ENT_QUOTES, 'UTF-8');
$_SESSION['input_items']['gender'] = $_POST['gender'];
$_SESSION['input_items']['pref'] = $_POST['pref'];
$_SESSION['input_items']['address'] = htmlspecialchars($_POST['address'], ENT_QUOTES, 'UTF-8');
$_SESSION['input_items']['password'] = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');
$_SESSION['input_items']['pass_conf'] = htmlspecialchars($_POST['pass_conf'], ENT_QUOTES, 'UTF-8');
$_SESSION['input_items']['mail'] = htmlspecialchars($_POST['mail'], ENT_QUOTES, 'UTF-8');

if (isset($_SESSION['validation_errors']) && is_array($_SESSION['validation_errors']) && count($_SESSION['validation_errors']) === 0) {

    if (isset($_POST['member_confirm_submit'])) {

        header('Location: member_complete.php');
        exit();
    } 
    // elseif (isset($_POST['member_confirm_back_submit'])) {
    //     header('Location: member_regist.php');
    //     exit();
    // }
} else {
    header('Location: member_regist.php');
    exit();
}

$name = "";
$gender = "";
$address_pref = "";
$address = "";

if (isset($_SESSION['input_items']['first_name']) && isset($_SESSION['input_items']['second_name'])) {
    $name = $_SESSION['input_items']['first_name'] . $_SESSION['input_items']['second_name'];
}

if (isset($_SESSION['input_items']['pref']) && isset($_SESSION['input_items']['address'])) {
    foreach ($prefCotegory as $pref) {
        if ($_SESSION['input_items']['pref'] == $pref['value']) {
            $address_pref = $pref['value'];
        }
    }
    $address = $address_pref . $_SESSION['input_items']['address'];
}

//トークンをセッション変数にセット
$_SESSION["token"] = $token = mt_rand();

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
                    <p class="confirm_item_contents"><?php echo $_SESSION['input_items']['gender'] ?></p>
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
                    <p class="confirm_item_contents"><?php echo $_SESSION['input_items']['mail'] ?></p>
                </div>
            </div>

            <form action="member_complete.php" method="post">
                <input type="hidden" name="token" value="<?php echo $token; ?>">

                <div class="member_confirm_btn">
                    <input type="submit" name="member_confirm_submit" value="登録完了" class="member_confirm_submit">
                </div>
                <div class="member_confirm_btn">
                    <button type="button" onclick="location.href='member_regist.php'" class="btn">前に戻る</button>
                    <!-- <input type="submit" name="member_confirm_back_submit" value="前に戻る" class="member_confirm_back_submit"> -->
                </div>
            </form>
        </div>
    </main>
</body>

</html>