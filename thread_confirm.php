<?php
require_once("function.php");

// スレッド新規作成時のバリデーション
// ============================

// スレッドタイトルのチェック
if ($_POST['thread_title'] === "") {
    $_SESSION['validation_errors']['thread_title'] = true;
}

$thread_title_conut = strlen($_POST['thread_title']);

if ($thread_title_conut > 100) {
    // 100文字以上の場合の処理
    $_SESSION['validation_errors']['thread_title_count'] = true;
}

// スレッドコメントのチェック
if ($_POST['thread_comment'] === "") {
    $_SESSION['validation_errors']['thread_comment'] = true;
}

$thread_comment_conut = strlen($_POST['thread_comment']);

if ($thread_comment_conut > 500) {
    // 500文字以上の場合の処理
    $_SESSION['validation_errors']['thread_comment_count'] = true;
}

$_SESSION['input_items']['thread_title'] = htmlspecialchars($_POST['thread_title'], ENT_QUOTES, 'UTF-8');
$_SESSION['input_items']['thread_comment'] = htmlspecialchars($_POST['thread_comment'], ENT_QUOTES, 'UTF-8');

if (isset($_SESSION['validation_errors']) && is_array($_SESSION['validation_errors']) && count($_SESSION['validation_errors']) !== 0) {
    header('Location: thread_regist.php');
    exit();
}

if (isset($_POST['thread_confirm_submit'])) {
    header('Location: index.php');
    exit();
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
    <div class="container">
        <h1 class="main_title">スレッド作成確認画面</h1>

        <form action="thread.php" method="post">
            <div class="thread_input_group">
                <div class="thread_items">
                    <p class="thread_confirm_label">スレッドタイトル</p>
                    <p class="thread_confirm_contents"><?php echo $_SESSION['input_items']['thread_title']; ?></p>
                </div>
                <div class="thread_items">
                    <p class="thread_confirm_label">コメント</p>
                    <p class="thread_confirm_contents"><?php echo nl2br($_SESSION['input_items']['thread_comment']); ?></p>
                </div>
            </div>

            <input type="hidden" name="token" value="<?php echo $token; ?>">

            <div class="member_regi_submit">
                <div class="regi_btn">
                    <input type="submit" name="thread_confirm_submit" value="スレッドを作成する" class="member_regi_btn thread_confirm_btn">
                </div>
                <div class="regi_btn">
                    <button type="button" onclick="location.href='thread_regist.php'" class="btn back_btn thread_confirm_btn">前に戻る</button>
                </div>
            </div>

        </form>
    </div>
</body>

</html>