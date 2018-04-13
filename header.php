<header>
  <div class="navbar navbar-default navbar-fixed-top">
    <div class="container container-navbar">
      <div class="navbar-header">
        <a href="/" class="navbar-brand">カタログメーカー</a>
        <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
      </div>
      <div class="navbar-collapse collapse" id="navbar-main">
        <ul class="nav navbar-nav">
          <?php
          if(!isset($_SESSION['access_token'])){
            // 未ログイン
          }else{
      			echo "<li><a href=". SITE_URL.'/'. $_SESSION['screen_name'].">ホーム</a></li>";
            echo "<li><a href=". SITE_URL."/fav>お気に入り</a></li>";
      		}
          ?>
          <li><a href="<?php echo SITE_URL.'/pop' ?>">人気</a></li>
          <li><a href="<?php echo SITE_URL.'/new' ?>">新着</a></li>
          <?php
          if(!isset($_SESSION['access_token'])){
      			// 未ログイン
      			echo '<li><a class="twitter" href='. SITE_URL. '/'. 'login><i class="fa fa-lg fa-twitter"></i>&nbsp;ログイン</a></li>';
      		}else{
            echo '<li><a href='. SITE_URL. '/'. 'logout>ログアウト</a></li>';
          }
          ?>
        </ul>
      </div>
    </div>
  </div>
</header>
<div class="main">
  <div class="container">
