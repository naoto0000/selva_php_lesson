<?php
require_once("../function.php");
require_once("../pref_cotegory.php");

if ($_SESSION['admin_login'] == "") {
    header('Location: login.php');
    exit();
}

// ページネーション関連
// ページ数を取得する。GETでページが渡ってこなかった時（最初のページ）は$pageに１を格納する。
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

if ($page > 1) {
    $start = ($page * 10) - 10;
} else {
    $start = 0;
}

$member_search_id = $_GET['member_search_id'];
$member_search_gender_man = $_GET['member_search_gender_man'];
$member_search_gender_woman = $_GET['member_search_gender_woman'];
$member_search_pref = $_GET['member_search_pref'];
$member_search_freeword = $_GET['member_search_freeword'];

// 条件を格納する配列
$conditions = [];

// IDの検索条件
if ($member_search_id) {
    $conditions[] = "id = :id";
}

// 性別の検索条件
if ($member_search_gender_man && $member_search_gender_woman) {
    // 男性または女性のいずれかを指定している場合は条件を追加
    $conditions[] = "(gender = :gender OR gender = :gender2)";
} elseif ($member_search_gender_man) {
    // 男性のみを指定している場合
    $conditions[] = "gender = :gender";
} elseif ($member_search_gender_woman) {
    // 女性のみを指定している場合
    $conditions[] = "gender = :gender2";
}

// 都道府県の検索条件
if ($member_search_pref) {
    $conditions[] = "pref_name = :pref_name";
}
// フリーワードの検索条件
if ($member_search_freeword) {
    $conditions[] = "(name_sei LIKE :word OR name_mei LIKE :word2)";
}

// 削除日時がNULLの条件
$conditions[] = "deleted_at IS NULL";

// 昇降順序の設定
$orders = "";
// 昇降順序変更
if (isset($_GET['search_order_submit'])) {
    // 既に一度クリックがあった場合
    if ($_SESSION['search_order'] == 1) {
        $orders = "ORDER BY id DESC";
        $_SESSION['search_order'] = "";
    } else {
        $orders = "ORDER BY id ASC";
        $_SESSION['search_order'] = 1;
    }
    // 他ページに移動時にセッションを維持する
} elseif ($_SESSION['search_order'] == 1) {
    $orders = "ORDER BY id ASC";
    // 初期状態の設定
} else {
    $orders = "ORDER BY id DESC";
}

// 条件を結合して基本クエリを構築
$search_sql =
    "SELECT * 
    FROM `members` 
    WHERE " . implode(" AND ", $conditions);

// カウント用
// =========
$search_members = $pdo->prepare($search_sql);

// 事前にバインドするパラメータを初期化
$params = [];

// IDの検索条件
if ($member_search_id) {
    $params[':id'] = $member_search_id;
}

// 性別の検索条件
if ($member_search_gender_man && $member_search_gender_woman) {
    // 男性または女性のいずれかを指定している場合は条件を追加
    $params[':gender'] = $member_search_gender_man;
    $params[':gender2'] = $member_search_gender_woman;
} elseif ($member_search_gender_man) {
    // 男性のみを指定している場合
    $params[':gender'] = $member_search_gender_man;
} elseif ($member_search_gender_woman) {
    // 女性のみを指定している場合
    $params[':gender2'] = $member_search_gender_woman;
}

// 都道府県の検索条件
if ($member_search_pref) {
    $params[':pref_name'] = $member_search_pref;
}
// フリーワードの検索条件
if ($member_search_freeword) {
    $params[':word'] = "%{$member_search_freeword}%";
    $params[':word2'] = "%{$member_search_freeword}%";
}

// SQL クエリを実行
if (empty($params)) {
    // すべての条件が空の場合
    $search_members->execute();
} else {
    // 1つ以上の条件がある場合
    $search_members->execute($params);
}

$member_count = $search_members->rowCount();

// 表示用
// =====
$limit_sql = $search_sql . " " . $orders . " LIMIT {$start},10";

