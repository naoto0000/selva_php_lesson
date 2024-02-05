<?php
require_once("function.php");

$login_error = isset($_SESSION['login_error']) ? $_SESSION['login_error'] : '';
unset($_SESSION['login_error']);

$login_id = isset($_SESSION['login_id']) ? $_SESSION['login_id'] : '';
unset($_SESSION['login_id']);


if ($_SESSION['login'] == 1) {
    header('Location: index.php?login=1');
    exit();
}

if (isset($_POST['login_submit'])) {
    header('Location: index.php');
    exit();
}

if (isset($_POST['login_back_submit'])) {
    header('Location: index.php');
    exit();
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
    <div class="container">
        <h1 class="main_title">ログイン</h1>

        <form action="index.php" method="post">
            <div class="login_input">
                <div class="login_items">
                    <p>メールアドレス（ID）</p>
                    <input type="text" name="login_id" value="<?php echo isset($login_id) ? $login_id : ''; ?>">
                </div>
                <div class="login_items">
                    <p>パスワード</p>
                    <input type="password" name="login_pass">
                </div>
                <span class="indi">
                    <?php
                    if (isset($login_error) && $login_error) {
                        echo "※IDもしくはパスワードが間違っています";
                        $login_error = "";
                    }
                    ?>
                </span>
            </div>
            <div class="login_btn">
                <div class="member_confirm_btn">
                    <input type="submit" name="login_submit" value="ログイン" class="member_confirm_submit login_submit">
                </div>
                <div class="member_confirm_btn">
                    <input type="submit" name="login_back_submit" value="トップに戻る" class="member_confirm_back_submit">
                </div>
            </div>
        </form>
    </div>
</body>

</html>