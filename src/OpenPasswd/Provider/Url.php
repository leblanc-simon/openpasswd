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

use Silex\Provider\UrlGeneratorServiceProvider;


/**
 * Url provider class
 *
 * @package     OpenPasswd\Provider
 * @version     1.0.0
 * @license     http://opensource.org/licenses/MIT  MIT
 * @author      Simon Leblanc <contact@leblanc-simon.eu>
 */
class Url
    extends AProvider
    implements IProvider
{
    /**
     * Register the provider into the Silex Application
     * @return $this
     */
    public function register()
    {
        $this->app->register(new UrlGeneratorServiceProvider());
        return $this;
    }

    /**
     * Configure the provider
     * @return $this
     */
    public function configure()
    {
        // nothing to do
        return $this;
    }
}