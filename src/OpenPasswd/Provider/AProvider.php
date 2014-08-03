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

abstract class AProvider
{
    /**
     * @var Application
     */
    protected $app;


    /**
     * Setup the Silex Application
     *
     * @param   Application $app
     * @return  $this
     */
    public function setApp(Application $app)
    {
        $this->app = $app;
        return $this;
    }
}
