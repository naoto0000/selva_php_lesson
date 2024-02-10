<?php
require_once("../function.php");

$admin_login_error = isset($_SESSION['admin_login_error']) ? $_SESSION['admin_login_error'] : '';
unset($_SESSION['admin_login_error']);

$admin_login_id_input = isset($_SESSION['admin_login_id']) ? $_SESSION['admin_login_id'] : '';
unset($_SESSION['admin_login_id']);

if (isset($_POST['admin_login_submit'])) {
    header('Location: index.php');
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
    </header>
    <div class="container">
        <h1 class="main_title">管理画面</h1>

        <form action="index.php" method="post">
            <div class="login_input">
                <div class="login_items">
                    <p>ログインID</p>
                    <input type="text" name="admin_login_id" value="<?php echo isset($admin_login_id_input) ? $admin_login_id_input : ''; ?>">
                </div>
                <div class="login_items">
                    <p>パスワード</p>
                    <input type="password" name="admin_login_pass">
                </div>
                <span class="indi admin_indi">
                    <?php
                    if (isset($admin_login_error['input_null']) && $admin_login_error['input_null']) {
                        echo "※IDもしくはパスワードが間違っています";
                        $admin_login_error = "";
                    } elseif (isset($admin_login_error['id_check']) && $admin_login_error['id_check'] && isset($admin_login_error['pass_check']) && $admin_login_error['pass_check']) {
                        echo "※ログインIDは半角英数字7~10文字以内で入力してください";
                        echo "※パスワードは半角英数字8~20文字以内で入力してください";
                        $admin_login_error = "";
                    } elseif (isset($admin_login_error['id_check']) && $admin_login_error['id_check']) {
                        echo "※ログインIDは半角英数字7~10文字以内で入力してください";
                        $admin_login_error = "";
                    } elseif (isset($admin_login_error['pass_check']) && $admin_login_error['pass_check']) {
                        echo "※パスワードは半角英数字8~20文字以内で入力してください";
                        $admin_login_error = "";
                    }
                    ?>
                </span>
            </div>
            <div class="login_btn">
                <div class="member_confirm_btn">
                    <input type="submit" name="admin_login_submit" value="ログイン" class="member_confirm_submit login_submit">
                </div>
            </div>
        </form>
    </div>

</body>

</html>