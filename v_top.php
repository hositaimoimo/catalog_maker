<?php
if (!isset($_SESSION)) {
  session_start();
}
?>
<a class="btn twitter twi_btn" href="<?php echo SITE_URL. '/login' ?>"><i class="fa fa-lg fa-twitter"></i>&nbsp;ログイン</a>

<div class="panel panel-success panel_main">
  <div class="panel-heading">
    <h1 class="panel-title">お気に入りのものを集めて、自分だけのカタログを作っちゃおう！</h1>
  </div>
  <div class="panel-body">
    <ul>
      <li>作品を並べてギャラリーに。</li>
      <li>お気に入りのお店・グッズなどの紹介に。</li>
      <li>ハマってるものの共有に。</li>
      <li>未知の逸品さがしに。</li>
    </ul>
    <div class="goto_pop">
      <p><a href="<?php echo SITE_URL. '/pop'; ?>">人気のカタログを見てみる</a></p>
    </div>
  </div>
</div>

<div class="panel panel-success panel_main">
  <div class="panel-heading">
    <h2 class="panel-title">カタログメーカーの使い方</h1>
  </div>
  <div class="panel-body">
    <div class="panel panel-danger panel_sub">
      <div class="panel-heading">
        <h3 class="panel-title">カタログを作る（要ログイン）</h3>
      </div>
      <div class="panel-body">
        <ol>
          <li class="tutorial_li">カタログを作成<img class="tutorial" src="images/tutorial1.jpg"></li>
          <li class="tutorial_li">アイテムを登録<img class="tutorial" src="images/tutorial2.jpg"><p class="small tutorial_small">※画像の添付やリンクもできるよ。</p></li>
          <li class="tutorial_li">カタログを公開（鍵を外す）<img class="tutorial" src="images/tutorial3.jpg"><p class="small tutorial_small">※鍵を外すまでは自分しか見られないよ。</p></li>
          <li class="tutorial_li">ツイートしてみんなに知らせよう！<img class="tutorial" src="images/tutorial4.jpg"></li>
        </ol>
      </div>
    </div>
    <div class="panel panel-danger panel_sub">
      <div class="panel-heading">
        <h3 class="panel-title">ログインするとできること</h3>
      </div>
      <div class="panel-body">
        <ul>
          <li>カタログ作成</li>
          <li>カタログのお気に入り登録</li>
        </ul>
        <p class="small tutorial_small">※カタログの閲覧はログインしなくてもできるよ。</p>
      </div>
    </div>
    <p>自分のカタログが誰かの役に立ったり、他の人のカタログから掘り出し物がみつかったりするかも！</p>
  </div>
</div>

<a class="btn twitter twi_btn" href="<?php echo SITE_URL. '/login' ?>"><i class="fa fa-lg fa-twitter"></i>&nbsp;ログイン</a>

<?php
require_once ( __DIR__ . '/v_pop_catalog.php');
require_once ( __DIR__ . '/v_new_catalog.php');
?>

<a class="btn twitter twi_btn" href="<?php echo SITE_URL. '/login' ?>"><i class="fa fa-lg fa-twitter"></i>&nbsp;ログイン</a>
