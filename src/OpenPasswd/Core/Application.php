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

use OpenPasswd\Routing\Module;
use OpenPasswd\Routing\Specific;
use OpenPasswd\User\WebserviceUserProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Symfony\Component\Translation\Loader\YamlFileLoader;

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
            $module_routing = new Module();
            $module_routing
                ->setApp($app)
                ->setModule($class)
                ->setPrefixRoute($route)
                ->generateRouting();
        }

        $specific_routing = new Specific();
        $specific_routing
            ->setApp($app)
            ->generateRouting();
    }


    /**
     * Initialize the register provider
     */
    static private function getRegisters()
    {
        self::registerDoctrine();
        self::registerSecurity();
        self::registerUrl();
        self::registerTranslation();
    }


    /**
     * Register the doctrine provider
     */
    static private function registerDoctrine()
    {
        self::getSilexApplication()->register(new DoctrineServiceProvider(), array(
            'db.options' => Config::get('doctrine-configuration'),
        ));
    }


    /**
     * Register the security provider
     */
    static private function registerSecurity()
    {
        $app = self::getSilexApplication();

        $app->register(new SessionServiceProvider());
        $app->register(new SecurityServiceProvider(), array(
            'security.firewalls' => array(
                'login' => array(
                    'pattern'   => '^/login$',
                    'anonymous' => true,
                ),
                'secured' => array(
                    'pattern'   => '^.*$',
                    'form'      => array('login_path' => '/login', 'check_path' => '/login_check'),
                    'logout'    => array('logout_path' => '/logout'),
                    'users'     => $app->share(function() use ($app) {
                        return new WebserviceUserProvider($app['db']);
                    }),
                ),
            ),
        ));

        $app['security.encoder.digest'] = $app->share(function($app) {
            return new PasswordEncoder();
        });
    }


    /**
     * Register the URL generator provider
     */
    static private function registerUrl()
    {
        self::getSilexApplication()->register(new UrlGeneratorServiceProvider());
    }


    /**
     * Register the translation provider
     */
    static private function registerTranslation()
    {
        $app = self::getSilexApplication();

        $app->register(new TranslationServiceProvider(), array(
            'locale_fallbacks' => array('en'),
        ));

        $app['translator'] = $app->share($app->extend('translator', function($translator, $app) {
            $translator->addLoader('yaml', new YamlFileLoader());

            $dir = new \DirectoryIterator(Config::get('locales_dir'));

            foreach ($dir as $file) {
                if (preg_match(
                        '/^'.Config::get('locales_domain', 'messages').'\.([a-z_A-Z]+)\.yml$/',
                        $file->getFilename(),
                        $matches) === 1
                ) {
                    $translator->addResource('yaml', $file->getPathname(), $matches[1]);
                }
            }

            return $translator;
        }));

        $app['translator']->setLocale(Config::get('locale'));
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
