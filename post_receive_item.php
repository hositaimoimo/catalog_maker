<?php
if (!isset($_SESSION)) {
  session_start();
}

require_once ( __DIR__ . '/functions.php');
require_once ( __DIR__ . '/config.php');

$dbh = get_db_connect ();

$err = [];
$img = $_FILES['img'];
$image_path = '';

if($img['name'] == '') {
  // 添付がなければ画像処理はスキップ
}else{
  // 画像の処理
  $type = exif_imagetype($img['tmp_name']);

  if($type !== IMAGETYPE_JPEG && $type !== IMAGETYPE_PNG){
    // 対象外。何もしない
  }else{
    // リサイズしてjpgで保存
    $image_path = '/user_images/' .md5(uniqid(mt_rand(), true)). '.jpg';
    $output_path = __DIR__. $image_path;
    png2jpg($img['tmp_name'], $output_path, 80);
  }
}

$item_name = html_escape($_POST['item_name']);
$catalog_id = html_escape($_POST['catalog_id']);
$item_comment = nl2br(html_escape($_POST['item_comment']));
// $image_path  画像表示用のパス
$link1_url = html_escape($_POST['link1_url']);
$link2_url = html_escape($_POST['link2_url']);
$request_url = $_SESSION['request_url'];

insert_item($dbh, $item_name, $catalog_id, $item_comment, $image_path, $link1_url, $link2_url);
update_catalog($dbh, $catalog_id);

// 登録完了フラグ
$_SESSION['item_finish'] = $item_name;

// 登録したカタログの取得
$user_id = get_user_id($dbh, $_SESSION['screen_name']);
$_SESSION['regist_catalog'] = get_catalog($dbh, $user_id, $catalog_id);

// 最後に元のページに戻る
header( 'location: '. SITE_URL.'/'.$request_url );
