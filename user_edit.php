<?php
// 送信データのチェック
// var_dump($_GET);
// exit();

// 関数ファイルの読み込み
session_start(); // セッションの開始
include('functions.php'); // 関数ファイル読み込み
check_session_id(); // idチェック関数の実行

// idの受け取り
$id = $_GET['id'];

// DB接続
$pdo = connect_to_db();
$sql = 'SELECT * FROM users_table WHERE id=:id';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$status = $stmt->execute();

// データ取得SQL作成
$sql = '';

// SQL準備&実行




// データ登録処理後
if ($status == false) {
  // SQL実行に失敗した場合はここでエラーを出力し，以降の処理を中止する
  $error = $stmt->errorInfo();
  echo json_encode(["error_msg" => "{$error[2]}"]);
  exit();
} else {
  // 正常にSQLが実行された場合は指定の11レコードを取得
  // fetch()関数でSQLで取得したレコードを取得できる
  $record = $stmt->fetch(PDO::FETCH_ASSOC);
}

?>


<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DB連携型userリスト（編集画面）</title>
</head>

<body>
  <form action="user_update.php" method="POST">
    <fieldset>
      <legend>DB連携型userリスト（編集画面）</legend>
      <a href="user_read.php">一覧画面</a>
      <div>
        username: <input type="text" name="username" value="<?= $record["username"] ?>">
      </div>
      <div>
        password: <input type="password" name="password" value="<?= $record["password"] ?>">
      </div>
      <div>
        is_admin:
          <input type="radio" name="is_admin"  value="1" <?php if ($record["is_admin"] == 1){
                                                                echo 'checked';
                                                                } else {
                                                                echo '';}
                                                         ?>>yes
          <input type="radio" name="is_admin"  value="1" <?php if ($record["is_admin"] == 1){
                                                                echo '';
                                                              } else {
                                                              echo 'checked';}
                                                         ?>>no
      </div>
      <div>
        is_deleted:
        <input type="radio" name="is_deleted"  value="1" <?php if ($record["is_deleted"] == 1){
                                                                echo 'checked';
                                                                } else {
                                                                echo '';}
                                                         ?>>yes
        <input type="radio" name="is_deleted"  value="1" <?php if ($record["is_deleted"] == 1){
                                                                echo '';
                                                              } else {
                                                              echo 'checked';}
                                                         ?>>no
      </div>
      <!-- idを見えないように送る -->
      <!-- input type="hidden"を使用する！ -->
      <!-- form内に以下を追加 -->
      <input type="hidden" name="id" value="<?=$record['id']?>">
      <div>
        <button>submit</button>
      </div>

    </fieldset>
  </form>

</body>

</html>