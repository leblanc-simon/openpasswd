<?php
/**
 * This file is part of the OpenPasswd package.
 *
 * (c) Simon Leblanc <contact@leblanc-simon.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenPasswd\Core;

use OpenPasswd\Provider\Doctrine as DoctrineProvider;
use OpenPasswd\Provider\Security as SecurityProvider;
use OpenPasswd\Provider\Translation as TranslationProvider;
use OpenPasswd\Provider\Url as UrlProvider;
use OpenPasswd\Routing\Module as ModuleRouting;
use OpenPasswd\Routing\Specific as SpecificRouting;

/**
 * Application class
 *
 * @package     OpenPasswd\Core
 * @version     1.0.0
 * @license     http://opensource.org/licenses/MIT  MIT
 * @author      Simon Leblanc <contact@leblanc-simon.eu>
 */
class Application
{
    static private $available_modules = array(
        'Account'       => '/accounts',
        'AccountType'   => '/account-types',
        'Field'         => '/fields',
        'Group'         => '/groups',
        'User'          => '/users',
    );

    static private $app = null;


    static public function run()
    {
        self::getSilexApplication()['debug'] = Config::get('debug', false);

        self::getRouting();

        self::getRegisters();

        self::$app->run();
    }


    /**
     * Generate the routing of the application
     */
    static private function getRouting()
    {
        $app = self::getSilexApplication();

        foreach (self::$available_modules as $class => $route) {
            $module_routing = new ModuleRouting();
            $module_routing
                ->setApp($app)
                ->setModule($class)
                ->setPrefixRoute($route)
                ->generateRouting();
        }

        $specific_routing = new SpecificRouting();
        $specific_routing
            ->setApp($app)
            ->generateRouting();
    }


    /**
     * Initialize the register provider
     */
    static private function getRegisters()
    {
        $app = self::getSilexApplication();

        $providers = array(
            new DoctrineProvider(),
            new SecurityProvider(),
            new UrlProvider(),
            new TranslationProvider()
        );

        foreach ($providers as $provider) {
            $provider
                ->setApp($app)
                ->register()
                ->configure();
        }
    }


    /**
     * Return the Silex Application object
     *
     * @return     \Silex\Application         The silex Application object
     */
    static private function getSilexApplication()
    {
        if (self::$app === null) {
            self::$app = new \Silex\Application();
        }

        return self::$app;
    }
}
