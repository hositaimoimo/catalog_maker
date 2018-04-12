<?php
if (!isset($_SESSION)) {
  session_start();
}

require_once ( __DIR__ . '/functions.php');
require_once ( __DIR__ . '/config.php');

$dbh = get_db_connect ();

$catalog_name = html_escape($_POST['catalog_name']);
$user_id = get_user_id($dbh, $_SESSION['request_url']);
$catalog_comment = nl2br(html_escape($_POST['catalog_comment']));
$request_url = $_SESSION['request_url'];

insert_catalog($dbh, $catalog_name, $user_id, $catalog_comment);

// 作成完了フラグ
$_SESSION['catalog_finish'] = $catalog_name;

// 最後に元のページに戻る
header( 'location: '. SITE_URL.'/'.$request_url );
