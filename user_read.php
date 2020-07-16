<?php
// DB接続の設定
// DB名は`gsacf_x00_00`にする
session_start(); // セッションの開始
include('functions.php'); // 関数ファイル読み込み
check_session_id(); // idチェック関数の実行
$pdo = connect_to_db();

// データ取得SQL作成
$sql = 'SELECT * FROM users_table';

// SQL準備&実行
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();

// データ登録処理後
if ($status == false) {
  // SQL実行に失敗した場合はここでエラーを出力し，以降の処理を中止する
  $error = $stmt->errorInfo();
  echo json_encode(["error_msg" => "{$error[2]}"]);
  exit();
} else {
  // 正常にSQLが実行された場合は入力ページファイルに移動し，入力ページの処理を実行する
  // fetchAll()関数でSQLで取得したレコードを配列で取得できる
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);  // データの出力用変数（初期値は空文字）を設定
  $output = "";
  // <tr><td>deadline</td><td>todo</td><tr>の形になるようにforeachで順番に$outputへデータを追加
  // `.=`は後ろに文字列を追加する，の意味
  foreach ($result as $record) {
    $output .= "<tr>";
    $output .= "<td>{$record["username"]}</td>";
    $output .= "<td>{$record["password"]}</td>";
    $output .= "<td>{$record["is_admin"]}</td>";
    $output .= "<td>{$record["is_deleted"]}</td>";
    // edit deleteリンクを追加
    $output .= "<td><a href='user_edit.php?id={$record["id"]}'>edit</a></td>";
    $output .= "<td><a href='user_delete.php?id={$record["id"]}'>delete</a></td>";
    $output .= "</tr>";
  }
  // $valueの参照を解除する．解除しないと，再度foreachした場合に最初からループしない
  // 今回は以降foreachしないので影響なし
  unset($record);
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/sample.css">
  <title>コンソール</title>
</head>

