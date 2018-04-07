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
function insert_user($dbh, $sns_user_id, $screen_name, $user_name) {
  try {
    $sql = "INSERT INTO user (sns_user_id, screen_name, user_name) VALUE (:sns_user_id, :screen_name, :user_name)";
    $stmt = $dbh -> prepare($sql);
    $stmt -> bindValue(':sns_user_id', $sns_user_id, PDO::PARAM_STR);
    $stmt -> bindValue(':screen_name', $screen_name, PDO::PARAM_STR);
    $stmt -> bindValue(':user_name', $user_name, PDO::PARAM_STR);
    $stmt -> execute();
  }catch (PDOException $e) {
    echo($e -> getMessage());
    die();
  }
}

// スクリーンネームとユーザーネームの更新
function update_screen_name($dbh, $sns_user_id, $screen_name, $user_name) {
  try {
    $sql = "UPDATE user SET screen_name = :screen_name, user_name = :user_name WHERE sns_user_id = :sns_user_id";
    $stmt = $dbh -> prepare($sql);
    $stmt -> bindValue(':sns_user_id', $sns_user_id, PDO::PARAM_STR);
    $stmt -> bindValue(':screen_name', $screen_name, PDO::PARAM_STR);
    $stmt -> bindValue(':user_name', $user_name, PDO::PARAM_STR);
    $stmt -> execute();
  }catch (PDOException $e) {
    echo($e -> getMessage());
    die();
  }
}


///////////////////////////////////////////////////
// カタログ関連
///////////////////////////////////////////////////

// そのユーザーが作成済みのカタログ数を返す
// function count_users_catalog($dbh, $user_id) {
//   try {
//     $sql = "SELECT * FROM catalog  WHERE user_id = :user_id";
//     $stmt = $dbh -> prepare($sql);
//     $stmt -> bindValue(':user_id', $user_id, PDO::PARAM_STR);
//     $stmt -> execute();
//   }catch (PDOException $e) {
//     echo($e -> getMessage());
//     die();
//   }
//   $count = $stmt->rowCount();
//   return $count;
// }

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
