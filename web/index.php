<?php
/**
 * This file is part of the OpenPasswd package.
 *
 * (c) Simon Leblanc <contact@leblanc-simon.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../vendor/autoload.php';

if (file_exists(__DIR__.'/../app/config.php') === false) {
    // TODO : call install
}

require_once __DIR__.'/../app/config.php';

OpenPasswd\Core\Application::run();