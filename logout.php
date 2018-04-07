<?php
if (!isset($_SESSION)) {
  session_start();
}


//セッション変数を全て解除
$_SESSION = [];

//セッションクッキーの削除
if (isset($_COOKIE["PHPSESSID"])) {
    setcookie("PHPSESSID", '', time() - 1800, '/');
}

//セッションを破棄する
session_destroy();

header( 'location: '. SITE_URL );
