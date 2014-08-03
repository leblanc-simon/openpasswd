<?php
/**
 * This file is part of the OpenPasswd package.
 *
 * (c) Simon Leblanc <contact@leblanc-simon.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenPasswd\Security;

use OpenPasswd\Core\Config;
use Symfony\Component\Security\Core\User\UserInterface;
use Silex\Application;

class Security
{
    /**
     * @var Application
     */
    private $app;

    /**
     * @var UserInterface
     */
    private $user;

    public function __construct(Application $app, UserInterface $user)
    {
        $this->app = $app;
        $this->user = $user;
    }


    public function getEnableGroups()
    {
        $role_ids = array();

        foreach ($this->user->getRoles() as $role) {
            $role_ids[] = $role->getId();
        }

        return $role_ids;
    }


    public function isAllowedToShowParameters()
    {
        foreach ($this->user->getRoles() as $role) {
            if ('admin' === $role->getRole()) {
                return true;
            }
        }

        return false;
    }


    public function isAllowedToShowAccount($account_id)
    {
        $groups = $this->getEnableGroups();

        $count = $this->app['db']->executeQuery('SELECT COUNT(*) as nb
                                                 FROM account_has_group
                                                 WHERE account_id = ?
                                                    AND
                                                    group_id IN ('.implode(', ', array_fill(0, count($groups), '?')).')'
                                                , array_merge(
                                                    array($account_id),
                                                    $groups
                                                ))
                                 ->fetch();

        return (bool)$count['nb'];
    }


    public function encrypt($value)
    {
        list($cipher_method, $password, $iv) = $this->getCryptDatas();

        return openssl_encrypt($value, $cipher_method, $password, 0, $iv);
    }


    public function decrypt($value)
    {
        list($cipher_method, $password, $iv) = $this->getCryptDatas();

        return openssl_decrypt($value, $cipher_method, $password, 0, $iv);
    }


    private function getCryptDatas()
    {
        $cipher_method = Config::get('crypt_method');
        $password = Config::get('crypt_password');
        $iv = Config::get('crypt_iv');

        // Master password : must in the certificate
        // TODO : get it in the certificate
        $master = Config::get('crypt_masterpw');

        $iv = substr($iv, 0, openssl_cipher_iv_length($cipher_method));

        $password = openssl_decrypt($password, $cipher_method, $master, 0, $iv);

        return array($cipher_method, $password, $iv);
    }


    /**
     * Get the hash from the password
     *
     * @param   string  $password   The original password
     * @return  string              The hash of the password
     */
    static public function hash($password)
    {
        if (version_compare(PHP_VERSION, '5.3.7', '<')) {
            return Passwd::password_hash($password, Passwd::PASSWORD_SHA512);
        } elseif (version_compare(PHP_VERSION, '5.5.0', '<')) {
            return Passwd::password_hash($password, Passwd::PASSWORD_BCRYPT);
        } else {
            return password_hash($password, PASSWORD_BCRYPT);
        }
    }

    static public function verify($password, $hash)
    {
        if (version_compare(PHP_VERSION, '5.5.0', '<')) {
            return Passwd::password_verify($password, $hash);
        } else {
            return password_verify($password, $hash);
        }
    }
}