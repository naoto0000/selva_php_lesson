<?php
require_once("function.php");

if ($_SESSION['login'] !== 1) {
    header('Location: index.php');
    exit();
}

if (isset($_POST['withdrawal_submit'])) {
    date_default_timezone_set('Asia/Tokyo');
    $deleted_at = date('Y:m:d H:i:s');

    $delete_sql = "UPDATE members SET deleted_at = :deleted_at WHERE id = :id";
    $delete_stmt = $pdo->prepare($delete_sql);
    $delete_stmt->execute([
        ':id' => $_SESSION['member_id'],
        ':deleted_at' => $deleted_at
    ]);

    $_SESSION['login'] = "";

    header("Location: index.php");
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
    <header class="with_header">
        <button type="button" onclick="location.href='index.php'" class="btn with_btn">トップに戻る</button>
    </header>

    <main>
        <div class="container">
            <h1 class="main_title">退会</h1>
            <div class="withdrawal_group">
                <p>退会しますか？</p>
                <form action="" method="post">
                    <input type="submit" name="withdrawal_submit" value="退会する" class="member_regi_btn">
                </form>
            </div>
        </div>
    </main>
</body>

</html>