<body>
  <fieldset>
    <legend>コンソール</legend>
    <!-- <a href="user_input.php">入力画面</a> -->
    <a href="user_logout.php">logout</a>
    <!-- <table>
      <thead>
        <tr>
          <th>username</th>
          <th>password</th>
          <th>is_admin</th>
          <th>is_deleted</th>
          <th></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?= $output ?>   // ここに<tr><td>deadline</td><td>todo</td><tr>の形でデータが入る
      </tbody>
    </table> -->
  </fieldset>
  
  <header>
    <h1>じゃんけん王決定戦</h1>
  </header>

  <main>
    <h3>YOU</h3>

    <ul>
      <li id="gu_btn">グー</li>
      <li id="cho_btn">チョキ</li>
      <li id="par_btn">パー</li>
    </ul>
    <div id="big_box">
      <div id="box1">
        <div id="question">Aの出した手は？</div>
        <div id="judgment">結果は？</div>
      </div>
      <div id="box2">
        <table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td width="1">
              <div class="victory">YOU</div>
            </td>
            <td width="1">
              <div class="victory">┐</div>
            </td>
            <td width="1"></td>
            <td width="1"></td>
            <td width="90%"></td>
          </tr>
          <tr>
            <td width="1"></td>
            <td width="1">
              <div class="victory">├</div>
            </td>
            <td width="1">
              <div class="victory">─</div>
      </div>
      </td>
      <td width="1">
        <div class="victory3">┐</div>
      </td>
      <td width="90%"></td>
      </tr>
      <tr>
        <td width="1"> A</td>
        <td width="1">┘</td>
        <td width="1"></td>
        <td width="1">
          <div class="victory3">│</div>
        </td>
        <td width="90%"></td>
      </tr>
      <tr>
        <td width="1"></td>
        <td width="1"></td>
        <td width="1"></td>
        <td width="1">
          <div class="victory3">├</div>
        </td>
        <td width="1"></td>
      </tr>
      <tr>
        <td width="1">
          <div class="victory2">B</div>
        </td>
        <td width="1">
          <div class="victory2">┐</div>
        </td>
        <td width="1"></td>
        <td width="1">│</td>
        <td width="90%"></td>
      </tr>
      <tr>
        <td width="1"></td>
        <td width="1">
          <div class="victory2">├</div>
        </td>
        <td width="1">
          <div class="victory2">─</div>
        </td>
        <td width="1">┘</td>
        <td width="90%"></td>
      </tr>
      <tr>
        <td width="1"> C</td>
        <td width="1">┘</td>
        <td width="1"></td>
        <td width="1"></td>
        <td width="90%"></td>
      </tr>
      </table>
    </div>
    <div class="jankenou_img">
      <img src="img/ダウンロード.jpg" alt="">
    </div>
  </main>

  <footer id="keika_btn">途中経過</footer>

  <input type="text" id="input">
  <!-- <textarea id="text_area"></textarea> -->
  <ul>
    <li id="save">Save</li>
    <li id="clear">Clear</li>
  </ul>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script>
    //YOU vs A
    $(function () {
      function BorC() {
        $(".victory2").css("color", "red");
        $("#input").val("B WIN!");
      }
      //YOU vs B
      function finalbattle() {
        alert("FINAL BATTLE");
        $("#question").text("Bの出した手は？");
        $("#judgment").text("結果は？");
        $("#gu_btn").on("click", function () {
          const randomNumber = Math.floor(Math.random() * 3);
          if (randomNumber == 0) {
            $("#question").text("B：グー");
            $("#judgment").text("あいこ");
          } else if (randomNumber == 1) {
            $("#question").text("B：チョキ");
            $("#judgment").text("あなたの勝ち");
            $(".victory3").css("color", "red");
            $("#input").val("YOU ARE THE CHAMPION!");
          } else if (randomNumber == 2) {
            $("#question").text("B：パー");
            $("#judgment").text("あなたの負け");
          }
        });
        $("#cho_btn").on("click", function () {
          const randomNumber = Math.floor(Math.random() * 3);
          if (randomNumber == 0) {
            $("#question").text("B：グー");
            $("#judgment").text("あなたの負け");
          } else if (randomNumber == 1) {
            $("#question").text("B：チョキ");
            $("#judgment").text("あいこ");
          } else if (randomNumber == 2) {
            $("#question").text("B：パー");
            $("#judgment").text("あなたの勝ち");
            $(".victory3").css("color", "red");
            $("#input").val("YOU ARE THE CHAMPION!");
          }
        });
        $("#par_btn").on("click", function () {
          const randomNumber = Math.floor(Math.random() * 3);
          if (randomNumber == 0) {
            $("#question").text("B：グー");
            $("#judgment").text("あなたの勝ち");
            $(".victory3").css("color", "red");
            $("#input").val("YOU ARE THE CHAMPION!");
          } else if (randomNumber == 1) {
            $("#question").text("B：チョキ");
            $("#judgment").text("あなたの負け");
          } else if (randomNumber == 2) {
            $("#question").text("B：パー");
            $("#judgment").text("あいこ");
          }
        });
      }

      $("#gu_btn").on("click", function () {
        const randomNumber = Math.floor(Math.random() * 3);
        if (randomNumber == 0) {
          $("#question").text("A：グー");
          $("#judgment").text("あいこ");
        } else if (randomNumber == 1) {
          $("#question").text("A：チョキ");
          $("#judgment").text("あなたの勝ち");
          $(".victory").css("color", "red");
          $("#input").val("YOU WIN!");
        } else if (randomNumber == 2) {
          $("#question").text("A：パー");
          $("#judgment").text("あなたの負け");
        }
      });
      $("#cho_btn").on("click", function () {
        const randomNumber = Math.floor(Math.random() * 3);
        if (randomNumber == 0) {
          $("#question").text("A：グー");
          $("#judgment").text("あなたの負け");
        } else if (randomNumber == 1) {
          $("#question").text("A：チョキ");
          $("#judgment").text("あいこ");
        } else if (randomNumber == 2) {
          $("#question").text("A：パー");
          $("#judgment").text("あなたの勝ち");
          $("#input").val("YOU WIN!");
          $(".victory").css("color", "red");
        }
      });
      $("#par_btn").on("click", function () {
        const randomNumber = Math.floor(Math.random() * 3);
        if (randomNumber == 0) {
          $("#question").text("A：グー");
          $("#judgment").text("あなたの勝ち");
          $("#input").val("YOU WIN!");
          $(".victory").css("color", "red");
        } else if (randomNumber == 1) {
          $("#question").text("A：チョキ");
          $("#judgment").text("あなたの負け");
        } else if (randomNumber == 2) {
          $("#question").text("A：パー");
          $("#judgment").text("あいこ");
        }
      });

      //途中経過
      $("#keika_btn").on("click", function () {
        BorC();
        setTimeout(finalbattle, 1000);
      });


      //1.Save クリックイベント
      $("#save").on("click", function () {
        const data = $("#input").val();
        // text: $("#text_area").val(),
        // const jsonData = JSON.stringify(data);
        localStorage.setItem("memo", data);
        alert("セーブしました");
      });

      //2.clear クリックイベント
      $("#clear").on("click", function () {
        localStorage.removeItem("memo");
        $("#input").val("");
      });


      //3.ページ読み込み：保存データ取得表示
      if (localStorage.getItem("memo")) {// 値が保存されていれば
        const text = localStorage.getItem("memo"); // データを取得
        $("#input").val(text);
        if (input.value == "YOU WIN!") {
          $(".victory").css("color", "red");
          $("#question").text("");
          $("#judgment").text("");
          alert("?");
        } else if (input.value == "B WIN!") {
          $(".victory").css("color", "red");
          $(".victory2").css("color", "red");
          setTimeout(finalbattle, 1000);
        }
      };
    });
  </script>

</body>
</html>
