<?php
function html_escape($word) {
  return htmlspecialchars($word, ENT_QUOTES, 'UTF-8');
}
function get_db_connect() {
  try {
    $dsn = DSN;
    $user = DB_USER;
    $password = DB_PASSWORD;
    $dbh = new PDO($dsn, $user, $password);
  }catch(PDOException $e) {
    echo($e -> getMessage());
    die();
  }
  $dbh -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  return $dbh;
}

///////////////////////////////////////////////////
// ユーザー登録関連
///////////////////////////////////////////////////

// ユーザー重複チェック
function count_double_user($dbh, $sns_user_id) {
  try {
    $sql = "SELECT COUNT(*) FROM user WHERE (sns_user_id) = (:sns_user_id)";
    $stmt = $dbh -> prepare($sql);
    $stmt -> bindValue(':sns_user_id', $sns_user_id, PDO::PARAM_STR);
    $stmt -> execute();
  }catch (PDOException $e) {
    echo($e -> getMessage());
    die();
  }
  $count = (int)$stmt->fetchColumn();
  return $count;
}

// ユーザーをDBに登録
function insert_user($dbh, $sns_user_id, $screen_name, $user_name, $profile_image_url_https) {
  try {
    $sql = "INSERT INTO user (sns_user_id, screen_name, user_name, profile_image_url_https)
     VALUE (:sns_user_id, :screen_name, :user_name, :profile_image_url_https)";
    $stmt = $dbh -> prepare($sql);
    $stmt -> bindValue(':sns_user_id', $sns_user_id, PDO::PARAM_STR);
    $stmt -> bindValue(':screen_name', $screen_name, PDO::PARAM_STR);
    $stmt -> bindValue(':user_name', $user_name, PDO::PARAM_STR);
    $stmt -> bindValue(':profile_image_url_https', $profile_image_url_https, PDO::PARAM_STR);
    $stmt -> execute();
  }catch (PDOException $e) {
    echo($e -> getMessage());
    die();
  }
}

// ユーザー情報の更新
function update_screen_name($dbh, $sns_user_id, $screen_name, $user_name, $profile_image_url_https) {
  try {
    $sql = "UPDATE user SET screen_name = :screen_name, user_name = :user_name, profile_image_url_https = :profile_image_url_https
     WHERE sns_user_id = :sns_user_id";
    $stmt = $dbh -> prepare($sql);
    $stmt -> bindValue(':sns_user_id', $sns_user_id, PDO::PARAM_STR);
    $stmt -> bindValue(':screen_name', $screen_name, PDO::PARAM_STR);
    $stmt -> bindValue(':user_name', $user_name, PDO::PARAM_STR);
    $stmt -> bindValue(':profile_image_url_https', $profile_image_url_https, PDO::PARAM_STR);
    $stmt -> execute();
  }catch (PDOException $e) {
    echo($e -> getMessage());
    die();
  }
}


///////////////////////////////////////////////////
// カタログ関連
///////////////////////////////////////////////////

// カタログをDBに登録
function insert_catalog($dbh, $catalog_name, $user_id, $catalog_comment) {
  $date = date('Y-m-d H:i:s');
  try {
    $sql = "INSERT INTO catalog (catalog_name, user_id, catalog_comment, updated) VALUE (:catalog_name, :user_id, :catalog_comment, '{$date}')";
    $stmt = $dbh -> prepare($sql);
    $stmt -> bindValue(':catalog_name', $catalog_name, PDO::PARAM_STR);
    $stmt -> bindValue(':user_id', $user_id, PDO::PARAM_STR);
    $stmt -> bindValue(':catalog_comment', $catalog_comment, PDO::PARAM_STR);
    $stmt -> execute();
  }catch (PDOException $e) {
    echo($e -> getMessage());
    die();
  }
}

// カタログを削除
function delete_catalog($dbh, $catalog_id) {
  try {
    $sql = "DELETE FROM catalog WHERE catalog_id = :catalog_id";
    $stmt = $dbh -> prepare($sql);
    $stmt -> bindValue(':catalog_id', $catalog_id, PDO::PARAM_STR);
    $stmt -> execute();
  }catch (PDOException $e) {
    echo($e -> getMessage());
    die();
  }
}

// カタログ更新
function update_catalog($dbh, $catalog_id) {
  $date = date('Y-m-d H:i:s');
  try {
    $sql = "UPDATE catalog SET updated = '{$date}' WHERE catalog_id = :catalog_id";
    $stmt = $dbh -> prepare($sql);
    $stmt -> bindValue(':catalog_id', $catalog_id, PDO::PARAM_STR);
    $stmt -> execute();
  }catch (PDOException $e) {
    echo($e -> getMessage());
    die();
  }
}

