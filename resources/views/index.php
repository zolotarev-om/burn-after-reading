<!DOCTYPE html>
<html lang="ru">
<head>
    <title>Main page</title>
    <meta charset="UTF-8">
    <meta name=description content="Burn After Reading">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.js"></script>
    <!-- Bootstrap JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <!-- my -->
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
</head>
<body>
<div class="container">
    <div class="col-md-8 center-block">
        <div class="row">
            <?php
            if (!empty($text)) {
                if (isset($admin) && $admin === true) {
                    include 'inc.admin.php';
                } else {
                    include 'inc.letter.php';
                }
            } elseif (!empty($url)) {
                include 'inc.new.php';
            } else {
                include 'inc.form.php';
                if (isset($errors) && count($errors) > 0) {
                    foreach ($errors as $error) {
                        include 'inc.error.php';
                    }
                }
            }
            ?>
        </div>
    </div>
</div>
</body>
</html>