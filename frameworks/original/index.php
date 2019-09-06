<!DOCType html>
<html>
  <head>
    <title>タイトル</title>
  </head>
  <body>
    <?php if (!empty($_POST)) : ?>
      <p class="flash-message"><?= ($_POST['csrf'] ?? false) ? htmlspecialchars($_POST['message'], ENT_QUOTES, 'UTF-8') . 'が送られてきました。' : 'CSRF トークンが送られてきていません。'; ?></p>
    <?php endif ?>
    <form class="form" action="./index.php" method="POST">
      <input type="text" name="message" />
      <button type="submit">送信</button>
    </form>
    <script>
      document.addEventListener('DOMContentLoaded', () => {
        const csrf = document.createElement('input');
        csrf.name = 'csrf';
        csrf.type = 'hidden';
        csrf.value = '1';
        document.querySelector('.form').append(csrf);
      })
    </script>
  </body>
</html>
