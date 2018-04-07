<?php
if (!isset($_SESSION)) {
	session_start();
}

header ("Content-type: text/html; charset=utf-8");

require_once ( __DIR__ . '/functions.php');
require_once ( __DIR__ . '/config.php');

// DBに接続
$dbh = get_db_connect ();

// リクエストUSITE_URLのスラッシュを除去
$request_url = ltrim(html_escape($_SERVER['REQUEST_URI']), '/');
echo $request_url.PHP_EOL;
// 'login'の場合以外はurlをセッションに保管
if(ltrim(html_escape($_SERVER['REQUEST_URI']), '/') != 'login'){
	$_SESSION['request_url'] = $request_url;
}


// ログイン中のみナビメニュー呼び出し
if(!isset($_SESSION['access_token'])){
	// 未ログイン
	// 何もしない
}else{
	// ログイン中
	// ナビメニュー呼び出し

}


// リクエストURLによって表示内容を調節
switch ($request_url) {
	case '':
	case 'favicon.ico':
		///////////////////////////////////////////////////
		// トップページ
		///////////////////////////////////////////////////
		$_SESSION['title'] = 'カタログメーカー';
		require_once ( __DIR__ . '/head.php');
		if(!isset($_SESSION['access_token'])){
			// 未ログイン
			// トップページ呼び出し
				echo "<a href='login'>Twitterでログイン</a>";
		}else{
			// ログイン中
			// screen_nameが変わっているかもしれないので一応上書き
		  update_screen_name($dbh, $_SESSION['id'], $_SESSION['screen_name'], $_SESSION['user_name']);
			// 自分のページ(/username)へリダイレクト
			header( 'location: '. SITE_URL.'/'.$_SESSION['screen_name'] );

			// 確認用の仮表示↓
			echo "<p>ID：". $_SESSION['id'] . "</p>";
			echo "<p>名前：". $_SESSION['user_name'] . "</p>";
			echo "<p>スクリーン名：". $_SESSION['screen_name'] . "</p>";
			echo "<p><img src=".$_SESSION['profile_image_url_https']."></p>";
			echo "<p>最新ツイート：" .$_SESSION['text']. "</p>";
			echo "<p>access_token：". $_SESSION['access_token']['oauth_token'] . "</p>";
			echo "<p><a href='logout'>ログアウト</a></p>";
		}
		break;

	case 'login':
		///////////////////////////////////////////////////
		// ログインページ
		///////////////////////////////////////////////////
		$_SESSION['title'] = 'ログイン';
		require_once ( __DIR__ . '/head.php');
		if(!isset($_SESSION['access_token'])){
			// 未ログイン
			require_once ( __DIR__ . '/login.php');
		}else{
			// ログイン中
			header( 'location: '. SITE_URL );
		}
		break;

	case 'logout':
		///////////////////////////////////////////////////
		// ログアウトページ
		///////////////////////////////////////////////////
		$_SESSION['title'] = 'ログアウト';
		require_once ( __DIR__ . '/head.php');
		if(!isset($_SESSION['access_token'])){
			// 未ログイン
			header( 'location: '. SITE_URL );
		}else{
			// ログイン中
			require_once ( __DIR__ . '/logout.php');
		}
		break;

	case 'setting':
		///////////////////////////////////////////////////
		// 設定ページ
		///////////////////////////////////////////////////
		$_SESSION['title'] = '設定';
		require_once ( __DIR__ . '/head.php');
		if(!isset($_SESSION['access_token'])){
			// 未ログイン
			header( 'location: '. SITE_URL );
		}else{
			// ログイン中
			require_once ( __DIR__ . '/setting.php');
		}
		break;

	default:
		if(preg_match('|^[0-9a-z_]+[/]{1}[c]{1}[0-9]+$|', $request_url)){
			list($url1, $url2) = explode("/c", $request_url);

			// ユーザー名とカタログIDの両方があればカタログページを表示
			// ユーザーIDを取得
			$user_id = get_user_id($dbh, $url1);
			if($user_id == NULL){
				// 無ければ404
				$_SESSION['title'] = 'お探しのページが見つかりませんでした';
				require_once ( __DIR__ . '/head.php');
				require_once ( __DIR__ . '/404.php');
			}else{
				// あればカタログの有無を確認
				$catalog = get_catalog($dbh, $user_id, $url2);
				if($catalog == NULL){
					// 無ければ404
					$_SESSION['title'] = 'お探しのページが見つかりませんでした';
					require_once ( __DIR__ . '/head.php');
					require_once ( __DIR__ . '/404.php');
				}else{
					// あればカタログページを表示
					// カタログに登録されているアイテムを取得
					$screen_name = get_screen_name($dbh, $user_id);
					$user_name = get_user_name($dbh, $screen_name);
					$catalog_item =	get_catalog_item($dbh, $catalog['catalog_id']);

					$_SESSION['title'] = $catalog['catalog_name'];
					require_once ( __DIR__ . '/head.php');
					require_once ( __DIR__ . '/v_catalog.php');
				}
			}
		}else{

			// /s?... s?で始まっているか？正規表現使って何とかする
			// 検索結果表示

			// 訪問先のユーザーIDがあるか確認(あれば取得)
			$user_id = get_user_id($dbh, $request_url);

			if($user_id == NULL){		// 無ければ404
				///////////////////////////////////////////////////
				// 404 not found
				///////////////////////////////////////////////////
				$_SESSION['title'] = 'お探しのページが見つかりませんでした';
				require_once ( __DIR__ . '/head.php');
				require_once ( __DIR__ . '/404.php');

			}else{	// あればユーザーページを表示
				///////////////////////////////////////////////////
				// ユーザーページ
				///////////////////////////////////////////////////
				$_SESSION['title'] = get_user_name($dbh, $request_url);
				require_once ( __DIR__ . '/head.php');
				require_once ( __DIR__ . '/v_profile.php');

				// カタログリスト, アイテムリストの取得
				$catalog_list = get_catalog_list($dbh, $user_id);
				$item_list = get_item_list($dbh, $user_id);

				if(!isset($_SESSION['access_token'])){
					// 未ログイン
					// 特になし
				}else{
					// ログイン中
					// 自分のページの場合はアイテム登録・カタログ作成メニューを表示
					if($request_url == $_SESSION['screen_name']){
						// カタログがある場合はアイテム登録部分を表示
						if($catalog_list != NULL){
							require_once ( __DIR__ . '/v_item_regist.php');
						}
						// カタログ作成部分は常時表示
						require_once ( __DIR__ . '/v_catalog_make.php');
					}
				}

				// カタログリスト、アイテムリストを表示
				require_once ( __DIR__ . '/v_catalog_list.php');
				require_once ( __DIR__ . '/v_item_list.php');

			}
		}
		break;
}
require_once ( __DIR__ . '/foot.php');
