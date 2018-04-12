<?php
if (!isset($_SESSION)) {
  session_start();
}
?>

<div class="panel panel-success panel_main">
  <div class="panel-heading">
    <h2 class="panel-title">新着アイテム(最大10件)</h2>
  </div>
  <div class="panel-body">
    <?php if($item_list == NULL){ ?>
      <p>登録されたアイテムがありません。</p>
    <?php }else{?>
      <ul class="item_list">
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
                <div class="item_list_catalog_name">
                  <p class="sub">登録カタログ：</p>
                  <p><a href="<?php echo $url ?>"><?php echo $row['catalog_name']; ?></a></p>
                </div>
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
