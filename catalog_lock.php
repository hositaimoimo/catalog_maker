<?php

require_once ( __DIR__ . '/functions.php');
require_once ( __DIR__ . '/config.php');

// DBに接続
$dbh = get_db_connect ();
$catalog_id = $_POST['catalog_id'];
$catalog_lock = $_POST['catalog_lock'];

if($catalog_lock == 1){
  // アンロック状態→ロック状態にする
  $catalog_lock = 0;
}else{
  // ロック状態→アンロック状態にする
  $catalog_lock = 1;
}
change_lock($dbh, $catalog_id, $catalog_lock);
