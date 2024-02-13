<?php 
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
    $conditions[] = "(name_sei LIKE :word OR name_mei LIKE :word2 OR email LIKE :word3)";
}

// 削除日時がNULLの条件
$conditions[] = "deleted_at IS NULL";

// 昇降順序の設定
$orders = "";
// 昇降順序変更
if (isset($_GET['search_order_submit_id']) || isset($_GET['search_order_submit_created'])) {
    // 既に一度クリックがあった場合
    if ($_SESSION['search_order'] == 1) {
        $orders = "ORDER BY created_at DESC";
        $_SESSION['search_order'] = "";
    } else {
        $orders = "ORDER BY created_at ASC";
        $_SESSION['search_order'] = 1;
    }
    // 他ページに移動時にセッションを維持する
} elseif ($_SESSION['search_order'] == 1) {
    $orders = "ORDER BY created_at ASC";
    // 初期状態の設定
} else {
    $orders = "ORDER BY created_at DESC";
}

// 昇降順の表示設定
if (isset($_GET['search_order_submit_id'])) {
    if ($_SESSION['search_order_id'] == 1) {
        $_SESSION['search_order_id'] = "";
    } else {
        $_SESSION['search_order_id'] = 1;
    }
}
if (isset($_GET['search_order_submit_created'])) {
    if ($_SESSION['search_order_created'] == 1) {
        $_SESSION['search_order_created'] = "";
    } else {
        $_SESSION['search_order_created'] = 1;
    }
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
    $params[':word3'] = "%{$member_search_freeword}%";
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
    $params[':word3'] = "%{$member_search_freeword}%";
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
?>