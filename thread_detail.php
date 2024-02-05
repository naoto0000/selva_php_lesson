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
    $detail_name = $thread_detail['name_sei'].$thread_detail['name_mei'];
    $detail_comment = $thread_detail['content'];
    
    $originalDateTitle = $thread_detail['created_at'];
    $dateTimeTitle = new DateTime($originalDateTitle);
    $detail_date_title = $dateTimeTitle->format('n/j/y H:i');

    $originalDate = $thread_detail['created_at'];
    $dateTime = new DateTime($originalDate);
    $detail_date = $dateTime->format('Y.n.j H:i');
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
            <button type="button" onclick="location.href='thread.php'" class="btn thread_display_btn">スレッド一覧に戻る</button>
        </div>
    </header>

    <div class="container">
        <h1 class="main_title thread_detail_title"><?php echo $detail_title ?></h1>
        <p class="detai_title_date"><?php echo $detail_date_title; ?></p>

        <div class="detail_display">
            <div class="detail_display_header">
                <p class="detail_name">投稿者：<?php echo $detail_name; ?></p>
                <p><?php echo $detail_date; ?></p>
            </div>
            <p class="detail_content"><?php echo nl2br($detail_comment); ?></p>
        </div>

        <?php if ($_SESSION['login'] == 1) : ?>
        <div class="detail_comment">
            <textarea name="detail_comment" id="" cols="30" rows="10"></textarea>
            <input type="submit" name="detail_comment_submit" value="コメントする" class="member_regi_btn detail_submit">
        </div>
        <?php endif; ?>
    </div>

</body>

</html>