<?php
require_once("function.php");
require_once("pref_cotegory.php");

if (isset($_POST['member_regi_submit'])) {
    $_SESSION['first_name'] = $_POST['first_name'];
    $_SESSION['second_name'] = $_POST['second_name'];
    $_SESSION['gender'] = $_POST['gender'];
    $_SESSION['pref'] = $_POST['pref'];
    $_SESSION['address'] = $_POST['address'];
    $_SESSION['password'] = $_POST['password'];
    $_SESSION['pass_conf'] = $_POST['pass_conf'];
    $_SESSION['mail'] = $_POST['mail'];

    header('Location: member_confirm.php');
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
            <h1 class="main_title">会員情報登録フォーム</h1>

            <form action="" method="post">
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

                <div class="input_items">
                    <p class="sub_title sub_group">性別</p>
                    <div class="gender_radio">
                        <?php if ($_SESSION['gender'] == 1) : ?>
                            <input type="radio" name="gender" value="1" checked>
                        <?php else : ?>
                            <input type="radio" name="gender" value="1">
                        <?php endif; ?>
                        <label for="" class="item_label">男性</label>
                    </div>
                    <div class="gender_radio">
                        <?php if ($_SESSION['gender'] == 2) : ?>
                            <input type="radio" name="gender" value="2" checked>
                        <?php else : ?>
                            <input type="radio" name="gender" value="2">
                        <?php endif; ?>
                        <label for="" class="item_label">女性</label>
                    </div>
                </div>
                <div class="input_items">
                    <p class="sub_title sub_group">住所</p>
                    <div class="input_address">
                        <div class="input_address_items">
                            <p class="sub_title_address">都道府県</p>
                            <select name="pref" id="" class="sub_address_input">
                                <option value="" selected>選択してください</option>
                                <?php foreach ($prefCotegory as $row) {
                                    if ($row['value'] == $_SESSION['pref']) {
                                        echo '<option value="' . $row['value'] . '" selected>' . $row['text'] . '</option>';
                                    } else {
                                        echo '<option value="' . $row['value'] . '">' . $row['text'] . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="input_address_items">
                            <p class="sub_title_address">それ以降の住所</p>
                            <input type="text" name="address" class="sub_address_input" value="<?php echo isset($_SESSION['address']) ? $_SESSION['address'] : ''; ?>">
                        </div>
                    </div>
                </div>
                <div class="input_items">
                    <p class="sub_title">パスワード</p>
                    <input type="password" name="password" class="regi_input" value="<?php echo isset($_SESSION['password']) ? $_SESSION['password'] : ''; ?>">
                </div>
                <div class="input_items">
                    <p class="sub_title">パスワード確認</p>
                    <input type="password" name="pass_conf" class="regi_input" value="<?php echo isset($_SESSION['pass_conf']) ? $_SESSION['pass_conf'] : ''; ?>">
                </div>
                <div class="input_items">
                    <p class="sub_title">メールアドレス</p>
                    <input type="text" name="mail" class="regi_input" value="<?php echo isset($_SESSION['mail']) ? $_SESSION['mail'] : ''; ?>">
                </div>

                <div class="member_regi_submit"><input type="submit" name="member_regi_submit" value="確認画面へ"></div>
            </form>
        </div>
    </main>

</body>

</html>