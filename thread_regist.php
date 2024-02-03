<?php
require_once("function.php");

if ($_SESSION['login'] !== 1) {
    header('Location: index.php');
    exit();
}

$validation = isset($_SESSION['validation_errors']) ? $_SESSION['validation_errors'] : [];
unset($_SESSION['validation_errors']);

$post_items = isset($_SESSION['input_items']) ? $_SESSION['input_items'] : [];
unset($_SESSION['input_items']);

if (isset($_POST['thread_regi_submit'])) {
    header('Location: thread_confirm.php');
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
        <h1 class="main_title">スレッド作成フォーム</h1>

        <form action="thread_confirm.php" method="post">
            <div class="thread_input_group">
                <div class="thread_items">
                    <p>スレッドタイトル</p>
                    <div class="thread_item">
                        <input type="text" name="thread_title" value="<?php echo isset($post_items['thread_title']) ? $post_items['thread_title'] : null; ?>" class="thread_input">
                        <span class="indi indi_sub thread_indi">
                            <?php
                            if (isset($validation['thread_title'])) {
                                echo "※タイトルは入力必須です";
                                initializeValidationErrors('thread_title');
                                initializeValidationErrors('thread_title_count');
                            } elseif (isset($validation['thread_title_count'])) {
                                echo "※タイトルは100文字以内で入力してください";
                                initializeValidationErrors('thread_title_count');
                            }
                            ?>
                        </span>
                    </div>
                </div>
                <div class="thread_items">
                    <p>コメント</p>
                    <div class="thread_item">
                        <textarea name="thread_comment" id="" cols="30" rows="10" class="thread_input"><?php echo isset($post_items['thread_comment']) ? $post_items['thread_comment'] : null; ?></textarea>
                        <span class="indi indi_sub thread_indi">
                            <?php
                            if (isset($validation['thread_comment'])) {
                                echo "※コメントは入力必須です";
                                initializeValidationErrors('thread_comment');
                                initializeValidationErrors('thread_comment_conut');
                            } elseif (isset($validation['thread_comment_count'])) {
                                echo "※コメントは500文字以内で入力してください";
                                initializeValidationErrors('thread_comment_count');
                            }
                            ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="member_regi_submit">
                <div class="regi_btn">
                    <input type="submit" name="thread_regi_submit" value="確認画面へ" class="member_regi_btn thread_regist_btn">
                </div>
                <div class="regi_btn">
                    <button type="button" onclick="location.href='index.php?thread_login=1'" class="btn back_btn">トップに戻る</button>
                </div>
            </div>

        </form>
    </div>
</body>

</html>