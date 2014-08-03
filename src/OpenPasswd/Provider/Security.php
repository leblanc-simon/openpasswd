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

use OpenPasswd\Security\Encoder\PasswordEncoder;
use OpenPasswd\User\WebserviceUserProvider;
use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\SessionServiceProvider;


/**
 * Security provider class
 *
 * @package     OpenPasswd\Provider
 * @version     1.0.0
 * @license     http://opensource.org/licenses/MIT  MIT
 * @author      Simon Leblanc <contact@leblanc-simon.eu>
 */
class Security
    extends AProvider
    implements IProvider
{
    /**
     * Register the provider into the Silex Application
     * @return $this
     */
    public function register()
    {
        $this->app->register(new SessionServiceProvider());
        $this->app->register(new SecurityServiceProvider(), array(
            'security.firewalls' => array(
                'login' => array(
                    'pattern'   => '^/login$',
                    'anonymous' => true,
                ),
                'secured' => array(
                    'pattern'   => '^.*$',
                    'form'      => array('login_path' => '/login', 'check_path' => '/login_check'),
                    'logout'    => array('logout_path' => '/logout'),
                    'users'     => $this->app->share(function() {
                            return new WebserviceUserProvider($this->app['db']);
                    }),
                ),
            ),
        ));

        return $this;
    }

    /**
     * Configure the provider
     * @return $this
     */
    public function configure()
    {
        $this->app['security.encoder.digest'] = $this->app->share(function() {
            return new PasswordEncoder();
        });

        return $this;
    }
}