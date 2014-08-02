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

use Symfony\Component\Security\Core\Role\RoleInterface;

class Role implements RoleInterface
{
    private $id;
    private $role;

    /**
     * Constructor.
     *
     * @param   int     $id     The role id
     * @param   string  $role   The role name
     */
    public function __construct($id, $role)
    {
        $this->id = (int)$id;
        $this->role = (string)$role;
    }

    /**
     * {@inheritdoc}
     */
    public function getRole()
    {
        return $this->role;
    }


    public function getId()
    {
        return $this->id;
    }
}