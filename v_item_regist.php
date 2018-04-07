<?php
if (!isset($_SESSION)) {
  session_start();
}
?>
<h3>アイテム登録</h3>
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
  <p><input type="text" name="item_name" placeholder="アイテム名" required><span>(最大30文字)<span></p>
  <!-- カタログ選択フォーム -->
  <p>
    <select name="catalog_id" size="1" required>
      <option value="">カタログを選択</option>
      <?php foreach($catalog_list as $row){ ?>
        <option value="<?php echo $row['catalog_id'] ?>"><?php echo $row['catalog_name'] ?></option>
      <?php } ?>
    </select>
  </p>
  <!-- コメント入力フォーム -->
  <p><textarea name="item_comment" rows="3" cols="20" placeholder="アイテムの説明文" maxlength="500" required></textarea><span>(最大500文字)<span></p>

  <!-- 画像入力フォーム -->
  <div class="preview"><img src="images/no_image.jpg"></div>
  <label for="file_image">＋画像を選択(任意)</label>
  <p>※.jpg または .pngのみ</p>
  <p><input type="file" id="file_image" name="img" style="display:none;"></p>

  <!-- リンクURL入力フォーム1 -->
  <p><input type="text" placeholder="リンク1 例）https://catamake.com" name="link1_url" maxlength="500"><span>(任意)<span></p>
  <!-- リンクURL入力フォーム2 -->
  <p><input type="text" placeholder="リンク2 例）https://catamake.com" name="link2_url" maxlength="500"><span>(任意)<span></p>

  <p><input type="submit" value="登録"</p>
</form>
<p>※アイテムは合計100個まで登録できます(あと<?php echo 100-count($item_list) ?>個)</p>
<?php
}
?>
