<?php
if (!isset($_SESSION)) {
  session_start();
}

$user_name = get_user_name($dbh, $request_url);

?>
<h2><?php echo $user_name; ?> さんのカタログページです。</h2>

<!-- Notice -->
<?php
if($_SESSION['catalog_finish'] != NULL){
?>
  <p>カタログ『<?php echo $_SESSION['catalog_finish'] ?>』を作成しました！</p>
<?php
  $_SESSION['catalog_finish'] = NULL;
}
if($_SESSION['item_finish'] != NULL){
?>
  <p>アイテム『<?php echo $_SESSION['item_finish'] ?>』を登録しました！</p>
<?php
  $_SESSION['item_finish'] = NULL;
}
