#!/usr/bin/php
<?php
/**
 * This file is part of the OpenPasswd package.
 *
 * (c) Simon Leblanc <contact@leblanc-simon.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../../vendor/autoload.php';

if (file_exists(__DIR__.'/../config.php') === false) {
    throw new \Exception('config.php doesn\'t exists');
}

require_once __DIR__.'/../config.php';

$application = new \Symfony\Component\Console\Application();
$application->add(new \OpenPasswd\Command\CertificateCommand());

$application->run();