// アイテムIDによるカタログIDの取得
function get_catalog_id_by_item($dbh, $item_id) {
  try {
    $sql = "SELECT * FROM item WHERE (item_id) = (:item_id)";
    $stmt = $dbh -> prepare($sql);
    $stmt -> bindValue(':item_id', $item_id, PDO::PARAM_STR);
    $stmt -> execute();
  }catch (PDOException $e) {
    echo($e -> getMessage());
    die();
  }
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  return $result['catalog_id'];
}

// カタログのロック状態変更
function change_lock($dbh, $catalog_id, $catalog_lock) {
  try {
    $sql = "UPDATE catalog SET catalog_lock = :catalog_lock WHERE catalog_id = :catalog_id";
    $stmt = $dbh -> prepare($sql);
    $stmt -> bindValue(':catalog_id', $catalog_id, PDO::PARAM_STR);
    $stmt -> bindValue(':catalog_lock', $catalog_lock, PDO::PARAM_STR);
    $stmt -> execute();
  }catch (PDOException $e) {
    echo($e -> getMessage());
    die();
  }
}

// 特定のカタログをカタログリストから除外
function list_minus_catalog($catalog_list, $catalog_id) {
  $new_catalog_list = [];
  foreach($catalog_list as $row){
    if($row['catalog_id'] != $catalog_id) {
      $new_catalog_list[] = $row;
    }
  }
  return $new_catalog_list;
}

// ロック中のカタログをカタログリストから除外
function list_minus_locked($catalog_list) {
    $new_catalog_list = [];
    foreach($catalog_list as $row){
      if($row['catalog_lock'] == 1) {
        $new_catalog_list[] = $row;
      }
    }
    return $new_catalog_list;
}

// お気に入り数の更新
function update_favo($dbh, $catalog_id, $value) {
  try {
    $sql = "UPDATE catalog SET favos = favos + :value WHERE catalog_id = :catalog_id";
    $stmt = $dbh -> prepare($sql);
    $stmt -> bindValue(':catalog_id', $catalog_id, PDO::PARAM_STR);
    $stmt -> bindValue(':value', $value, PDO::PARAM_STR);
    $stmt -> execute();
  }catch (PDOException $e) {
    echo($e -> getMessage());
    die();
  }
}

///////////////////////////////////////////////////
// アイテム関連
///////////////////////////////////////////////////

// アイテムをDBに登録
function insert_item($dbh, $item_name, $catalog_id, $item_comment, $image_path, $link1_url, $link2_url) {
  try {
    $sql = "INSERT INTO item (item_name, catalog_id, item_comment, image_path, link1_url, link2_url)
            VALUE (:item_name, :catalog_id, :item_comment, :image_path, :link1_url, :link2_url)";
    $stmt = $dbh -> prepare($sql);
    $stmt -> bindValue(':item_name', $item_name, PDO::PARAM_STR);
    $stmt -> bindValue(':catalog_id', $catalog_id, PDO::PARAM_STR);
    $stmt -> bindValue(':item_comment', $item_comment, PDO::PARAM_STR);
    $stmt -> bindValue(':image_path', $image_path, PDO::PARAM_STR);
    $stmt -> bindValue(':link1_url', $link1_url, PDO::PARAM_STR);
    $stmt -> bindValue(':link2_url', $link2_url, PDO::PARAM_STR);
    $stmt -> execute();
  }catch (PDOException $e) {
    echo($e -> getMessage());
    die();
  }
}

// アイテムを削除
function delete_item($dbh, $item_id) {
  try {
    $sql = "DELETE FROM item WHERE item_id = :item_id";
    $stmt = $dbh -> prepare($sql);
    $stmt -> bindValue(':item_id', $item_id, PDO::PARAM_STR);
    $stmt -> execute();
  }catch (PDOException $e) {
    echo($e -> getMessage());
    die();
  }
}

// カタログ削除に伴うアイテム削除
function delete_item_by_catalog($dbh, $catalog_id) {
  try {
    $sql = "DELETE FROM item WHERE catalog_id = :catalog_id";
    $stmt = $dbh -> prepare($sql);
    $stmt -> bindValue(':catalog_id', $catalog_id, PDO::PARAM_STR);
    $stmt -> execute();
  }catch (PDOException $e) {
    echo($e -> getMessage());
    die();
  }
}

///////////////////////////////////////////////////
// お気に入り関連
///////////////////////////////////////////////////

