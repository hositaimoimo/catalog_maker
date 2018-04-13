<div class="panel panel-success panel_main">
  <div class="panel-heading">
    <h1 class="panel-title">カタログ検索</h1>
  </div>
  <div class="panel-body">
    <p>カタログとアイテムに含まれるキーワードからカタログを検索できます。
    <form class="navbar-form navbar-center search_box" role="search">
        <div class="form-group">
          <input type="text" class="form-control" placeholder="Search">
        </div>
        <button type="submit" class="btn btn-default center">検索</button>
      </div>
    </form>
  </div>
</div>

<?php
if($request_url != "search"){
  // 検索結果の場合は以下も表示
}
