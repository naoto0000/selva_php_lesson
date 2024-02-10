<?php 
// 会員登録・編集関連
// ===============
if ($_POST['token'] !== "" && $_POST['token'] == $_SESSION["token"]) {

    if ($_SESSION['input_items']['gender'] == "男性") {
        $insert_gender = 1;
    } elseif ($_SESSION['input_items']['gender'] == "女性") {
        $insert_gender = 2;
    }

    date_default_timezone_set('Asia/Tokyo');
    $created_at = date('Y:m:d H:i:s');

    $hashed_pass = password_hash($_SESSION['input_items']['password'], PASSWORD_DEFAULT);

    if ($_SESSION['member_edit'] == 1) {
        if ($hashed_pass == "") {
            // パスワードが空欄の場合
            $sql = "UPDATE `members` 
        SET name_sei = :name_sei, name_mei = :name_mei, gender = :gender,
            pref_name = :pref_name, address = :address, email = :email, updated_at = :updated_at 
        WHERE id = :id";

            try {
                $stmt = $pdo->prepare($sql);

                $stmt->execute([
                    ':id' => $_SESSION['input_items']['id'],
                    ':name_sei' => $_SESSION['input_items']['first_name'],
                    ':name_mei' => $_SESSION['input_items']['second_name'],
                    ':gender' => $insert_gender,
                    ':pref_name' => $_SESSION['input_items']['pref'],
                    ':address' => $_SESSION['input_items']['address'],
                    ':email' => $_SESSION['input_items']['mail'],
                    ':updated_at' => $created_at
                ]);

                $_SESSION['member_edit'] = "";
                $_SESSION['input_items'] = "";
            } catch (PDOException $e) {
                echo "エラーが発生しました: " . $e->getMessage();
            }
        } else {
            // パスワード記載の場合
            $sql = "UPDATE `members` 
        SET name_sei = :name_sei, name_mei = :name_mei, gender = :gender,
            pref_name = :pref_name, address = :address, password = :password, email = :email, updated_at = :updated_at 
        WHERE id = :id";

            try {
                $stmt = $pdo->prepare($sql);

                $stmt->execute([
                    ':id' => $_SESSION['input_items']['id'],
                    ':name_sei' => $_SESSION['input_items']['first_name'],
                    ':name_mei' => $_SESSION['input_items']['second_name'],
                    ':gender' => $insert_gender,
                    ':pref_name' => $_SESSION['input_items']['pref'],
                    ':address' => $_SESSION['input_items']['address'],
                    ':password' => $hashed_pass,
                    ':email' => $_SESSION['input_items']['mail'],
                    ':updated_at' => $created_at
                ]);

                $_SESSION['member_edit'] = "";
                $_SESSION['input_items'] = "";
            } catch (PDOException $e) {
                echo "エラーが発生しました: " . $e->getMessage();
            }
        }
    } else {
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

            $_SESSION['input_items'] = "";
        } catch (PDOException $e) {
            echo "エラーが発生しました: " . $e->getMessage();
        }
    }
}
?>