<?php
require_once("../function.php");
require_once("../pref_cotegory.php");

if (isset($_POST['member_edit_submit'])) {

    header('Location: member_confirm.php');
    exit();
}

if ($_SESSION['admin_login'] == "") {
    header('Location: login.php');
    exit();
}

$member_id = $_GET['member_id'];

$sql = "SELECT * FROM `members` WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $member_id, PDO::PARAM_STR);
$stmt->execute();
$stmt_result = $stmt->fetch();

$member_sei = $stmt_result['name_sei'];
$member_mei = $stmt_result['name_mei'];
$member_gender = $stmt_result['gender'];
$member_pref = $stmt_result['pref_name'];
$member_address = $stmt_result['address'];
$member_password = $stmt_result['password'];
$member_mail = $stmt_result['email'];

$validation = isset($_SESSION['validation_errors']) ? $_SESSION['validation_errors'] : [];
unset($_SESSION['validation_errors']);

$post_items = isset($_SESSION['input_items']) ? $_SESSION['input_items'] : [];
unset($_SESSION['input_items']);
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
    <form action="member_confirm.php" method="post">
        <header class="admin_header">
            <div class="admin_header_left">
                <h2>会員編集</h2>
            </div>
            <div class="admin_header_right">
                <button type="button" onclick="location.href='member.php'" class="btn member_back_btn">一覧へ戻る</button>
            </div>
        </header>
        <main>
            <div class="container">
                <div class="item_group">
                    <div class="input_items">
                        <p class="sub_title sub_group">ID</p>
                        <p class="edit_id"><?php echo $member_id; ?></p>
                        <input type="hidden" name="id" value="<?php echo $member_id; ?>">
                    </div>
                </div>

                <div class="item_group">
                    <div class="input_name">
                        <p class="sub_title sub_group">氏名</p>
                        <div class="input_name_items">
                            <label for="" class="item_label">姓</label>
                            <?php if (isset($post_items['first_name'])) : ?>
                                <input type="text" name="first_name" value="<?php echo $post_items['first_name']; ?>">
                            <?php else : ?>
                                <input type="text" name="first_name" value="<?php echo $member_sei; ?>">
                            <?php endif; ?>
                        </div>
                        <div class="input_name_items">
                            <label for="" class="item_label">名</label>
                            <?php if (isset($post_items['second_name'])) : ?>
                                <input type="text" name="second_name" value="<?php echo $post_items['second_name']; ?>">
                            <?php else : ?>
                                <input type="text" name="second_name" value="<?php echo $member_mei; ?>">
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="name_indi">
                        <span class="indi indi_sub">
                            <?php
                            if (isset($validation['first_name'])) {
                                echo "※氏名(姓)は入力必須です";
                                initializeValidationErrors('first_name');
                                initializeValidationErrors('first_name_count');
                            } elseif (isset($validation['first_name_count'])) {
                                echo "※氏名(姓)は20文字以内で入力してください";
                                initializeValidationErrors('first_name_count');
                            }
                            ?>
                        </span>
                        <span class="indi indi_sub">
                            <?php
                            if (isset($validation['second_name'])) {
                                echo "※氏名(名)は入力必須です";
                                initializeValidationErrors('second_name');
                                initializeValidationErrors('second_name_count');
                            } elseif (isset($validation['second_name_count'])) {
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
                            <?php if ($post_items['gender'] == "男性") : ?>
                                <input type="radio" name="gender" value="男性" checked>
                            <?php elseif ($member_gender == 1) : ?>
                                <input type="radio" name="gender" value="男性" checked>
                            <?php else : ?>
                                <input type="radio" name="gender" value="男性">
                            <?php endif; ?>
                            <label for="" class="item_label">男性</label>
                        </div>
                        <div class="gender_radio">
                            <?php if ($post_items['gender'] == "女性") : ?>
                                <input type="radio" name="gender" value="女性" checked>
                            <?php elseif ($member_gender == 2) : ?>
                                <input type="radio" name="gender" value="女性" checked>
                            <?php else : ?>
                                <input type="radio" name="gender" value="女性">
                            <?php endif; ?>
                            <label for="" class="item_label">女性</label>
                        </div>
                    </div>
                    <span class="indi indi_sub">
                        <?php
                        if (isset($validation['gender'])) {
                            echo "※性別は入力必須です";
                            initializeValidationErrors('gender');
                            initializeValidationErrors('gender_check');
                        } elseif (isset($validation['gender_check'])) {
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
                                    <?php if (isset($post_items['pref'])) : ?>
                                        <option value="" selected>選択してください</option>
                                        <?php foreach ($prefCotegory as $row) {
                                            if ($row['value'] == $post_items['pref']) {
                                                echo '<option value="' . $row['value'] . '" selected>' . $row['value'] . '</option>';
                                            } else {
                                                echo '<option value="' . $row['value'] . '">' . $row['value'] . '</option>';
                                            }
                                        }
                                        ?>
                                    <?php else : ?>
                                        <option value="" selected>選択してください</option>
                                        <?php foreach ($prefCotegory as $row) {
                                            if ($row['value'] == $member_pref) {
                                                echo '<option value="' . $row['value'] . '" selected>' . $row['value'] . '</option>';
                                            } else {
                                                echo '<option value="' . $row['value'] . '">' . $row['value'] . '</option>';
                                            }
                                        }
                                        ?>
                                    <?php endif; ?>
                                </select>

                            </div>
                            <span class="indi">
                                <?php
                                if (isset($validation['pref'])) {
                                    echo "※都道府県は入力必須です";
                                    initializeValidationErrors('pref');
                                    initializeValidationErrors('pref_check');
                                } elseif (isset($validation['pref_check'])) {
                                    echo "※正しい値を入力してください";
                                    initializeValidationErrors('pref_check');
                                }
                                ?>
                            </span>
                        </div>
                        <div class="address_indi">
                            <div class="input_address_items">
                                <p class="sub_title_address">それ以降の住所</p>
                                <?php if (isset($post_items['address'])) : ?>
                                    <input type="text" name="address" class="sub_address_input" value="<?php echo $post_items['address']; ?>">
                                <?php else : ?>
                                    <input type="text" name="address" class="sub_address_input" value="<?php echo $member_address; ?>">
                                <?php endif; ?>
                            </div>
                            <span class="indi">
                                <?php
                                if (isset($validation['address'])) {
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
                        <input type="password" name="password" class="regi_input">
                    </div>
                    <span class="indi">
                        <?php
                        if (isset($validation['password'])) {
                            echo "※パスワードは入力必須です";
                            initializeValidationErrors('password');
                            initializeValidationErrors('pass_check');
                        } elseif (isset($validation['pass_check'])) {
                            echo "※英数字8~20文字で入力してください";
                            initializeValidationErrors('pass_check');
                        }
                        ?>
                    </span>
                </div>

                <div class="item_group">
                    <div class="input_items">
                        <p class="sub_title">パスワード確認</p>
                        <input type="password" name="pass_conf" class="regi_input">
                    </div>
                    <span class="indi">
                        <?php
                        if (isset($validation['pass_conf'])) {
                            echo "※パスワード確認は入力必須です";
                            initializeValidationErrors('pass_conf');
                            initializeValidationErrors('pass_conf_check');
                            initializeValidationErrors('pass_match');
                        } elseif (isset($validation['pass_conf_check'])) {
                            echo "※英数字8~20文字で入力してください";
                            initializeValidationErrors('pass_conf_check');
                            initializeValidationErrors('pass_match');
                        } elseif (isset($validation['pass_match'])) {
                            echo "※パスワードと入力した値が異なっています";
                            initializeValidationErrors('pass_match');
                        }
                        ?>
                    </span>
                </div>

                <div class="item_group">
                    <div class="input_items">
                        <p class="sub_title">メールアドレス</p>
                        <?php if (isset($post_items['mail'])) : ?>
                            <input type="text" name="mail" class="regi_input" value="<?php echo $post_items['mail']; ?>">
                        <?php else : ?>
                            <input type="text" name="mail" class="regi_input" value="<?php echo $member_mail; ?>">
                        <?php endif; ?>
                    </div>
                    <span class="indi">
                        <?php
                        if (isset($validation['mail'])) {
                            echo "※メールアドレスは入力必須です";
                            initializeValidationErrors('mail');
                            initializeValidationErrors('mail_check');
                            initializeValidationErrors('mail_count_check');
                            initializeValidationErrors('mail_match');
                        } elseif (isset($validation['mail_check'])) {
                            echo "※メールアドレスの形式で入力してください";
                            initializeValidationErrors('mail_check');
                            initializeValidationErrors('mail_count_check');
                            initializeValidationErrors('mail_match');
                        } elseif (isset($validation['mail_count_check'])) {
                            echo "※メールアドレスは200文字以内で入力してください";
                            initializeValidationErrors('mail_count_check');
                            initializeValidationErrors('mail_match');
                        } elseif (isset($validation['mail_match'])) {
                            echo "※このメールアドレスは既に使用されています";
                            initializeValidationErrors('mail_match');
                        }
                        ?>
                    </span>
                </div>

                <div class="member_regi_submit">
                    <div class="regi_btn">
                        <input type="submit" name="member_edit_submit" value="確認画面へ" class="member_regi_btn">
                    </div>
                </div>
            </div>
        </main>
    </form>
</body>

</html>