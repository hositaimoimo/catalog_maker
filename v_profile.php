<?php
if (!isset($_SESSION)) {
  session_start();
}

$user_name = get_user_name($dbh, $request_url);
$profile_image_url_https = get_user_image($dbh, $request_url);
?>
<!-- profile -->
<div class="profile">
  <div class="row">
    <div class="col-xs-3">
      <div class="bs-component maker_img">
        <img src="<?php echo $profile_image_url_https; ?>">
      </div>
    </div>
    <div class="col-xs-9">
      <div class="bs-component maker1">
        <span>カタログ作成者</span>
      </div>
      <div class="bs-component maker2">
        <h1><?php echo $user_name; ?></h1>
        <a href="<?php echo 'https://twitter.com/'.$request_url ?>" targe="_blank">@<?php echo $request_url; ?></a>
      </div>
    </div>
  </div>
</div>

<!-- Notice -->
<?php if($_SESSION['catalog_finish'] != NULL){ ?>
  <div class="bs-component">
    <div class="alert alert-dismissible alert-info">
      <button type="button" class="close" data-dismiss="alert">&times;</button>
      <p>カタログ『<?php echo $_SESSION['catalog_finish'] ?>』を作成しました！</p>
      <small>※ロックを解除するまでは非公開です</small>
    </div>
  </div>
<?php
  $_SESSION['catalog_finish'] = NULL;
}
if($_SESSION['item_finish'] != NULL){
  $regist_catalog = $_SESSION['regist_catalog'];
?>
  <div class="bs-component">
    <div class="alert alert-dismissible alert-info">
      <button type="button" class="close" data-dismiss="alert">&times;</button>
      <p>『<?php echo $_SESSION['item_finish']; ?>』を『<a href="<?php echo SITE_URL. '/'. $request_url. '/c'. $regist_catalog['catalog_id']; ?>" class="notice"><?php echo $regist_catalog['catalog_name']; ?></a>』登録しました！</p>
    </div>
  </div>
<?php
  $_SESSION['item_finish'] = NULL;
  $_SESSION['regist_catalog_id'] = NULL;
}
