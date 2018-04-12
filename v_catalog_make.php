<?php
if (!isset($_SESSION)) {
  session_start();
}
?>

<div class="panel panel-success panel_main">
  <div class="panel-heading">
    <h2 class="panel-title">カタログ作成</h2>
  </div>
  <div class="panel-body">
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
        <p><input class="input_area" type="text" name="catalog_name" placeholder="カタログ名" maxlength="30" required></p>
        <!-- コメント入力フォーム -->
        <p><textarea class="input_area" name="catalog_comment" rows="5" cols="20" placeholder="カタログの説明文" maxlength="300" required></textarea></p>

        <p class="catalog_make_button"><input type="submit" class="btn btn-success" value="作成"></p>
      </form>
    <?php
    }
    ?>
    <p class="sub">※カタログは10個まで作成できます(あと<?php echo 10-count($catalog_list) ?>個)</p>
  </div>
</div>
