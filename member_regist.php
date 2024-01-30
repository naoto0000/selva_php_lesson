<?php
require_once("function.php");
require_once("pref_cotegory.php");

if (isset($_POST['member_regi_submit'])) {

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
    }

    validate($_POST);

    $_SESSION['first_name'] = $_POST['first_name'];
    $_SESSION['second_name'] = $_POST['second_name'];
    $_SESSION['gender'] = $_POST['gender'];
    $_SESSION['pref'] = $_POST['pref'];
    $_SESSION['address'] = $_POST['address'];
    $_SESSION['password'] = $_POST['password'];
    $_SESSION['pass_conf'] = $_POST['pass_conf'];
    $_SESSION['mail'] = $_POST['mail'];

    if (isset($_SESSION['validation_errors']) && is_array($_SESSION['validation_errors']) && count($_SESSION['validation_errors']) === 0) {

    header('Location: member_confirm.php');
    exit();
    }
}

function initializeValidationErrors($key)
{
    $_SESSION['validation_errors'][$key] = "";
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
            <h1 class="main_title">会員情報登録フォーム</h1>

            <form action="" method="post">

                <div class="item_group">
                    <div class="input_name">
                        <p class="sub_title sub_group">氏名</p>
                        <div class="input_name_items">
                            <label for="" class="item_label">姓</label>
                            <input type="text" name="first_name" value="<?php echo isset($_SESSION['first_name']) ? $_SESSION['first_name'] : ''; ?>">
                        </div>
                        <div class="input_name_items">
                            <label for="" class="item_label">名</label>
                            <input type="text" name="second_name" value="<?php echo isset($_SESSION['second_name']) ? $_SESSION['second_name'] : ''; ?>">
                        </div>
                    </div>
                    <div class="name_indi">
                        <span class="indi indi_sub">
                            <?php
                            if (isset($_SESSION['validation_errors']['first_name']) && $_SESSION['validation_errors']['first_name']) {
                                echo "※氏名(姓)は入力必須です";
                                initializeValidationErrors('first_name');
                                initializeValidationErrors('first_name_count');
                            } elseif (isset($_SESSION['validation_errors']['first_name_count']) && $_SESSION['validation_errors']['first_name_count']) {
                                echo "※氏名(姓)は20文字以内で入力してください";
                                initializeValidationErrors('first_name_count');
                            }
                            ?>
                        </span>
                        <span class="indi indi_sub">
                            <?php
                            if (isset($_SESSION['validation_errors']['second_name']) && $_SESSION['validation_errors']['second_name']) {
                                echo "※氏名(名)は入力必須です";
                                initializeValidationErrors('second_name');
                                initializeValidationErrors('second_name_count');
                            } elseif (isset($_SESSION['validation_errors']['second_name_count']) && $_SESSION['validation_errors']['second_name_count']) {
                                echo "※氏名(名)は20文字以内で入力してください";
                                initializeValidationErrors('second_name_count');
                            }
                            ?>
                        </span>
                    </div>
                </div>

                <div class="item_group">
                    <div class="input_items">
                        <p class="sub_title sub_group">性別</p>
                        <div class="gender_radio">
                            <?php if ($_SESSION['gender'] == "男性") : ?>
                                <input type="radio" name="gender" value="男性" checked>
                            <?php else : ?>
                                <input type="radio" name="gender" value="男性">
                            <?php endif; ?>
                            <label for="" class="item_label">男性</label>
                        </div>
                        <div class="gender_radio">
                            <?php if ($_SESSION['gender'] == "女性") : ?>
                                <input type="radio" name="gender" value="女性" checked>
                            <?php else : ?>
                                <input type="radio" name="gender" value="女性">
                            <?php endif; ?>
                            <label for="" class="item_label">女性</label>
                        </div>
                    </div>
                    <span class="indi indi_sub">
                        <?php
                        if (isset($_SESSION['validation_errors']['gender']) && $_SESSION['validation_errors']['gender']) {
                            echo "※性別は入力必須です";
                            initializeValidationErrors('gender');
                            initializeValidationErrors('gender_check');
                        } elseif (isset($_SESSION['validation_errors']['gender_check']) && $_SESSION['validation_errors']['gender_check']) {
                            echo "※正しい値を入力してください";
                            initializeValidationErrors('gender_check');
                        }
                        ?>
                    </span>

                </div>

                <div class="input_items">
                    <p class="sub_title sub_group">住所</p>
                    <div class="input_address">
                        <div class="address_indi">
                            <div class="input_address_items">
                                <p class="sub_title_address">都道府県</p>
                                <select name="pref" id="" class="sub_address_input">
                                    <option value="" selected>選択してください</option>
                                    <?php foreach ($prefCotegory as $row) {
                                        if ($row['value'] == $_SESSION['pref']) {
                                            echo '<option value="' . $row['value'] . '" selected>' . $row['value'] . '</option>';
                                        } else {
                                            echo '<option value="' . $row['value'] . '">' . $row['value'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>

                            </div>
                            <span class="indi">
                                <?php
                                if (isset($_SESSION['validation_errors']['pref']) && $_SESSION['validation_errors']['pref']) {
                                    echo "※都道府県は入力必須です";
                                    initializeValidationErrors('pref');
                                    initializeValidationErrors('pref_check');
                                } elseif (isset($_SESSION['validation_errors']['pref_check']) && $_SESSION['validation_errors']['pref_check']) {
                                    echo "※正しい値を入力してください";
                                    initializeValidationErrors('pref_check');
                                }
                                ?>
                            </span>
                        </div>
                        <div class="address_indi">
                            <div class="input_address_items">
                                <p class="sub_title_address">それ以降の住所</p>
                                <input type="text" name="address" class="sub_address_input" value="<?php echo isset($_SESSION['address']) ? $_SESSION['address'] : ''; ?>">
                            </div>
                            <span class="indi">
                                <?php
                                if (isset($_SESSION['validation_errors']['address']) && $_SESSION['validation_errors']['address']) {
                                    echo "※100文字以内で入力してください";
                                    initializeValidationErrors('address');
                                }
                                ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="item_group">
                    <div class="input_items">
                        <p class="sub_title">パスワード</p>
                        <input type="password" name="password" class="regi_input" value="<?php echo isset($_SESSION['password']) ? $_SESSION['password'] : ''; ?>">
                    </div>
                    <span class="indi">
                        <?php
                        if (isset($_SESSION['validation_errors']['password']) && $_SESSION['validation_errors']['password']) {
                            echo "※パスワードは入力必須です";
                            initializeValidationErrors('password');
                            initializeValidationErrors('pass_check');
                        } elseif (isset($_SESSION['validation_errors']['pass_check']) && $_SESSION['validation_errors']['pass_check']) {
                            echo "※英数字8~20文字で入力してください";
                            initializeValidationErrors('pass_check');
                        }
                        ?>
                    </span>
                </div>

                <div class="item_group">
                    <div class="input_items">
                        <p class="sub_title">パスワード確認</p>
                        <input type="password" name="pass_conf" class="regi_input" value="<?php echo isset($_SESSION['pass_conf']) ? $_SESSION['pass_conf'] : ''; ?>">
                    </div>
                    <span class="indi">
                        <?php
                        if (isset($_SESSION['validation_errors']['pass_conf']) && $_SESSION['validation_errors']['pass_conf']) {
                            echo "※パスワード確認は入力必須です";
                            initializeValidationErrors('pass_conf');
                            initializeValidationErrors('pass_conf_check');
                            initializeValidationErrors('pass_match');
                        } elseif (isset($_SESSION['validation_errors']['pass_conf_check']) && $_SESSION['validation_errors']['pass_conf_check']) {
                            echo "※英数字8~20文字で入力してください";
                            initializeValidationErrors('pass_conf_check');
                            initializeValidationErrors('pass_match');
                        } elseif (isset($_SESSION['validation_errors']['pass_match']) && $_SESSION['validation_errors']['pass_match']) {
                            echo "※パスワードと入力した値が異なっています";
                            initializeValidationErrors('pass_match');
                        }
                        ?>
                    </span>
                </div>

                <div class="item_group">
                    <div class="input_items">
                        <p class="sub_title">メールアドレス</p>
                        <input type="text" name="mail" class="regi_input" value="<?php echo isset($_SESSION['mail']) ? $_SESSION['mail'] : ''; ?>">
                    </div>
                    <span class="indi">
                        <?php
                        if (isset($_SESSION['validation_errors']['mail']) && $_SESSION['validation_errors']['mail']) {
                            echo "※メールアドレスは入力必須です";
                            initializeValidationErrors('mail');
                            initializeValidationErrors('mail_check');
                        } elseif (isset($_SESSION['validation_errors']['mail_check']) && $_SESSION['validation_errors']['mail_check']) {
                            echo "※メールアドレスの形式で入力してください";
                            initializeValidationErrors('mail_check');
                        }
                        ?>
                    </span>
                </div>

                <div class="member_regi_submit"><input type="submit" name="member_regi_submit" value="確認画面へ"></div>
            </form>
        </div>
    </main>

</body>

</html>