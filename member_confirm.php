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

function validate(array $post_data)
{
    $_SESSION['validation_errors'] = [];

    // 氏名のチェック
    // ===========
    if ($post_data['first_name'] === "") {
        $_SESSION['validation_errors']['first_name'] = true;
    }
    // 20字以内のチェックと文字列のチェック
    if (!preg_match('/\A[^\p{Cc}]{1,20}\z/u', $post_data['first_name'])) {
        $_SESSION['validation_errors']['first_name_count'] = true;
    }
    if ($post_data['second_name'] === "") {
        $_SESSION['validation_errors']['second_name'] = true;
    }
    // 20字以内のチェックと文字列のチェック
    if (!preg_match('/\A[^\p{Cc}]{1,20}\z/u', $post_data['second_name'])) {
        $_SESSION['validation_errors']['second_name_count'] = true;
    }

    // 性別のチェック
    // ===========
    if (!isset($post_data['gender']) || $post_data['gender'] === NULL) {
        $_SESSION['validation_errors']['gender'] = true;
    } elseif ($post_data['gender'] !== "男性" && $post_data['gender'] !== "女性") {
        $_SESSION['validation_errors']['gender_check'] = true;
    }

    // 住所のチェック
    // ===========
    if ($post_data['pref'] === "") {
        $_SESSION['validation_errors']['pref'] = true;
    }

    global $validate_prefCotegory;  // グローバル変数(pref_cotegory.php内の正しい都道府県を読み込んでいる)

    if (!in_array($post_data['pref'], $validate_prefCotegory, true)) {
        $_SESSION['validation_errors']['pref_check'] = true;
    }
    if (mb_strlen($post_data['address']) > 100) {
        $_SESSION['validation_errors']['address'] = true;
    }

    // パスワードのチェック
    // ================
    if ($post_data['password'] === "") {
        $_SESSION['validation_errors']['password'] = true;
    }
    // パスワードが英数字でかつ8文字以上20字以内かのチェック
    $pass_check = preg_match('/^[a-zA-Z0-9]{8,20}$/', ($post_data['password']));
    if ($pass_check === 0) {
        $_SESSION['validation_errors']['pass_check'] = true;
    }

    // パスワード確認のチェック
    // ====================
    if ($post_data['pass_conf'] === "") {
        $_SESSION['validation_errors']['pass_conf'] = true;
    }
    // パスワードが英数字でかつ8文字以上20字以内かのチェック
    $pass_conf_check = preg_match('/^[a-zA-Z0-9]{8,20}$/', ($post_data['pass_conf']));
    if ($pass_conf_check === 0) {
        $_SESSION['validation_errors']['pass_conf_check'] = true;
    }
    if ($post_data['password'] !== $post_data['pass_conf']) {
        $_SESSION['validation_errors']['pass_match'] = true;
    }

    // メールアドレスのチェック
    // ====================
    if ($post_data['mail'] === "") {
        $_SESSION['validation_errors']['mail'] = true;
    }
    $mail_check = preg_match("/^[a-zA-Z0-9]+([a-zA-Z0-9._-]{0,198})@[a-zA-Z0-9_-]+([a-zA-Z0-9._-]+)+$/", $post_data['mail']);
    if ($mail_check === 0) {
        $_SESSION['validation_errors']['mail_check'] = true;
    }

    $mail_conut = strlen($post_data['mail']);

    if ($mail_conut > 200) {
        // 200文字以内の場合の処理
        $_SESSION['validation_errors']['mail_count_check'] = true;
    }

    // メールアドレスの重複チェック
    global $mail_match_row;

    foreach ($mail_match_row as $mail_match) {
        if ($mail_match['email'] == $post_data['mail']) {
            $mail_match_result = 1;
        }
    }
    if ($mail_match_result === 1) {
        $_SESSION['validation_errors']['mail_match'] = true;
    }
}

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