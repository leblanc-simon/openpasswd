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
        $master_password = Config::get('master_password');

        $certificate_password = $this->getCertificatePassword();

        $iv = substr($iv, 0, openssl_cipher_iv_length($cipher_method));

        $password = openssl_decrypt(
            $password,
            $cipher_method,
            openssl_decrypt(
                $certificate_password,
                $cipher_method,
                $master_password,
                0, $iv
            ), 0, $iv
        );

        return array($cipher_method, $password, $iv);
    }


    /**
     * Extract the certificate password
     *
     * @return mixed|null
     */
    private function getCertificatePassword()
    {
        if (isset($_SERVER) === false || isset($_SERVER['SSL_CLIENT_CERT']) === false) {
            return null;
        }

        $certificate = openssl_x509_parse($_SERVER['SSL_CLIENT_CERT']);
        if (
            is_array($certificate) === false ||
            isset($certificate['name']) === false ||
            isset($certificate['subject']) === false
        ) {
            return null;
        }

        preg_match_all('#/[^=]+=#', $certificate['name'], $matches);
        $position = array_search('/'.Config::get('certificate_oid').'=', $matches[0]);

        if ($position === false || count($certificate['subject']) < $position) {
            return count($certificate['subject']).' - '.$position;
        }

        return current(array_slice($certificate['subject'], $position, 1));
    }


    /**
     * Get the hash from the password
     *
     * @param   string  $password   The original password
     * @return  string              The hash of the password
     */
    static public function hash($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    static public function verify($password, $hash)
    {
        return password_verify($password, $hash);
    }
}