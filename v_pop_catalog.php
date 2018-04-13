<?php
if (!isset($_SESSION)) {
  session_start();
}

// 訪問者のユーザーIDを取得
 $my_user_id = get_user_id($dbh, $_SESSION['screen_name']);
?>

<div class="panel panel-success panel_main">
  <div class="panel-heading">
    <h2 class="panel-title">人気カタログ</h2>
  </div>
  <div class="panel-body">
    <?php if($pop_catalog_list == NULL){ ?>
      <p>カタログがありません。</p>
    <?php }else{?>
      <ul class="catalog_list">
        <?php
        foreach($pop_catalog_list as $row){
          $screen_name = get_screen_name($dbh, $row['user_id']);
          $user_name = get_user_name($dbh, $screen_name);
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
                <div class="maker3">
                  <p><a href="<?php echo SITE_URL. '/'. $screen_name; ?>"><?php echo $user_name; ?></a>&nbsp;作</p>
                </div>
                <div class="updated">
                  <i class="glyphicon glyphicon-repeat updated_icon"></i>&nbsp;<?php echo date('Y年n月j日', strtotime($row['updated'])); ?>
                </div>
                <div class="icons">
                  <?php
                  if(isset($_SESSION['access_token'])){
                    // ログイン中
                    $favo_class = 'favo btn btn-default';
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
