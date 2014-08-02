<?php
/**
 * This file is part of the OpenPasswd package.
 *
 * (c) Simon Leblanc <contact@leblanc-simon.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenPasswd\User;

use Doctrine\DBAL\Connection;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class WebserviceUserProvider implements UserProviderInterface
{
    private $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * Loads the user for the given username.
     *
     * This method must throw UsernameNotFoundException if the user is not
     * found.
     *
     * @param string $username The username
     *
     * @return UserInterface
     *
     * @see UsernameNotFoundException
     *
     * @throws UsernameNotFoundException if the user is not found
     *
     */
    public function loadUserByUsername($username)
    {
        $stmt = $this->db->executeQuery('SELECT * FROM user WHERE username = ?', array($username));
        $user = $stmt->fetch();

        if (false === $user) {
            throw new UsernameNotFoundException('Username '.$username.' not found');
        }

        $stmt = $this->db->executeQuery('SELECT g.id, g.slug
                    FROM '.$this->db->quoteIdentifier('group').' g
                    INNER JOIN '.$this->db->quoteIdentifier('user_has_group').' ug
                        ON g.id = ug.group_id
                    WHERE ug.user_id = ?',
                array($user['id'])
        );

        $tmp_roles = $stmt->fetchAll();
        $roles = array();

        foreach ($tmp_roles as $role) {
            $roles[] = new Role($role['id'], $role['slug']);
        }

        return new WebserviceUser($user['username'], $user['passwd'], $roles);
    }

    /**
     * Refreshes the user for the account interface.
     *
     * It is up to the implementation to decide if the user data should be
     * totally reloaded (e.g. from the database), or if the UserInterface
     * object can just be merged into some internal array of users / identity
     * map.
     * @param UserInterface $user
     *
     * @return UserInterface
     *
     * @throws UnsupportedUserException if the account is not supported
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof WebserviceUser) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * Whether this provider supports the given user class
     *
     * @param string $class
     *
     * @return Boolean
     */
    public function supportsClass($class)
    {
        return $class === '\\OpenPasswd\\User\\WebserviceUser';
    }

}