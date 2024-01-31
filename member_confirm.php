<?php
require_once("function.php");
require_once("pref_cotegory.php");

if (isset($_POST['member_confirm_submit'])) {

    if ($_POST['token'] !== "" && $_POST['token'] == $_SESSION["token"]) {

        if ($_SESSION['gender'] == "男性") {
            $insert_gender = 1;
        } elseif ($_SESSION['gender'] == "女性") {
            $insert_gender = 2;
        }

        date_default_timezone_set('Asia/Tokyo');
        $created_at = date('Y:m:d H:i:s');

        $sql = 'INSERT INTO `members` 
        (`name_sei`, `name_mei`, `gender`, `pref_name`, `address`, `password`, `email`, `created_at`, `updated_at`) 
        VALUES (:name_sei, :name_mei, :gender, :pref_name, :address, :password, :email, :created_at, :updated_at)';

        try {
            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ':name_sei' => $_SESSION['first_name'],
                ':name_mei' => $_SESSION['second_name'],
                ':gender' => $insert_gender,
                ':pref_name' => $_SESSION['pref'],
                ':address' => $_SESSION['address'],
                ':password' => $_SESSION['password'],
                ':email' => $_SESSION['mail'],
                ':created_at' => $created_at,
                ':updated_at' => $created_at
            ]);

            echo "登録しました";
        } catch (PDOException $e) {
            echo "エラーが発生しました: " . $e->getMessage();
        }
    }

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
    $name = $_SESSION['first_name'] . $_SESSION['second_name'];
}

if (isset($_SESSION['pref']) && isset($_SESSION['address'])) {
    foreach ($prefCotegory as $pref) {
        if ($_SESSION['pref'] == $pref['value']) {
            $address_pref = $pref['value'];
        }
    }
    $address = $address_pref . $_SESSION['address'];
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
                <input type="hidden" name="token" value="<?php echo $token; ?>">

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