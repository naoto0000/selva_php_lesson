<?php
require_once("function.php");

$_SESSION = array(); //セッションの中身をすべて削除
session_destroy(); //セッションを破壊

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
        </div>
    </main>
</body>

</html>