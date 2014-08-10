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

use OpenPasswd\Core\Config;
use Silex\Provider\DoctrineServiceProvider;


/**
 * Doctrine provider class
 *
 * @package     OpenPasswd\Provider
 * @version     1.0.0
 * @license     http://opensource.org/licenses/MIT  MIT
 * @author      Simon Leblanc <contact@leblanc-simon.eu>
 */
class Doctrine
    extends AProvider
    implements ProviderInterface
{
    /**
     * Register the provider into the Silex Application
     * @return $this
     */
    public function register()
    {
        $this->app->register(new DoctrineServiceProvider(), array(
            'db.options' => Config::get('doctrine-configuration'),
        ));

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