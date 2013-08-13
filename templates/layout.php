<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>OpenPasswd</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Bootstrap -->
        <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0-rc1/css/bootstrap.min.css" rel="stylesheet" media="screen">
    </head>
    <body>
        <?php 
        if ($must_be_login === true) {
            include __DIR__.'/login.php';
        } else {
            include __DIR__.'/account.php';
            include __DIR__.'/account_type.php';
            include __DIR__.'/field.php';
            include __DIR__.'/group.php';
            include __DIR__.'/user.php';
        }
        ?>

        <!-- JavaScript plugins (requires jQuery) -->
        <script src="http://code.jquery.com/jquery.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0-rc1/js/bootstrap.min.js"></script>
    </body>
</html>