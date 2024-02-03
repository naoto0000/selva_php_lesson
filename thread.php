<?php
require_once("function.php");

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

// 検索時の処理
// ===========
if (isset($_POST['thread_search_submit']) && $_POST['thread_search'] !== "") {
    // 入力値を取得 文字前後の空白除去&エスケープ処理
    $thread_name = trim(htmlspecialchars($_POST['thread_search'], ENT_QUOTES));
    // 文字列の中の「　」(全角空白)を「」(何もなし)に変換
    $thread_name = str_replace("　", "", $thread_name);

    $search_sql = "SELECT * FROM `thread` WHERE(title LIKE :word OR content LIKE :word2) ORDER BY created_at DESC";
    $search_stmt = $pdo->prepare($search_sql);
    $search_stmt->bindValue(':word', "%{$thread_name}%", PDO::PARAM_STR);
    $search_stmt->bindValue(':word2', "%{$thread_name}%", PDO::PARAM_STR);
    $search_stmt->execute();
    $search_result = $search_stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $search_sql = "SELECT * FROM `thread` ORDER BY created_at DESC";
    $search_stmt = $pdo->prepare($search_sql);
    $search_stmt->execute();
    $search_result = $search_stmt->fetchAll(PDO::FETCH_ASSOC);
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
        <div class="header_link">
            <?php if ($_SESSION['login'] == 1) : ?>
                <button type="button" onclick="location.href='thread_regist.php'" class="btn thread_display_btn">新規スレッド作成</button>
            <?php endif; ?>
        </div>
    </header>

    <main>
        <div class="container">
            <form action="" method="post">
                <div class="thread_search_submit">
                    <input type="text" name="thread_search" class="thread_search">
                    <input type="submit" name="thread_search_submit" value="スレッド検索" class="search_submit">
                </div>
            </form>

            <div class="thread_display">
                <?php foreach ($search_result as $thread_display) : ?>
                    <?php
                    $originalDate = $thread_display['created_at'];
                    $dateTime = new DateTime($originalDate);
                    $thread_display['created_at'] = $dateTime->format('Y.n.j H:i');
                    ?>
                    <div class="thread_display_items">
                        <p class="display_id display_item">ID:<?php echo $thread_display['id'] ?></p>
                        <p class="display_title display_item"><?php echo $thread_display['title'] ?></p>
                        <p class="display_date display_item"><?php echo $thread_display['created_at'] ?></p>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="member_regi_submit">
                <div class="regi_btn">
                    <?php if ($_SESSION['login'] == 1) : ?>
                        <button type="button" onclick="location.href='index.php?thread_login=1'" class="btn back_btn">トップに戻る</button>
                    <?php else : ?>
                        <button type="button" onclick="location.href='index.php'" class="btn back_btn">トップに戻る</button>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </main>

</body>

</html>