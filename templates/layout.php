<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>OpenPasswd</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Bootstrap -->
        <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" media="screen">
        <link href="<?php echo $this->request->getBasePath() ?>/css/styles.css" rel="stylesheet" media="screen">
        <link href="<?php echo $this->request->getBasePath() ?>/css/bootstrap-glyphicons.css" rel="stylesheet" media="screen">
    </head>
    <body>
        <?php include __DIR__.'/header.php' ?>

        <div id="info-user"></div>

        <div class="container">
        <?php 
        if ($must_be_login === true) {
            include __DIR__.'/login.php';
        } else {
            include __DIR__.'/account/account.php';
            include __DIR__.'/account_type/account_type.php';
            include __DIR__.'/field/field.php';
            include __DIR__.'/group/group.php';
            include __DIR__.'/user/user.php';
        }
        ?>
        </div>

        <div id="loading" class="hide"></div>

        <!-- JavaScript plugins (requires jQuery) -->
        <script src="http://code.jquery.com/jquery.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0-rc2/js/bootstrap.min.js"></script>
        <?php if ($must_be_login === false) { ?>
        <script src="<?php echo $this->request->getBasePath() ?>/js/vendor/jquery.loadTemplate-1.0.0.js"></script>
        <script src="<?php echo $this->request->getBasePath() ?>/js/functions.js"></script>
        <script src="<?php echo $this->request->getBasePath() ?>/js/admin.prototype.js"></script>
        <script src="<?php echo $this->request->getBasePath() ?>/js/field.js"></script>
        <script src="<?php echo $this->request->getBasePath() ?>/js/group.js"></script>
        <script src="<?php echo $this->request->getBasePath() ?>/js/app.js"></script>
        <?php } ?>
    </body>
</html>