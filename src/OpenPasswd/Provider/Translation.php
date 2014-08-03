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
use Silex\Provider\TranslationServiceProvider;
use Symfony\Component\Translation\Loader\YamlFileLoader;


/**
 * Translation provider class
 *
 * @package     OpenPasswd\Provider
 * @version     1.0.0
 * @license     http://opensource.org/licenses/MIT  MIT
 * @author      Simon Leblanc <contact@leblanc-simon.eu>
 */
class Translation
    extends AProvider
    implements IProvider
{
    /**
     * Register the provider into the Silex Application
     * @return $this
     */
    public function register()
    {
        $this->app->register(new TranslationServiceProvider(), array(
            'locale_fallbacks' => array('en'),
        ));

        return $this;
    }

    /**
     * Configure the provider
     * @return $this
     */
    public function configure()
    {
        $this->app['translator'] = $this->app->share(
            $this->app->extend(
                'translator',
                function($translator) {
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
                }
            )
        );

        $this->app['translator']->setLocale(Config::get('locale'));

        return $this;
    }
}