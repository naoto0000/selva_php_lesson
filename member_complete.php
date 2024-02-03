<?php
require_once("function.php");

if ($_POST['token'] !== "" && $_POST['token'] == $_SESSION["token"]) {

    if ($_SESSION['input_items']['gender'] == "男性") {
        $insert_gender = 1;
    } elseif ($_SESSION['input_items']['gender'] == "女性") {
        $insert_gender = 2;
    }

    date_default_timezone_set('Asia/Tokyo');
    $created_at = date('Y:m:d H:i:s');

    $hashed_pass = password_hash($_SESSION['input_items']['password'], PASSWORD_DEFAULT);

    $sql = 'INSERT INTO `members` 
(`name_sei`, `name_mei`, `gender`, `pref_name`, `address`, `password`, `email`, `created_at`, `updated_at`) 
VALUES (:name_sei, :name_mei, :gender, :pref_name, :address, :password, :email, :created_at, :updated_at)';

    try {
        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':name_sei' => $_SESSION['input_items']['first_name'],
            ':name_mei' => $_SESSION['input_items']['second_name'],
            ':gender' => $insert_gender,
            ':pref_name' => $_SESSION['input_items']['pref'],
            ':address' => $_SESSION['input_items']['address'],
            ':password' => $hashed_pass,
            ':email' => $_SESSION['input_items']['mail'],
            ':created_at' => $created_at,
            ':updated_at' => $created_at
        ]);

        $_SESSION = array(); //セッションの中身をすべて削除
        session_destroy(); //セッションを破壊

    } catch (PDOException $e) {
        echo "エラーが発生しました: " . $e->getMessage();
    }
}

session_start();

if (isset($_POST['member_regi_back_submit'])) {
    $_SESSION['login'] = 1;
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
    <main>
        <div class="container">
            <h1 class="main_title">会員登録完了</h1>
            <p class="complete_text">会員登録が完了しました</p>
            <form action="" method="post">
                <div class="complete_btn">
                    <input type="submit" name="member_regi_back_submit" value="トップに戻る" class="member_complete_btn">
                </div>
            </form>
        </div>
    </main>
</body>

</html>