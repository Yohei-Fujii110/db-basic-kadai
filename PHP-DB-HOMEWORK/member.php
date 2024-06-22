<?php
  session_start();

  $dsn = 'mysql:dbname=homework_login;host=localhost;charset=utf8mb4';
  $user = 'root';
  $password = '';

  // submitパラメータの値が存在するとき
  if(isset($_POST['submit'])) {
    try{
      // データベースへの接続
      $pdo = new PDO($dsn, $user, $password);

      // 入力されたメールアドレスとpasswordに両方合致するデータを検索するSQL文
      $sql = 'SELECT * FROM members WHERE ( email = :email ) AND (password = :password )';
      $stmt = $pdo->prepare($sql);     // SQL文のセット

      $password = hash('sha256', $_POST['password']);   // パスワードのハッシュ化

      // bindValue()メソッドでプレースホルダにバインド
      $stmt->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
      $stmt->bindValue(':password', $password, PDO::PARAM_STR);

      $stmt->execute(); //SQL文の実行

      // 戻り値として取得
      $resluts = $stmt->fetchAll(PDO::FETCH_ASSOC);

      // 戻り値がNULL（会員情報が一致しない）場合
      if(empty($resluts)) {
        $_SESSION['flash'] = "認証エラー：ログインに失敗しました";
        header('Location: login.php');
      }

    } catch(PDOException $e) {
      $_SESSION['flash'] = "接続エラー：データベースの接続に失敗しました";
      header('Location: login.php');
    }
  }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=, initial-scale=1.0">
  <title><?php echo $resluts[0]['name']; ?>さんの会員ページ</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/style.css">
</head>

<body>
  <div class="container m-auto mt-4 ">
    <header>
      <h4><?php echo $resluts[0]['name']; ?>さんの会員ページ</h4>

    </header>

      <article class="p-4 mb-4 border border-info">
        <h4 class="mb-4 w-75 text-decoration-underline"><?php echo $resluts[0]['name']; ?>さんの会員情報</h4>
        <section class="w-75 table">
          <div>お名前</div><div><?php echo $resluts[0]['name']; ?></div>
          <div>メールアドレス</div><div><?php echo $resluts[0]['email']; ?></div>
          <div>年齢</div><div><?php echo $resluts[0]['age']; ?></div>
          <div>住所</div><div><?php echo $resluts[0]['address']; ?></div>
        </section>
      </article>

    </main>

    <footer class="text-center">
      <a href="login.php" class="btn btn-secondary">ログアウト</a>
    </footer>
  </div>
<script type="module" src="js/script.js"></script>
</body>
</html>