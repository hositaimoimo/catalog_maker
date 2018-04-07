<?php
if (!isset($_SESSION)) {
  session_start();
}
?>

<h3>新着アイテム(最大10件)</h3>
<?php if($item_list == NULL){ ?>
  <p>登録されたアイテムがありません。</p>
<?php }else{?>
  <ul>
    <?php
    $item_size = count($item_list);
    $max = 10;
    if($item_size < 10){
      $max = $item_size;
    }
    // 登録から新しいものを10件
    for($i = 0; $i < $max; $i++){
      $row = $item_list[$item_size - 1 - $i];
      $screen_name = get_screen_name($dbh, $row['user_id']);
      $url = $screen_name.'/c'.$row['catalog_id']

    ?>
      <li id="item<?php echo $row['item_id']; ?>">
        <div class="item_name">
          <?php echo $row['item_name']; ?>
        </div>
        <div class="item_image">
          <img src="<?php echo $row['image_path']; ?>">
        </div>
        <div class="catalog_name">
          カタログ：『<a href="<?php echo $url ?>"><?php echo $row['catalog_name']; ?></a>』
        </div>
        <div class="item_comment">
          <?php echo $row['item_comment']; ?>
        </div>
        <div class="link1_url">
          <?php echo $row['link1_url']; ?>
        </div>
        <div class="link2_url">
          <?php echo $row['link2_url']; ?>
        </div>
        <?php
        if($screen_name == $_SESSION['screen_name']){
        ?>
        <div class="delete_item">
          <button class="delete_item_alert" value="<?php echo $row['item_id']; ?>">アイテム削除</button>
        </div>
        <?php } ?>
      </li>
    <?php } ?>
  </ul>
<?php } ?>
