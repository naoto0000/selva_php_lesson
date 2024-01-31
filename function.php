<?php 
session_start();
ini_set('log_errors', 'on');  //ログを取るか
ini_set('error_log', 'php_error.log');  //ログの出力ファイルを指定

// ini_set('display_errors', 'on');

try {
    $pdo = new PDO('mysql:host=localhost;dbname=selva_php_lesson', "root", "fukuoka7010");
} catch (PDOException $e) {
    echo $e->getMessage();
}

?>