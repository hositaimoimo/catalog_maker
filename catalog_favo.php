<?php

require_once ( __DIR__ . '/functions.php');
require_once ( __DIR__ . '/config.php');

// DBに接続
$dbh = get_db_connect ();
$catalog_id = $_POST['catalog_id'];
$my_user_id = $_POST['my_user_id'];
$is_favo = $_POST['is_favo'];
$value = 0;
if($is_favo == 1){
  // ファボ状態→レコード削除
  minus_favo($dbh, $catalog_id, $my_user_id);
  $value = -1;
}else{
  // アンファボ状態→レコード追加
  plus_favo($dbh, $catalog_id, $my_user_id);
  $value = 1;
}
update_favo($dbh, $catalog_id, $value);
