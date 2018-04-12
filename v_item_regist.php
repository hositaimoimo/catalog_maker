<?php
if (!isset($_SESSION)) {
  session_start();
}
?>

<div class="panel panel-success panel_main">
  <div class="panel-heading">
    <h2 class="panel-title">アイテム登録</h2>
  </div>
  <div class="panel-body">
    <?php
    if(count($item_list) >= 100){
    ?>
      <p>これ以上、アイテムを登録できません。</p>
      <p>新たにアイテムを登録したい場合は、既存のアイテムを削除してください。</p>
    <?php
    }else{
    ?>
      <form action="post_receive_item.php" enctype="multipart/form-data" method="POST">
        <!-- アイテム名入力フォーム -->
        <p><input class="input_area" type="text" name="item_name" placeholder="アイテム名" required></p>
        <!-- カタログ選択フォーム -->
        <p>
          <select class="input_area" name="catalog_id" size="1" required>
            <option value="">カタログを選択</option>
            <?php foreach($catalog_list as $row){ ?>
              <option value="<?php echo $row['catalog_id'] ?>"><?php echo $row['catalog_name'] ?></option>
            <?php } ?>
          </select>
        </p>
        <!-- コメント入力フォーム -->
        <p><textarea class="input_area" name="item_comment" rows="5" cols="20" placeholder="アイテムの説明文" maxlength="500" required></textarea></p>

        <!-- 画像入力フォーム -->
        <div class="input_area preview"><img src="images/no_image.jpg"></div>
        <div class="image_button_frame input_area">
          <label class="select_image_button" for="file_image">＋画像を選択</label><span class="sub">&nbsp;(任意)</span>
          <p class="sub">※.jpg または .pngのみ</p>
          <p><input type="file" id="file_image" name="img" style="display:none;"></p>
        </div>

        <!-- リンクURL入力フォーム1 -->
        <p><input type="text" class="input_area_link" placeholder="リンク1" name="link1_url" maxlength="500"><span class="sub">&nbsp;(任意)</span></p>
        <!-- リンクURL入力フォーム2 -->
        <p><input type="text" class="input_area_link" placeholder="リンク2" name="link2_url" maxlength="500"><span class="sub">&nbsp;(任意)</span></p>
        <p class="item_regist_button"><input type="submit" class="btn btn-success" value="登録"></p>
      </form>
    <?php
    }
    ?>
    <p class="sub">※アイテムは合計100個まで登録できます(あと<?php echo 100-count($item_list) ?>個)</p>
  </div>
</div>
