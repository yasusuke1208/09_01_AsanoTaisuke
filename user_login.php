<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>じゃんけん王決定戦 ログイン画面</title>
</head>

<body>
  <form action="user_login_act.php" method="POST">
    <fieldset>
      <legend>じゃんけん王決定戦 ログイン画面</legend>
      <div>
        username: <input type="text" name="username">
      </div>
      <div>
        password: <input type="text" name="password">
      </div>
      <div>
        <button>Login</button>
      </div>
      <a href="user_register.php">or register</a>
    </fieldset>
  </form>

</body>

</html>