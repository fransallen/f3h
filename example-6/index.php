<?php

if ('POST' === $_SERVER['REQUEST_METHOD']) {
    if (!empty($_FILES['blob'])) {
        if (empty($_FILES['blob']['error'])) {
            move_uploaded_file($_FILES['blob']['tmp_name'], __DIR__ . '/' . uniqid() . '.' . pathinfo($_FILES['blob']['name'], PATHINFO_EXTENSION));
            header('Location: ?error=0');
            exit;
        }
        header('Location: ?error=' . $_FILES['blob']['error']);
        exit;
    }
    header('Location: ?error');
    exit;
}

?><!DOCTYPE html>
<html dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta content="width=device-width" name="viewport">
    <title>Home</title>
  </head>
  <body>
    <main>
      <h1>Files</h1>
      <?php if ($files = glob(__DIR__ . '/*.*')): ?>
      <ol>
        <?php foreach ($files as $file): ?>
        <?php if ('index.php' === ($n = basename($file))) continue; ?>
        <li><a href="<?= $n; ?>" target="_blank"><?= $n; ?></a></li>
        <?php endforeach; ?>
      </ol>
      <?php endif; ?>
    </main>
    <form enctype="multipart/form-data" method="post">
      <p>
        <input name="blob" type="file">
        <button name="save" hidden type="submit">Upload</button>
      </p>
    </form>
    <script src="../f3h.min.js"></script>
    <script>

    var f3h = new F3H,
        main = document.querySelector('main');

    f3h.on('exit', function(response, any) {
        document.title = (any.blob ? 'Uploading' : 'Loading') + '…';
    });

    f3h.on(200, function(response) {
        document.title = response.title;
        main.innerHTML = response.querySelector('main').innerHTML;
        document.forms[0].blob.value = "";
    });

    // Upload immediately on file selection
    document.forms[0].blob.addEventListener('change', function() {
        this.form.save.click(); // Click the hidden button element
    }, false);

    </script>
  </body>
</html>