$search_members = $pdo->prepare($limit_sql);

// 事前にバインドするパラメータを初期化
$params = [];

// IDの検索条件
if ($member_search_id) {
    $params[':id'] = $member_search_id;
}

// 性別の検索条件
if ($member_search_gender_man && $member_search_gender_woman) {
    // 男性または女性のいずれかを指定している場合は条件を追加
    $params[':gender'] = $member_search_gender_man;
    $params[':gender2'] = $member_search_gender_woman;
} elseif ($member_search_gender_man) {
    // 男性のみを指定している場合
    $params[':gender'] = $member_search_gender_man;
} elseif ($member_search_gender_woman) {
    // 女性のみを指定している場合
    $params[':gender2'] = $member_search_gender_woman;
}

// 都道府県の検索条件
if ($member_search_pref) {
    $params[':pref_name'] = $member_search_pref;
}
// フリーワードの検索条件
if ($member_search_freeword) {
    $params[':word'] = "%{$member_search_freeword}%";
    $params[':word2'] = "%{$member_search_freeword}%";
}

// SQL クエリを実行
if (empty($params)) {
    // すべての条件が空の場合
    $search_members->execute();
} else {
    // 1つ以上の条件がある場合
    $search_members->execute($params);
}

$display_result = $search_members->fetchAll(PDO::FETCH_ASSOC);

$max_page = ceil($member_count / 10);

