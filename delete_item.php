<?php

require_once ( __DIR__ . '/functions.php');
require_once ( __DIR__ . '/config.php');

// DBに接続
$dbh = get_db_connect ();
$item_id = $_POST['item_id'];
$catalog_id = get_catalog_id_by_item($dbh, $item_id);
update_catalog($dbh, $catalog_id);
delete_item($dbh, $item_id);
