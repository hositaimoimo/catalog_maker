<?php
if (!isset($_SESSION)) {
  session_start();
}

$profile_image_url_https = get_user_image($dbh, $screen_name);
?>

<div class="panel panel-success panel_main">
  <div class="panel-heading">
    <h1 class="panel-title"><?php echo $catalog['catalog_name']; ?></h1>
  </div>
  <div class="panel-body">
    <div class="catalog_comment">
      <p><?php echo $catalog['catalog_comment']; ?></p>
    </div>
    <div class="profile_sub">
      <div class="row">
        <div class="col-xs-3">
          <div class="bs-component maker2">
            <img src="<?php echo $profile_image_url_https; ?>">
          </div>
        </div>
        <div class="col-xs-9">
          <div class="bs-component maker1">
            <span>カタログ作成者</span>
          </div>
          <div class="bs-component maker2">
            <a href="<?php echo SITE_URL. '/'. $screen_name; ?>"><?php echo $user_name; ?></a>
          </div>
        </div>
      </div>
    </div>
    <div class="icons">
      <?php
      if(isset($_SESSION['access_token'])){
        // ログイン中
        $favo_class = 'favo btn btn-default';
        // 訪問者のユーザーIDを取得
        $my_user_id = get_user_id($dbh, $_SESSION['screen_name']);
        $is_favo = check_favo($dbh, $my_user_id, $catalog['catalog_id']);
        if($is_favo == 0){
          // 0…ファボ無し
        }else{
          // 1…ファボ有り
          $favo_class = 'favo btn btn-warning';
        }
        ?>
        <button class="<?php echo $favo_class ?>" value="<?php echo $catalog['catalog_id'].','.$my_user_id.','.$is_favo ?>" id="<?php echo 'favo_btn'. $catalog['catalog_id'] ?>">
          <i id="<?php echo 'favo_icon'. $catalog['catalog_id'] ?>"class="glyphicon glyphicon-star"></i>
        </button>
      <?php
      }
      if($screen_name == $_SESSION['screen_name']){
      ?>
      <?php
      $icon_class = 'fa fa-lock';
      if($catalog['catalog_lock'] == 1){
        $icon_class = 'fa fa-unlock-alt';
      }
      ?>
        <button class="lock btn btn-default" value="<?php echo $catalog['catalog_id'].','.$catalog['catalog_lock'] ?>" id="<?php echo 'lock_btn'. $catalog['catalog_id'] ?>"><i id="<?php echo 'lock_icon'. $catalog['catalog_id'] ?>" class="<?php echo $icon_class ?>"></i></button>
        <button class="delete_catalog_alert btn btn-danger" name="catalog_page" value="<?php echo $catalog['catalog_id']; ?>"><i class="glyphicon glyphicon-trash"></i></button>
      <?php } ?>
    </div>
  </div>
</div>

<?php
$tweet_comment = "このカタログ、気になる！";
if($screen_name == $_SESSION['screen_name']){
  // 自分のカタログの場合
  $tweet_comment = "こんなカタログ、作ってみました！";
}
?>
<a class="btn twitter twi_btn" target="_blank"
 href="http://twitter.com/share?url=<?php echo SITE_URL.'/'.$request_url; ?>&text=<?php echo $tweet_comment; ?>%0a『<?php echo $catalog['catalog_name']; ?>』%0a&hashtags=カタログメーカー">
 <i class="fa fa-lg fa-twitter"></i>&nbsp;このカタログをシェア（ツイート）
</a>

<div class="panel panel-warning panel_main">
  <div class="panel-heading">
    <h2 class="panel-title">登録アイテム</h2>
  </div>
  <div class="panel-body">
    <?php if($catalog_item == NULL){ ?>
      <p>登録されたアイテムがありません。</p>
    <?php }else{?>
      <ul class="item_list">
        <?php
        foreach($catalog_item as $row){
        ?>
          <li id="item<?php echo $row['item_id']; ?>">
            <div class="panel panel-default panel_sub">
              <div class="panel-heading">
                <div class="item_name">
                  <h3 class="panel-title"><?php echo $row['item_name']; ?></h3>
                </div>
              </div>
              <div class="panel-body">
                <?php
                if($row['image_path'] != ""){
                ?>
                  <div class="item_image">
                    <img src="<?php echo $row['image_path']; ?>">
                  </div>
                <?php } ?>
                <div class="item_comment">
                  <?php echo $row['item_comment']; ?>
                </div>
                <?php
                if($row['link1_url'] != ""){
                ?>
                  <div class="link1_url">
                    <span class="sub">リンク1&nbsp;</span>
                    <a href="<?php echo $row['link1_url']; ?>" target="_blank"><?php echo $row['link1_url']; ?></a>
                  </div>
                <?php } ?>
                <?php
                if($row['link2_url'] != ""){
                ?>
                  <div class="link2_url">
                    <span class="sub">リンク2&nbsp;</span>
                    <a href="<?php echo $row['link2_url']; ?>" target="_blank"><?php echo $row['link2_url']; ?></a>
                  </div>
                <?php } ?>
                <?php
                if($screen_name == $_SESSION['screen_name']){
                ?>
                  <div class="icons">
                    <button class="delete_item_alert btn btn-danger" value="<?php echo $row['item_id']; ?>"><i class="glyphicon glyphicon-trash"></i></button>
                  </div>
                <?php } ?>
              </div>
            </div>
          </li>
        <?php } ?>
      </ul>
    <?php } ?>
  </div>