// 特定のカタログがお気に入りに入っているかどうか
function check_favo($dbh, $user_id, $catalog_id) {
  try {
    $sql = "SELECT * FROM favo WHERE (favo_user_id, catalog_id) = (:user_id, :catalog_id)";
    $stmt = $dbh -> prepare($sql);
    $stmt -> bindValue(':user_id', $user_id, PDO::PARAM_STR);
    $stmt -> bindValue(':catalog_id', $catalog_id, PDO::PARAM_STR);
    $stmt -> execute();
  }catch (PDOException $e) {
    echo($e -> getMessage());
    die();
  }
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($result == NULL) {
    return 0;
  } else {
    return 1;
  }
}

// お気に入り追加
function plus_favo($dbh, $catalog_id, $user_id) {
  try {
    $sql = "INSERT INTO favo (favo_user_id, catalog_id) VALUE (:user_id, :catalog_id)";
    $stmt = $dbh -> prepare($sql);
    $stmt -> bindValue(':user_id', $user_id, PDO::PARAM_STR);
    $stmt -> bindValue(':catalog_id', $catalog_id, PDO::PARAM_STR);
    $stmt -> execute();
  }catch (PDOException $e) {
    echo($e -> getMessage());
    die();
  }
}

// お気に入り削除
function minus_favo($dbh, $catalog_id, $user_id) {
  try {
    $sql = "DELETE FROM favo WHERE (favo_user_id, catalog_id) = (:user_id, :catalog_id)";
    $stmt = $dbh -> prepare($sql);
    $stmt -> bindValue(':user_id', $user_id, PDO::PARAM_STR);
    $stmt -> bindValue(':catalog_id', $catalog_id, PDO::PARAM_STR);
    $stmt -> execute();
  }catch (PDOException $e) {
    echo($e -> getMessage());
    die();
  }
}

// カタログ削除に伴うお気に入り削除
function delete_favo_by_catalog($dbh, $catalog_id) {
  try {
    $sql = "DELETE FROM favo WHERE catalog_id = :catalog_id";
    $stmt = $dbh -> prepare($sql);
    $stmt -> bindValue(':catalog_id', $catalog_id, PDO::PARAM_STR);
    $stmt -> execute();
  }catch (PDOException $e) {
    echo($e -> getMessage());
    die();
  }
}

///////////////////////////////////////////////////
// 表示関連
///////////////////////////////////////////////////
// ユーザーIDの取得
function get_user_id($dbh, $screen_name) {
  try {
    $sql = "SELECT * FROM user WHERE (screen_name) = (:screen_name)";
    $stmt = $dbh -> prepare($sql);
    $stmt -> bindValue(':screen_name', $screen_name, PDO::PARAM_STR);
    $stmt -> execute();
  }catch (PDOException $e) {
    echo($e -> getMessage());
    die();
  }
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  return $result['user_id'];
}

// スクリーンネームの取得
function get_screen_name($dbh, $user_id) {
  try {
    $sql = "SELECT * FROM user WHERE (user_id) = (:user_id)";
    $stmt = $dbh -> prepare($sql);
    $stmt -> bindValue(':user_id', $user_id, PDO::PARAM_STR);
    $stmt -> execute();
  }catch (PDOException $e) {
    echo($e -> getMessage());
    die();
  }
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  return $result['screen_name'];
}

// ユーザー名の取得
function get_user_name($dbh, $screen_name) {
  try {
    $sql = "SELECT * FROM user WHERE (screen_name) = (:screen_name)";
    $stmt = $dbh -> prepare($sql);
    $stmt -> bindValue(':screen_name', $screen_name, PDO::PARAM_STR);
    $stmt -> execute();
  }catch (PDOException $e) {
    echo($e -> getMessage());
    die();
  }
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  return $result['user_name'];
}

// プロフ画像URLの取得
function get_user_image($dbh, $screen_name) {
  try {
    $sql = "SELECT * FROM user WHERE (screen_name) = (:screen_name)";
    $stmt = $dbh -> prepare($sql);
    $stmt -> bindValue(':screen_name', $screen_name, PDO::PARAM_STR);
    $stmt -> execute();
  }catch (PDOException $e) {
    echo($e -> getMessage());
    die();
  }
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  return $result['profile_image_url_https'];
}

// カタログリストの取得
function get_catalog_list($dbh, $user_id) {
  try {
    $sql = "SELECT * FROM catalog WHERE (user_id) = (:user_id)";
    $stmt = $dbh -> prepare($sql);
    $stmt -> bindValue(':user_id', $user_id, PDO::PARAM_STR);
    $stmt -> execute();
    $catalog_list = [];
    $count = $stmt->rowCount();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
      $catalog_list[] = $row;
    }
  }catch (PDOException $e) {
    echo($e -> getMessage());
    die();
  }
  return $catalog_list;
}

