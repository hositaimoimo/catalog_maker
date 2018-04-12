<?php
if (!isset($_SESSION)) {
  session_start();
}

?>

<div class="panel panel-success panel_main">
  <div class="panel-heading">
    <h2 class="panel-title">カタログ一覧</h1>
  </div>
  <div class="panel-body">
    <?php if($catalog_list == NULL){ ?>
      <p>作成されたカタログがありません。</p>
    <?php }else{?>
      <ul class="catalog_list">
        <?php
        foreach($catalog_list as $row){
          $screen_name = get_screen_name($dbh, $row['user_id']);
          $url = SITE_URL. '/'.$screen_name.'/c'.$row['catalog_id']
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
                <?php
                if($screen_name == $_SESSION['screen_name']){
                ?>
                  <div class="icons">
                    <?php
                    $icon_class = 'fa fa-lock';
                    if($row['catalog_lock'] == 1){
                      $icon_class = 'fa fa-unlock-alt';
                    }
                    ?>
                    <button class="favo btn btn-warning" value=""><i class="glyphicon glyphicon-star-empty"></i></button>
                    <button class="lock btn btn-default" value="<?php echo $row['catalog_id'].','.$row['catalog_lock'] ?>" id="<?php echo 'lock_btn'. $row['catalog_id'] ?>"><i id="<?php echo 'lock_icon'. $row['catalog_id'] ?>" class="<?php echo $icon_class ?>"></i></button>
                    <button class="delete_catalog_alert btn btn-danger" value="<?php echo $row['catalog_id']; ?>"><i class="glyphicon glyphicon-trash"></i></button>
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
