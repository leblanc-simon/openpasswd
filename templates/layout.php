<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?php echo $this->l10n('name') ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Bootstrap -->
        <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" media="screen">
        <?php if ($must_be_login === false) { ?>
        <link href="<?php echo $this->request->getBasePath() ?>/css/chosen.min.css" rel="stylesheet" media="screen">
        <?php } ?>
        <link href="<?php echo $this->request->getBasePath() ?>/css/styles.css" rel="stylesheet" media="screen">
        <link href="<?php echo $this->request->getBasePath() ?>/css/bootstrap-glyphicons.css" rel="stylesheet" media="screen">
        <!--
        /**
         * This file is part of the OpenPasswd package.
         *
         * (c) Simon Leblanc <contact@leblanc-simon.eu>
         *
         * For the full copyright and license information, please view the LICENSE
         * file that was distributed with this source code.
         */
        -->
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
            include __DIR__.'/user/user.php';
            include __DIR__.'/group/group.php';
            include __DIR__.'/account_type/account_type.php';
            include __DIR__.'/field/field.php';
        }
        ?>
        </div>

        <div id="loading" class="hide">
            <div class="floatingCirclesG">
                <div class="f_circleG" id="frotateG_01"></div>
                <div class="f_circleG" id="frotateG_02"></div>
                <div class="f_circleG" id="frotateG_03"></div>
                <div class="f_circleG" id="frotateG_04"></div>
                <div class="f_circleG" id="frotateG_05"></div>
                <div class="f_circleG" id="frotateG_06"></div>
                <div class="f_circleG" id="frotateG_07"></div>
                <div class="f_circleG" id="frotateG_08"></div>
            </div>
        </div>

        <!-- JavaScript plugins (requires jQuery) -->
        <script src="http://code.jquery.com/jquery.js"></script>
        <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
        <?php include __DIR__.'/lang.js.php'; ?>
        <?php if ($must_be_login === false) { ?>
        <script src="<?php echo $this->request->getBasePath() ?>/js/vendor/jquery.loadTemplate-1.2.1.js"></script>
        <script src="<?php echo $this->request->getBasePath() ?>/js/vendor/chosen.jquery.min.js"></script>
        <script src="<?php echo $this->request->getBasePath() ?>/js/functions.js"></script>
        <script src="<?php echo $this->request->getBasePath() ?>/js/admin.prototype.js"></script>
        <script src="<?php echo $this->request->getBasePath() ?>/js/user.js"></script>
        <script src="<?php echo $this->request->getBasePath() ?>/js/group.js"></script>
        <script src="<?php echo $this->request->getBasePath() ?>/js/account_type.js"></script>
        <script src="<?php echo $this->request->getBasePath() ?>/js/field.js"></script>
        <script src="<?php echo $this->request->getBasePath() ?>/js/account.js"></script>
        <script src="<?php echo $this->request->getBasePath() ?>/js/app.js"></script>
        <?php } ?>
    </body>
</html>