<?php

require_once ( __DIR__ . '/functions.php');
require_once ( __DIR__ . '/config.php');

// DBに接続
$dbh = get_db_connect ();
$catalog_id = $_POST['catalog_id'];
delete_item_by_catalog($dbh, $catalog_id);
delete_favo_by_catalog($dbh, $catalog_id);
delete_catalog($dbh, $catalog_id);
