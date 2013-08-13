<?php
/**
 * This file is part of the OpenPasswd package.
 *
 * (c) Simon Leblanc <contact@leblanc-simon.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenPasswd\Application;

class Index extends AbstractApp
{
    /**
     * Default action page
     */
    public function defaultAction()
    {
        return $this->getTemplate(false);
    }


    /**
     * Login action page
     */
    public function loginAction()
    {
        return $this->getTemplate(true);
    }


    private function getTemplate($must_be_login)
    {
        ob_start();
        include __DIR__.'/../../../templates/layout.php';
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }
}