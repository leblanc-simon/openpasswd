<?php
/**
 * This file is part of the OpenPasswd package.
 *
 * (c) Simon Leblanc <contact@leblanc-simon.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenPasswd\Routing;

use Silex\Application;
use OpenPasswd\Application\Index as AppIndex;
use OpenPasswd\Application\Account as AppAccount;


/**
 * Specific class
 *
 * @package     OpenPasswd\Routing
 * @version     1.0.0
 * @license     http://opensource.org/licenses/MIT  MIT
 * @author      Simon Leblanc <contact@leblanc-simon.eu>
 */
class Specific
{
    /**
     * @var Application
     */
    private $app;


    /**
     * Setup the Silex Application
     *
     * @param Application $app
     * @return $this
     */
    public function setApp(Application $app)
    {
        $this->app = $app;
        return $this;
    }


    /**
     * Generate the specific routing
     */
    public function generateRouting()
    {
        $this->buildHome();
        $this->buildLogin();
        $this->buildAccountShow();
    }


    /**
     * Build the homepage route
     */
    private function buildHome()
    {
        $this->app->get('/', function() {
            $index = new AppIndex($this->app);
            return $index->defaultAction();
        })->bind('homepage');
    }


    /**
     * Build the login form route
     */
    private function buildLogin()
    {
        $this->app->get('/login', function() {
            $index = new AppIndex($this->app);
            return $index->loginAction();
        })->bind('login_path');
    }


    /**
     * Build the account show route
     */
    private function buildAccountShow()
    {
        $this->app->get('/accounts/show/{slug}', function($slug) {
            $account = new AppAccount($this->app);

            return $account->showAction($slug);
        })->bind('account_show');
    }
}