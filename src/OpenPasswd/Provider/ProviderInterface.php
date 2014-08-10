<?php
/**
 * This file is part of the OpenPasswd package.
 *
 * (c) Simon Leblanc <contact@leblanc-simon.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenPasswd\Provider;

use Silex\Application;

interface ProviderInterface
{
    /**
     * Setup the Silex Application
     *
     * @param Application $app
     * @return mixed
     */
    public function setApp(Application $app);

    /**
     * Register the provider into the Silex Application
     * @return $this
     */
    public function register();

    /**
     * Configure the provider
     * @return $this
     */
    public function configure();
}