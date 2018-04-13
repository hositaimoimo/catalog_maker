<?php
if (!isset($_SESSION)) {
  session_start();
}

require_once ( __DIR__ . '/functions.php');
require_once ( __DIR__ . '/config.php');

$dbh = get_db_connect ();




// 最後に元のページに戻る
header( 'location: '. SITE_URL.'/'.$request_url );
