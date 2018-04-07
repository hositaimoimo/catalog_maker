<?php
if (!isset($_SESSION)) {
  session_start();
}

?>

<h3>カタログ一覧</h3>
<?php if($catalog_list == NULL){ ?>
  <p>作成されたカタログがありません。</p>
<?php }else{?>
  <ul>
    <?php
    foreach($catalog_list as $row){
      $screen_name = get_screen_name($dbh, $row['user_id']);
      $url = $screen_name.'/c'.$row['catalog_id']
    ?>
      <li id="catalog<?php echo $row['catalog_id']; ?>">
        <div class="catalog_name">
          <a href="<?php echo $url ?>"> <?php echo $row['catalog_name']; ?></a>
        </div>
        <div class="catalog_comment">
          <?php echo $row['catalog_comment']; ?>
        </div>
        <div class="updated">
          <?php echo date('Y年n月j日 更新', strtotime($row['updated'])); ?>
        </div>
        <?php
        if($screen_name == $_SESSION['screen_name']){
        ?>
        <div class="delete_catalog">
          <button class="delete_catalog_alert" value="<?php echo $row['catalog_id']; ?>">カタログ削除</button>
        </div>
        <?php } ?>
      </li>
    <?php } ?>
  </ul>
<?php } ?>
