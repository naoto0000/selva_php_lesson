<?php
function validate(array $post_data)
{
    $_SESSION['validation_errors'] = [];

    // 氏名のチェック
    // ===========
    if ($post_data['first_name'] === "") {
        $_SESSION['validation_errors']['first_name'] = true;
    }
    // 20字以内のチェックと文字列のチェック
    if (!preg_match('/\A[^\p{Cc}]{1,20}\z/u', $post_data['first_name'])) {
        $_SESSION['validation_errors']['first_name_count'] = true;
    }
    if ($post_data['second_name'] === "") {
        $_SESSION['validation_errors']['second_name'] = true;
    }
    // 20字以内のチェックと文字列のチェック
    if (!preg_match('/\A[^\p{Cc}]{1,20}\z/u', $post_data['second_name'])) {
        $_SESSION['validation_errors']['second_name_count'] = true;
    }

    // 性別のチェック
    // ===========
    if (!isset($post_data['gender']) || $post_data['gender'] === NULL) {
        $_SESSION['validation_errors']['gender'] = true;
    } elseif ($post_data['gender'] !== "男性" && $post_data['gender'] !== "女性") {
        $_SESSION['validation_errors']['gender_check'] = true;
    }

    // 住所のチェック
    // ===========
    if ($post_data['pref'] === "") {
        $_SESSION['validation_errors']['pref'] = true;
    }

    global $validate_prefCotegory;  // グローバル変数(pref_cotegory.php内の正しい都道府県を読み込んでいる)

    if (!in_array($post_data['pref'], $validate_prefCotegory, true)) {
        $_SESSION['validation_errors']['pref_check'] = true;
    }
    if (mb_strlen($post_data['address']) > 100) {
        $_SESSION['validation_errors']['address'] = true;
    }

    // パスワードのチェック
    // ================
    if ($post_data['password'] === "") {
        $_SESSION['validation_errors']['password'] = true;
    }
    // パスワードが英数字でかつ8文字以上20字以内かのチェック
    $pass_check = preg_match('/^[a-zA-Z0-9]{8,20}$/', ($post_data['password']));
    if ($pass_check === 0) {
        $_SESSION['validation_errors']['pass_check'] = true;
    }

    // パスワード確認のチェック
    // ====================
    if ($post_data['pass_conf'] === "") {
        $_SESSION['validation_errors']['pass_conf'] = true;
    }
    // パスワードが英数字でかつ8文字以上20字以内かのチェック
    $pass_conf_check = preg_match('/^[a-zA-Z0-9]{8,20}$/', ($post_data['pass_conf']));
    if ($pass_conf_check === 0) {
        $_SESSION['validation_errors']['pass_conf_check'] = true;
    }
    if ($post_data['password'] !== $post_data['pass_conf']) {
        $_SESSION['validation_errors']['pass_match'] = true;
    }

    // メールアドレスのチェック
    // ====================
    if ($post_data['mail'] === "") {
        $_SESSION['validation_errors']['mail'] = true;
    }
    $mail_check = preg_match("/^[a-zA-Z0-9]+([a-zA-Z0-9._-]{0,198})@[a-zA-Z0-9_-]+([a-zA-Z0-9._-]+)+$/", $post_data['mail']);
    if ($mail_check === 0) {
        $_SESSION['validation_errors']['mail_check'] = true;
    }

    $mail_conut = strlen($post_data['mail']);

    if ($mail_conut > 200) {
        // 200文字以内の場合の処理
        $_SESSION['validation_errors']['mail_count_check'] = true;
    }

    // メールアドレスの重複チェック
    global $mail_match_row;

    foreach ($mail_match_row as $mail_match) {
        if ($mail_match['email'] == $post_data['mail']) {
            $mail_match_result = 1;
        }
    }
    if ($mail_match_result === 1) {
        $_SESSION['validation_errors']['mail_match'] = true;
    }

}