// お気に入りカタログリストの取得
function get_favo_catalog_list($dbh, $user_id) {
  try {
    $sql = "SELECT * FROM catalog INNER JOIN favo ON catalog.catalog_id = favo.catalog_id WHERE favo_user_id = :user_id";
    $stmt = $dbh -> prepare($sql);
    $stmt -> bindValue(':user_id', $user_id, PDO::PARAM_STR);
    $stmt -> execute();
    $catalog_list = [];
    $count = $stmt->rowCount();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
      $catalog_list[] = $row;
    }
  }catch (PDOException $e) {
    echo($e -> getMessage());
    die();
  }
  return $catalog_list;
}

// 人気順にカタログを取得
function get_pop_catalog_list($dbh) {
  try {
    $sql = "SELECT * FROM catalog ORDER BY favos DESC LIMIT 30";
    $stmt = $dbh -> prepare($sql);
    $stmt -> execute();
    $catalog_list = [];
    $count = $stmt->rowCount();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
      $catalog_list[] = $row;
    }
  }catch (PDOException $e) {
    echo($e -> getMessage());
    die();
  }
  return $catalog_list;
}

// 新着順にカタログを取得
function get_new_catalog_list($dbh) {
  try {
    $sql = "SELECT * FROM catalog ORDER BY updated DESC LIMIT 30";
    $stmt = $dbh -> prepare($sql);
    $stmt -> execute();
    $catalog_list = [];
    $count = $stmt->rowCount();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
      $catalog_list[] = $row;
    }
  }catch (PDOException $e) {
    echo($e -> getMessage());
    die();
  }
  return $catalog_list;
}

// カタログの取得
function get_catalog($dbh, $user_id, $catalog_id){
  try {
    $sql = "SELECT * FROM catalog WHERE (user_id, catalog_id) = (:user_id, :catalog_id)";
    $stmt = $dbh -> prepare($sql);
    $stmt -> bindValue(':user_id', $user_id, PDO::PARAM_STR);
    $stmt -> bindValue(':catalog_id', $catalog_id, PDO::PARAM_STR);
    $stmt -> execute();
    $catalog = $stmt->fetch(PDO::FETCH_ASSOC);
  }catch (PDOException $e) {
    echo($e -> getMessage());
    die();
  }
  return $catalog;
}

// アイテムリストの取得
function get_item_list($dbh, $user_id) {
  try {
    $sql = "SELECT * FROM item INNER JOIN catalog ON item.catalog_id = catalog.catalog_id WHERE user_id = :user_id";
    $stmt = $dbh -> prepare($sql);
    $stmt -> bindValue(':user_id', $user_id, PDO::PARAM_STR);
    $stmt -> execute();
    $item_list = [];
    $count = $stmt->rowCount();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
      $item_list[] = $row;
    }
  }catch (PDOException $e) {
    echo($e -> getMessage());
    die();
  }
  return $item_list;
}

// 特定カタログのアイテムの取得
function get_catalog_item($dbh, $catalog_id) {
  try {
    $sql = "SELECT * FROM item WHERE catalog_id = :catalog_id";
    $stmt = $dbh -> prepare($sql);
    $stmt -> bindValue(':catalog_id', $catalog_id, PDO::PARAM_STR);
    $stmt -> execute();
    $catalog_item = [];
    $count = $stmt->rowCount();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
      $catalog_item[] = $row;
    }
  }catch (PDOException $e) {
    echo($e -> getMessage());
    die();
  }
  return $catalog_item;
}


///////////////////////////////////////////////////
// 画像関連
///////////////////////////////////////////////////
function png2jpg($img_path, $output_path, $quality) {
  // 加工前の画像の情報を取得
  list($original_w, $original_h, $type) = getimagesize($img_path);
  // png と jpg 以外のケースは事前に弾いてある
  if ($type == IMAGETYPE_PNG){
    $original_image = imagecreatefrompng($img_path);
  } elseif ($type == IMAGETYPE_JPEG) {
    $original_image = imagecreatefromjpeg($img_path);
  }
  $w = IMAGE_WIDTH;
  $h = IMAGE_HEIGHT;
  if($original_h >= $original_w){
    $w = IMAGE_WIDTH * $original_w / $original_h;
  }else{
    $h = IMAGE_HEIGHT * $original_h / $original_w;
  }
  $canvas = imagecreatetruecolor($w, $h);
  imagecopyresampled($canvas, $original_image, 0,0,0,0, $w, $h, $original_w, $original_h);
  imagejpeg($canvas, $output_path, $quality);
  imagedestroy($original_image);
  // クオリティは 0 (一番圧縮されています) から 100 (高画質)の間の値です。
}
