<?php
require_once("function.php");

$thread_detail_id = $_GET['id'];

$thread_detail_sql =
    "SELECT * 
FROM `thread` as t
INNER JOIN `members` as m
ON t.member_id = m.id
WHERE t.id = :id";

$thread_detail_stmt = $pdo->prepare($thread_detail_sql);
$thread_detail_stmt->bindValue(':id', $thread_detail_id, PDO::PARAM_STR);
$thread_detail_stmt->execute();
$thread_detail_result = $thread_detail_stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($thread_detail_result as $thread_detail) {
    $detail_title = $thread_detail['title'];
    $detail_name = $thread_detail['name_sei'] . $thread_detail['name_mei'];
    $detail_comment = $thread_detail['content'];

    $originalDateTitle = $thread_detail['created_at'];
    $dateTimeTitle = new DateTime($originalDateTitle);
    $detail_date_title = $dateTimeTitle->format('n/j/y H:i');

    $originalDate = $thread_detail['created_at'];
    $dateTime = new DateTime($originalDate);
    $detail_date = $dateTime->format('Y.n.j H:i');
}

$comment_error = "";

if (isset($_POST['detail_comment_submit'])) {
    if ($_POST['detail_comment'] == "") {
        $comment_error = "※コメントを入力してください";
    } elseif (strlen($_POST['detail_comment']) > 500) {
        $comment_error = "※コメントは500文字以内で入力してください";
    } else {
        $comment_error = "";
    }

    if ($comment_error === "") {

        date_default_timezone_set('Asia/Tokyo');
        $created_at = date('Y:m:d H:i:s');

        $sql = 'INSERT INTO `comments` 
        (`member_id`, `thread_id`, `comment`, `created_at`, `updated_at`) 
        VALUES (:member_id, :thread_id, :comment, :created_at, :updated_at)';

        try {
            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ':member_id' => $_SESSION['member_id'],
                ':thread_id' => $thread_detail_id,
                ':comment' => $_POST['detail_comment'],
                ':created_at' => $created_at,
                ':updated_at' => $created_at
            ]);
        } catch (PDOException $e) {
            echo "エラーが発生しました: " . $e->getMessage();
        }
    }
}

// ページネーション関連
// ページ数を取得する。GETでページが渡ってこなかった時（最初のページ）は$pageに１を格納する。
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

if ($page > 1) {
    $start = ($page * 5) - 5;
} else {
    $start = 0;
}

// コメント表示用のsql
$display_sql =
    "SELECT c.id, c.comment, c.created_at, m.name_sei, m.name_mei 
FROM `comments` as c
INNER JOIN `members` as m
ON c.member_id = m.id
INNER JOIN `thread` as t
ON c.thread_id = t.id
WHERE t.id = :id
LIMIT {$start},5";

$display_stmt = $pdo->prepare($display_sql);
$display_stmt->bindValue(':id', $thread_detail_id, PDO::PARAM_STR);
$display_stmt->execute();
$display_result = $display_stmt->fetchAll(PDO::FETCH_ASSOC);

$display_count = count($display_result);

// カウント用
$count_sql =
    "SELECT c.id, c.comment, c.created_at, m.name_sei, m.name_mei 
FROM `comments` as c
INNER JOIN `members` as m
ON c.member_id = m.id
INNER JOIN `thread` as t
ON c.thread_id = t.id
WHERE t.id = :id";

$count_stmt = $pdo->prepare($count_sql);
$count_stmt->bindValue(':id', $thread_detail_id, PDO::PARAM_STR);
$count_stmt->execute();
$count_result = $count_stmt->fetchAll(PDO::FETCH_ASSOC);

$coment_count = count($count_result);

$max_page = ceil($coment_count / 5);

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
            <button type="button" onclick="location.href='thread.php'" class="btn thread_display_btn">スレッド一覧に戻る</button>
        </div>
    </header>

    <div class="container">
        <h1 class="main_title thread_detail_title"><?php echo $detail_title ?></h1>
        <div class="detail_sub">
            <p class="comment_count"><?php echo $coment_count ?>コメント</p>
            <p class="detai_title_date"><?php echo $detail_date_title; ?></p>
        </div>

        <div class="pagenation page_top">
            <?php if ($page >= 2) : ?>
                <a href="?id=<?php echo $thread_detail_id ?>&page=<?php echo $page - 1 ?>">前へ＞</a>
            <?php else : ?>
                <a class="page_a">前へ＞</a>
            <?php endif; ?>

            <?php if ($page < $max_page) : ?>
                <a href="?id=<?php echo $thread_detail_id ?>&page=<?php echo $page + 1 ?>">次へ＞</a>
            <?php else : ?>
                <a class="page_a">次へ＞</a>
            <?php endif; ?>
        </div>

        <div class="detail_display">
            <div class="detail_display_header">
                <p class="detail_name">投稿者：<?php echo $detail_name; ?></p>
                <p><?php echo $detail_date; ?></p>
            </div>
            <p class="detail_content"><?php echo nl2br($detail_comment); ?></p>
        </div>

        <?php foreach ($display_result as $display_row) : ?>
            <div class="display_comments">
                <div class="comment_title">
                    <p><?php echo $display_row['id'] ?>.</p>
                    <p><?php echo $display_row['name_sei'] . $display_row['name_mei'] ?></p>
                    <?php
                    $originalDate = $display_row['created_at'];
                    $dateTime = new DateTime($originalDate);
                    $display_row['created_at'] = $dateTime->format('Y.n.j H:i');
                    ?>
                    <p><?php echo $display_row['created_at'] ?></p>
                </div>
                <div class="comment_contents">
                    <p><?php echo $display_row['comment'] ?></p>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="pagenation page_down">
            <?php if ($page >= 2) : ?>
                <a href="?id=<?php echo $thread_detail_id ?>&page=<?php echo $page - 1 ?>">前へ＞</a>
            <?php else : ?>
                <a class="page_a">前へ＞</a>
            <?php endif; ?>

            <?php if ($page < $max_page) : ?>
                <a href="?id=<?php echo $thread_detail_id ?>&page=<?php echo $page + 1 ?>">次へ＞</a>
            <?php else : ?>
                <a class="page_a">次へ＞</a>
            <?php endif; ?>
        </div>

        <?php if ($_SESSION['login'] == 1) : ?>
            <form action="" method="post">
                <div class="detail_comment">
                    <?php if ($comment_error !== "") : ?>
                        <textarea name="detail_comment" id="" cols="30" rows="10"><?php echo $_POST['detail_comment'] ?></textarea>
                    <?php else : ?>
                        <textarea name="detail_comment" id="" cols="30" rows="10"></textarea>
                    <?php endif; ?>
                    <span class="indi comment_indi">
                        <?php
                        if ($comment_error !== "") {
                            echo $comment_error;
                            $comment_error = "";
                        }
                        ?>
                    </span>
                    <input type="submit" name="detail_comment_submit" value="コメントする" class="member_regi_btn detail_submit">
                </div>
            </form>
        <?php endif; ?>
    </div>

</body>

</html>