// ページの数字ボタンを最大3個のみ表示
if ($page == 1 || $page == $max_page) {
    $range = 2;
} else {
    $range = 1;
}
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
    <form action="" method="get">
        <header class="admin_header">
            <div class="admin_header_left">
                <h2>会員一覧</h2>
            </div>
            <div class="admin_header_right">
                <button type="button" onclick="location.href='index.php'" class="btn member_back_btn">トップに戻る</button>
            </div>
        </header>

        <main>
            <div class="container">
                <div class="member_search">
                    <table>
                        <tr>
                            <td class="member_search_category">ID</td>
                            <td class="member_search_contents"><input type="text" name="member_search_id" value="<?php echo $member_search_id ?>"></td>
                        </tr>
                        <tr>
                            <td class="member_search_category">性別</td>
                            <td class="member_search_contents">
                                <?php if ($member_search_gender_man && $member_search_gender_woman) : ?>
                                    <input type="checkbox" name="member_search_gender_man" value="1" checked />
                                    <label for="男性">男性</label>
                                    <input type="checkbox" name="member_search_gender_woman" value="2" checked />
                                    <label for="女性">女性</label>
                                <?php elseif ($member_search_gender_man) : ?>
                                    <input type="checkbox" name="member_search_gender_man" value="1" checked />
                                    <label for="男性">男性</label>
                                    <input type="checkbox" name="member_search_gender_woman" value="2" />
                                    <label for="女性">女性</label>
                                <?php elseif ($member_search_gender_woman) : ?>
                                    <input type="checkbox" name="member_search_gender_man" value="1" />
                                    <label for="男性">男性</label>
                                    <input type="checkbox" name="member_search_gender_woman" value="2" checked />
                                    <label for="女性">女性</label>
                                <?php else : ?>
                                    <input type="checkbox" name="member_search_gender_man" value="1" />
                                    <label for="男性">男性</label>
                                    <input type="checkbox" name="member_search_gender_woman" value="2" />
                                    <label for="女性">女性</label>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="member_search_category">都道府県</td>
                            <td class="member_search_contents">
                                <select name="member_search_pref" id="" class="member_pref">
                                    <option value=""></option>
                                    <?php foreach ($prefCotegory as $row) {
                                        if ($row['value'] == $member_search_pref) {
                                            echo '<option value="' . $row['value'] . '" selected>' . $row['value'] . '</option>';
                                        } else {
                                            echo '<option value="' . $row['value'] . '">' . $row['value'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="member_search_category">フリーワード</td>
                            <td class="member_search_contents"><input type="text" name="member_search_freeword" value="<?php echo $member_search_freeword ?>"></td>
                        </tr>
                    </table>
                </div>

                <div class="member_search_btn">
                    <input type="submit" name="member_search_submit" value="検索する" class="member_search_submit">
                </div>

                <div class="member_display">
                    <table>
                        <?php if ($_SESSION['search_order'] == 1) : ?>
                            <tr>
                                <th>ID<input type="submit" name="search_order_submit" value="▲" class="search_order_submit"></th>
                                <th>氏名</th>
                                <th>性別</th>
                                <th>住所</th>
                                <th>登録日時<input type="submit" name="search_order_submit" value="▲" class="search_order_submit"></th>
                            </tr>
                        <?php else : ?>
                            <tr>
                                <th>ID<input type="submit" name="search_order_submit" value="▼" class="search_order_submit"></th>
                                <th>氏名</th>
                                <th>性別</th>
                                <th>住所</th>
                                <th>登録日時<input type="submit" name="search_order_submit" value="▼" class="search_order_submit"></th>
                            </tr>
                        <?php endif; ?>
                        <?php foreach ($display_result as $display_items) : ?>
                            <?php
                            $display_name = $display_items['name_sei'] . $display_items['name_mei'];
                            $display_gender = "";
                            $display_address = $display_items['pref_name'] . $display_items['address'];
                            if ($display_items['gender'] == 1) {
                                $display_gender = "男性";
                            } else {
                                $display_gender = "女性";
                            }
                            $originalDate = $display_items['created_at'];
                            $dateTime = new DateTime($originalDate);
                            $display_items['created_at'] = $dateTime->format('Y/n/j');
                            ?>
                            <tr>
                                <td><?php echo $display_items['id'] ?></td>
                                <td><?php echo $display_name ?></td>
                                <td><?php echo $display_gender ?></td>
                                <td><?php echo $display_address ?></td>
                                <td><?php echo $display_items['created_at'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>

                <div class="member_pagenation">
                    <!-- 前へ -->
                    <?php if ($page >= 2) : ?>
                        <a href="?page=<?php echo $page - 1 ?>&member_search_id=<?php echo $member_search_id; ?>&member_search_gender_man=<?php echo $member_search_gender_man; ?>&member_search_gender_woman=<?php echo $member_search_gender_woman; ?>&member_search_pref=<?php echo $member_search_pref; ?>&member_search_freeword=<?php echo $member_search_freeword; ?>" class="page_a_back">前へ＞</a>
                    <?php endif; ?>

                    <!-- ページ選択 -->
                    <?php for ($i = 1; $i <= $max_page; $i++) : ?>
                        <?php if ($i >= $page - $range && $i <= $page + $range) : ?>
                            <?php if ($i == $page) : ?>
                                <span class="now_page_number"><?php echo $i; ?></span>
                            <?php else : ?>
                                <a href="?page=<?php echo $i; ?>&member_search_id=<?php echo $member_search_id; ?>&member_search_gender_man=<?php echo $member_search_gender_man; ?>&member_search_gender_woman=<?php echo $member_search_gender_woman; ?>&member_search_pref=<?php echo $member_search_pref; ?>&member_search_freeword=<?php echo $member_search_freeword; ?>" class="page_number"><?php echo $i; ?></a>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <!-- 次へ -->
                    <?php if ($page < $max_page) : ?>
                        <a href="?page=<?php echo $page + 1 ?>&member_search_id=<?php echo $member_search_id; ?>&member_search_gender_man=<?php echo $member_search_gender_man; ?>&member_search_gender_woman=<?php echo $member_search_gender_woman; ?>&member_search_pref=<?php echo $member_search_pref; ?>&member_search_freeword=<?php echo $member_search_freeword; ?>" class="page_a_next">次へ＞</a>
                    <?php endif; ?>
                </div>

            </div>
        </main>
    </form>
</body>
</html>