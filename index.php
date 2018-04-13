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
		$_SESSION['title'] = 'トップ';
		require_once ( __DIR__ . '/head.php');
		if(!isset($_SESSION['access_token'])){
			// 未ログイン

			// 訪問者のユーザーIDを取得(意味ないけど一応)
			$user_id = get_user_id($dbh, $_SESSION['screen_name']);
			// 人気カタログリストを取得
			$pop_catalog_list = get_pop_catalog_list($dbh);
			// 新着カタログリストを取得
			$new_catalog_list = get_new_catalog_list($dbh);
			// ロック中のカタログを除外
			$pop_catalog_list = list_minus_locked($pop_catalog_list);
			$new_catalog_list = list_minus_locked($new_catalog_list);
			// トップページ呼び出し
			require_once ( __DIR__ . '/v_top.php');

		}else{
			// ログイン中
			// screen_nameが変わっているかもしれないので一応上書き
		  update_screen_name($dbh, $_SESSION['id'], $_SESSION['screen_name'], $_SESSION['user_name'], $_SESSION['profile_image_url_https']);
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

	case 'fav':
		///////////////////////////////////////////////////
		// お気に入りカタログページ
		///////////////////////////////////////////////////
		$_SESSION['title'] = 'お気に入り';
		require_once ( __DIR__ . '/head.php');
		if(!isset($_SESSION['access_token'])){
			// 未ログイン
			header( 'location: '. SITE_URL );
		}else{
			// ログイン中
			// 訪問者のユーザーIDを取得
			$user_id = get_user_id($dbh, $_SESSION['screen_name']);
			// カタログリストを取得
			$fav_catalog_list = get_favo_catalog_list($dbh, $user_id);
			require_once ( __DIR__ . '/v_favo_catalog.php');
		}
		break;

	case 'pop':
		///////////////////////////////////////////////////
		// 人気カタログページ
		///////////////////////////////////////////////////
		$_SESSION['title'] = '人気カタログ';
		require_once ( __DIR__ . '/head.php');
		// 訪問者のユーザーIDを取得
		$user_id = get_user_id($dbh, $_SESSION['screen_name']);
		// カタログリストを取得
		$pop_catalog_list = get_pop_catalog_list($dbh);
		// ロック中のカタログを除外
		$pop_catalog_list = list_minus_locked($pop_catalog_list);
		require_once ( __DIR__ . '/v_pop_catalog.php');

		break;

	case 'new':
		///////////////////////////////////////////////////
		// 新着カタログページ
		///////////////////////////////////////////////////
		$_SESSION['title'] = '新着カタログ';
		require_once ( __DIR__ . '/head.php');
		// 訪問者のユーザーIDを取得
		$user_id = get_user_id($dbh, $_SESSION['screen_name']);
		// カタログリストを取得
		$new_catalog_list = get_new_catalog_list($dbh);
		// ロック中のカタログを除外
		$new_catalog_list = list_minus_locked($new_catalog_list);
		require_once ( __DIR__ . '/v_new_catalog.php');
		break;

	// case 'search':
	// 	///////////////////////////////////////////////////
	// 	// 検索ページ
	// 	///////////////////////////////////////////////////
	// 	$_SESSION['title'] = 'カタログ検索';
	// 	require_once ( __DIR__ . '/head.php');
	// 	require_once ( __DIR__ . '/v_search.php');
	// 	break;

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
				// あればカタログの有無を確認(ロック中で自分以外の場合もNot Found)
				$catalog = get_catalog($dbh, $user_id, $url2);
				if($catalog == NULL || ($catalog['catalog_lock'] == 0 && $url1 != $_SESSION['screen_name'] )){
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

					// カタログリストも取得
					$catalog_list = get_catalog_list($dbh, $user_id);
					// カタログページのメインになるカタログは除外
					$catalog_list = list_minus_catalog($catalog_list, $catalog['catalog_id']);
					// 自分じゃない時はロック中のカタログを除外
					if($url1 != $_SESSION['screen_name']){
						$catalog_list = list_minus_locked($catalog_list);
					}
					$_SESSION['title'] = $catalog['catalog_name'];
					require_once ( __DIR__ . '/head.php');
					require_once ( __DIR__ . '/v_catalog.php');
				}
			}
		}else{

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
require_once ( __DIR__ . '/footer.php');
