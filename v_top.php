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
    <p>使い方は色々。以下はイラストにしたい。</p>
    <ul>
      <li>自分の作品を並べてギャラリーに。</li>
      <li>お気に入りのお店・グッズなどの紹介に。</li>
      <li>ハマってるゲームの共有に。</li>
      <li>口コミのチェックに。</li>
    </ul>
  </div>
</div>

<div class="panel panel-success panel_main">
  <div class="panel-heading">
    <h2 class="panel-title">カタログメーカーの使い方</h1>
  </div>
  <div class="panel-body">
    <div class="panel panel-danger panel_sub">
      <div class="panel-heading">
        <h3 class="panel-title">カタログを作る</h3>
      </div>
      <div class="panel-body">
        <ol>
          <li>カタログを作成</li>
          <li>アイテムを登録</li>
          <li>カタログを公開・ツイートしてみんなに知らせよう！</li>
        </ol>
      </div>
    </div>
    <div class="panel panel-danger panel_sub">
      <div class="panel-heading">
        <h3 class="panel-title">他の人のカタログを見て楽しむ</h3>
      </div>
      <div class="panel-body">
        <ul>
          <li>気に入ったカタログはお気に入り登録</li>
          <li>人気カタログをランキングでチェック</li>
          <li>気になるアイテムがないか検索で調べる</li>
        </ul>
      </div>
    </div>
  </div>
</div>

<a class="btn twitter twi_btn" href="<?php echo SITE_URL. '/login' ?>"><i class="fa fa-lg fa-twitter"></i>&nbsp;ログイン</a>
