<?php
if (!isset($_SESSION)) {
  session_start();
}

require_once ( __DIR__ . '/config.php');
require_once ( __DIR__ . '/functions.php');
require_once ( __DIR__ . '/twitteroauth/autoload.php');

$dbh = get_db_connect ();

use Abraham\TwitterOAuth\TwitterOAuth;

//login.phpでセットしたセッション
$request_token = [];
$request_token['oauth_token'] = $_SESSION['oauth_token'];
$request_token['oauth_token_secret'] = $_SESSION['oauth_token_secret'];

//Twitterから返されたOAuthトークンと、あらかじめlogin.phpで入れておいたセッション上のものと一致するかをチェック
if (isset($_REQUEST['oauth_token']) && $request_token['oauth_token'] !== $_REQUEST['oauth_token']) {
    die( 'Error!' );
}

//Twitterからアクセストークンを取得する
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $request_token['oauth_token'], $request_token['oauth_token_secret']);
$access_token = $connection->oauth("oauth/access_token", array("oauth_verifier" => $_REQUEST['oauth_verifier']));

//取得したアクセストークンでユーザ情報を取得
$user_connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
$user_info = $user_connection->get('account/verify_credentials');

//適当にユーザ情報を取得
$id = $user_info->id;
$name = $user_info->name;
$screen_name = $user_info->screen_name;
$profile_image_url_https = $user_info->profile_image_url_https;
$text = $user_info->status->text;


//各値をセッションに入れる
$_SESSION['access_token'] = $access_token;
$_SESSION['id'] = $id;
$_SESSION['user_name'] = html_escape($name);
$_SESSION['screen_name'] = html_escape($screen_name);
$_SESSION['profile_image_url_https'] = $profile_image_url_https;
$_SESSION['text'] = html_escape($text);

// ユーザー重複チェック
$result = count_double_user($dbh, $_SESSION['id']);
if($result == 0){
  // 未登録⇒ユーザー登録
  insert_user($dbh, $_SESSION['id'], $_SESSION['screen_name'], $_SESSION['user_name']);
}else{
  // 登録済み⇒screen_nameが変わっているかもしれないので一応上書き
  update_screen_name($dbh, $_SESSION['id'], $_SESSION['screen_name'], $_SESSION['user_name']);
}

//セッションIDをリジェネレート
session_regenerate_id();

//元いたページへリダイレクト
$redirect_url = $site_url.'/'.$_SESSION['request_url'];
header( "location: $redirect_url" );
