<!DOCType html>
<html>
  <head>
    <title>タイトル</title>
  </head>
  <body>
    <?php if (!empty($_POST)) : ?>
      <p class="flash-message"><?= ($_POST['added_by_js'] ?? false) ? htmlspecialchars($_POST['message'], ENT_QUOTES, 'UTF-8') . 'が送られてきました。' : 'added_by_js が送られてきていません。'; ?></p>
    <?php endif ?>
    <form class="form" action="./index.php" method="POST">
      <input type="text" name="message" />
      <button type="submit">送信</button>
    </form>
    <script>
      document.addEventListener('DOMContentLoaded', () => {
        const addedByJS = document.createElement('input');
        addedByJS.name = 'added_by_js';
        addedByJS.type = 'hidden';
        addedByJS.value = '1';
        document.querySelector('.form').append(addedByJS);
      })
    </script>
  </body>
</html>
