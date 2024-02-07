<?php
require_once("../function.php");

if (isset($_POST['admin_login_submit'])) {
    $admin_login_id = $_SESSION['admin_login_id'] = $_POST['admin_login_id'];
    $admin_login_pass = $_SESSION['admin_login_pass'] = $_POST['admin_login_pass'];

    $admin_login_sql = "SELECT * FROM administers WHERE login_id = :login_id";
    $admin_login_stmt = $pdo->prepare($admin_login_sql);
    $admin_login_stmt->bindValue(':login_id', $admin_login_id, PDO::PARAM_STR);
    $admin_login_stmt->execute();
    $admin_login_result = $admin_login_stmt->fetch();

    $_SESSION['admin_login_error'] = [];

    if ($admin_login_id == "" || $admin_login_pass == "" || $admin_login_id == "" && $admin_login_pass == "") {
        $_SESSION['admin_login_error']['input_null'] = true;
        header('Location: login.php');
        exit();
    }

    if (!preg_match('/^[a-zA-Z0-9]{7,10}$/', $admin_login_id)) {
        $_SESSION['admin_login_error']['id_check'] = true;
        header('Location: login.php');
        exit();
    }

    if (!preg_match('/^[a-zA-Z0-9]{8,20}$/', $admin_login_pass)) {
        $_SESSION['admin_login_error']['pass_check'] = true;
        header('Location: login.php');
        exit();
    }

    // パスワードのチェック
    if ($admin_login_pass == $admin_login_result['password']) {

        //DBのユーザー情報をセッションに保存
        $_SESSION['admin_id'] = $admin_login_result['id'];
        $_SESSION['admin_name'] = $admin_login_result['name'];

        $_SESSION['admin_login'] = 1;

        $_SESSION['admin_login_id'] = "";
        $admin_login_id = "";
    } else {
        $_SESSION['admin_login_error']['input_null'] = true;
        header('Location: login.php');
        exit();
    }
}

if (isset($_POST['admin_logout'])) {
    $_SESSION['admin_login'] = "";

    header('Location: login.php');
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
    <form action="" method="post">
        <header class="admin_header">
            <div class="admin_header_left">
                <h2>掲示板管理画面メインメニュー</h2>
            </div>
            <div class="admin_header_right">
                <p>ようこそ<?php echo $_SESSION['admin_name'] ?>さん</p>
                <input type="submit" name="admin_logout" class="btn logout_btn" value="ログアウト">
            </div>
        </header>
    </form>
</body>

</html>