<?php
require_once("function.php");

if (isset($_POST['login_submit'])) {
    $login_mail = $_SESSION['login_id'] = $_POST['login_id'];
    $login_pass = $_SESSION['login_pass'] = $_POST['login_pass'];

    $login_sql = "SELECT * FROM members WHERE email = :email";
    $login_stmt = $pdo->prepare($login_sql);
    $login_stmt->bindValue(':email', $login_mail, PDO::PARAM_STR);
    $login_stmt->execute();
    $login_result = $login_stmt->fetch();

    if ($login_mail == "" || $login_pass == "" || $login_mail == "" && $login_pass == "") {
        $_SESSION['login_error'] = true;
        header('Location: login.php');
        exit();
    }

    // パスワードのチェック
    if (password_verify($login_pass, $login_result['password'])) {

        //DBのユーザー情報をセッションに保存
        $_SESSION['member_id'] = $login_result['id'];
        $_SESSION['name'] = $login_result['name_sei'] . $login_result['name_mei'];

        $_SESSION['login'] = 1;

        $_SESSION['login_id'] = "";
        $login_id = "";
    } else {
        $_SESSION['login_error'] = true;
        header('Location: login.php');
        exit();
    }
} elseif (isset($_POST['thread_confirm_submit'])) {
    $_SESSION['login'] = 1;
} else {
    $_SESSION['login'] = "";
}

if ($_GET['thread_login'] == 1) {
    $_SESSION['login'] = 1;
    $_GET['thread_login'] == "";
}

if (isset($_POST['logout'])) {
    $_SESSION['login'] = "";
}

// スレッド作成時の処理
// =================
if (isset($_POST['thread_confirm_submit'])) {
    if ($_POST['token'] !== "" && $_POST['token'] == $_SESSION["token"]) {

        date_default_timezone_set('Asia/Tokyo');
        $created_at = date('Y:m:d H:i:s');

        $thread_sql = 'INSERT INTO `thread` 
    (`member_id`, `title`, `content`, `created_at`, `updated_at`) 
    VALUES (:member_id, :title, :content, :created_at, :updated_at)';

        try {
            $thread_stmt = $pdo->prepare($thread_sql);

            $thread_stmt->execute([
                ':member_id' => $_SESSION['member_id'],
                ':title' => $_SESSION['input_items']['thread_title'],
                ':content' => $_SESSION['input_items']['thread_comment'],
                ':created_at' => $created_at,
                ':updated_at' => $created_at
            ]);

            unset($_SESSION['validation_errors']);
            unset($_SESSION['input_items']);

        } catch (PDOException $e) {
            echo "エラーが発生しました: " . $e->getMessage();
        }
    }
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
    <header>
        <div class="header_name">
            <?php if ($_SESSION['login'] == 1) : ?>
                <p>ようこそ<?php echo $_SESSION['name']; ?>様</p>
            <?php endif; ?>
        </div>
        <div class="header_link">
            <form action="" method="post">
                <?php if ($_SESSION['login'] == 1) : ?>
                    <button type="button" onclick="location.href='thread_regist.php'" class="btn">新規スレッド作成</button>
                    <input type="submit" name="logout" class="btn logout_btn" value="ログアウト">
                <?php else : ?>
                    <div class="header_login_group">
                        <button type="button" onclick="location.href='member_regist.php'" class="btn">新規会員登録</button>
                        <button type="button" onclick="location.href='login.php'" class="btn">ログイン</button>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </header>
</body>

</html>