<?php
if (!isset($_SESSION)) {
  session_start();
}
?>
<h3>カタログ作成</h3>
<?php
if($catalog_list == NULL){
  echo '<p>まずはカタログを作成しましょう！</p>';
}
if(count($catalog_list) >= 10){
?>
<p>これ以上、カタログを作成できません。</p>
<p>新たにカタログを作成したい場合は、既存のカタログを削除してください。</p>
<?php
}else{
?>
<form action="post_receive_catalog.php" method="POST">
  <!-- カタログ名入力フォーム -->
  <p><input type="text" name="catalog_name" placeholder="カタログ名" maxlength="30" required><span>(最大30文字)<span></p>
  <!-- コメント入力フォーム -->
  <p><textarea name="catalog_comment" rows="3" cols="20" placeholder="カタログの説明文" maxlength="300" required></textarea><span>(最大300文字)<span></p>

  <p><input type="submit" value="作成"</p>
</form>
<p>※カタログは10個まで作成できます(あと<?php echo 10-count($catalog_list) ?>個)</p>
<?php
}
?>
