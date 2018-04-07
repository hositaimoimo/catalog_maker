<?php
if (!isset($_SESSION)) {
  session_start();
}



?>

<h2>『<?php echo $catalog['catalog_name']; ?>』</h2>
<p>作者：<a href="<?php echo SITE_URL. '/'. $screen_name; ?>"><?php echo $user_name; ?></a></p>

<?php
if($screen_name == $_SESSION['screen_name']){
?>
<div class="delete_catalog">
  <button class="delete_catalog_alert" name="catalog_page" value="<?php echo $catalog['catalog_id']; ?>">カタログ削除</button>
</div>
<?php } ?>

<h3>登録アイテム</h3>

<?php if($catalog_item == NULL){ ?>
  <p>登録されたアイテムがありません。</p>
<?php }else{?>
  <ul>
    <?php
    foreach($catalog_item as $row){
    ?>
      <li id="item<?php echo $row['item_id']; ?>">
        <div class="item_name">
          <?php echo $row['item_name']; ?>
        </div>
        <div class="item_image">
          <img src="<?php echo $row['image_path']; ?>">
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