</div>
<a class="btn twitter twi_btn" target="_blank"
 href="http://twitter.com/share?url=<?php echo SITE_URL.'/'.$request_url; ?>&text=このカタログ、気になる！%0a『<?php echo $catalog['catalog_name']; ?>』%0a&hashtags=カタログメーカー">
 <i class="fa fa-lg fa-twitter"></i>&nbsp;このカタログをシェア（ツイート）
</a>
<div class="panel panel-success panel_main">
  <div class="panel-heading">
    <h2 class="panel-title"><?php echo $user_name; ?>&nbsp;の他のカタログ</h2>
  </div>
  <div class="panel-body">
    <?php if($catalog_list == NULL){ ?>
      <p>他のカタログがありません。</p>
    <?php }else{?>
      <ul class="catalog_list">
        <?php
        foreach($catalog_list as $row){
          $screen_name = get_screen_name($dbh, $row['user_id']);
          $url = SITE_URL.'/'.$screen_name.'/c'.$row['catalog_id']
        ?>
          <li id="catalog<?php echo $row['catalog_id']; ?>">
            <div class="panel panel-default panel_sub">
              <div class="panel-heading">
                <div class="catalog_name">
                  <a href="<?php echo $url ?>"><h3 class="panel-title"><?php echo $row['catalog_name']; ?></h3></a>
                </div>
              </div>
              <div class="panel-body">
                <div class="catalog_comment">
                  <p><?php echo $row['catalog_comment']; ?></p>
                </div>
                <div class="updated">
                  <i class="glyphicon glyphicon-repeat updated_icon"></i>&nbsp;<?php echo date('Y年n月j日', strtotime($row['updated'])); ?>
                </div>
                <div class="icons">
                  <?php
                  if(isset($_SESSION['access_token'])){
                    // ログイン中
                    $favo_class = 'favo btn btn-default';
                    // 訪問者のユーザーIDを取得…は上でもしてるから省略
                    // $my_user_id = get_user_id($dbh, $_SESSION['screen_name']);
                    $is_favo = check_favo($dbh, $my_user_id, $row['catalog_id']);
                    if($is_favo == 0){
                      // 0…ファボ無し
                    }else{
                      // 1…ファボ有り
                      $favo_class = 'favo btn btn-warning';
                    }
                  ?>
                    <button class="<?php echo $favo_class ?>" value="<?php echo $row['catalog_id'].','.$my_user_id.','.$is_favo ?>" id="<?php echo 'favo_btn'. $row['catalog_id'] ?>">
                      <i id="<?php echo 'favo_icon'. $row['catalog_id'] ?>"class="glyphicon glyphicon-star"></i>
                    </button>
                  <?php
                  }
                  if($screen_name == $_SESSION['screen_name']){
                    $icon_class = 'fa fa-lock';
                    if($row['catalog_lock'] == 1){
                      $icon_class = 'fa fa-unlock-alt';
                    }
                  ?>
                    <button class="lock btn btn-default" value="<?php echo $row['catalog_id'].','.$row['catalog_lock'] ?>" id="<?php echo 'lock_btn'. $row['catalog_id'] ?>"><i id="<?php echo 'lock_icon'. $row['catalog_id'] ?>" class="<?php echo $icon_class ?>"></i></button>
                    <button class="delete_catalog_alert btn btn-danger" value="<?php echo $row['catalog_id']; ?>"><i class="glyphicon glyphicon-trash"></i></button>
                  <?php } ?>
                </div>
              </div>
            </div>
          </li>
        <?php } ?>
      </ul>
    <?php } ?>
  </div>
</div>
