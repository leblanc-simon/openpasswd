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

use OpenPasswd\Core\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use OpenPasswd\Core\ErrorResponse;

class User extends AbstractApp implements IApplication
{
    public function __construct(\Silex\Application $app)
    {
        parent::__construct($app);

        $this->table            = 'user';
        $this->fields           = 'id, slug, username, name, created_at, updated_at, last_connection';
        $this->order            = 'name ASC';
        $this->criteria         = null;
        $this->criteria_values  = array();
        $this->search           = array('slug', 'username', 'name');
    }


    /**
     * Get an iten in the model by slug
     */
    public function getAction($slug)
    {
        $object = $this->retrieveBySlug($slug);

        if ($object === false) {
            return new ErrorResponse('Impossible to find object '.$slug, 404);
        }

        // Get the groups enable for the user
        $sql = 'SELECT g.id, g.slug, g.name, g.description
                FROM '.$this->db->quoteIdentifier('group').' g
                    INNER JOIN '.$this->db->quoteIdentifier('user_has_group').' ug
                        ON g.id = ug.group_id
                WHERE ug.user_id = :user_id
                ORDER BY g.name';
        $fields = $this->db->fetchAll($sql, array(':user_id' => $object['id']));

        $object['enable_groups'] = $fields;

        // Get the groups always available for the user
        $sql = 'SELECT DISTINCT g.id, g.slug, g.name, g.description
                FROM '.$this->db->quoteIdentifier('group').' g
                WHERE g.id NOT IN (
                    SELECT ug.group_id
                    FROM '.$this->db->quoteIdentifier('user_has_group').' ug
                    WHERE ug.user_id = :user_id
                )
                ORDER BY g.name';
        $fields = $this->db->fetchAll($sql, array(':user_id' => $object['id']));

        $object['available_groups'] = $fields;

        return new JsonResponse($object);
    }

    /**
     * Save the data in the model
     */
    public function saveAction($slug = null)
    {
        try {
            if ($slug === null) {
                return $this->insert();
            } else {
                return $this->update($slug);
            }
        } catch (\Exception $e) {
            return new ErrorResponse($e->getMessage(), $e->getCode() !== 0 ? $e->getCode() : 500);
        }
    }

    /**
     * Remove the data in the model
     */
    public function deleteAction($slug)
    {
        try {
            $object = $this->retrieveBySlug($slug);

            if ($object === false) {
                return new ErrorResponse('Impossible to find object '.$slug, 404);
            }

            $this->db->delete('user_has_group', array('user_id' => $object['id']));
            $this->db->delete($this->db->quoteIdentifier($this->table), array('id' => $object['id']));

            return new JsonResponse(array('message' => 'The user is deleted', 'object' => $object), 200);
        } catch (\Exception $e) {
            return new ErrorResponse($e->getMessage(), $e->getCode() !== 0 ? $e->getCode() : 500);
        }
    }


    /**
     * Insert a new user
     */
    private function insert()
    {
        list($name, $username, $password, $groups) = $this->getDataFromForm(false);

        $slug = $this->getSlug($name);

        try {
            $now = date('Y-m-d H:i:s');
            $this->db->insert($this->db->quoteIdentifier($this->table), array(
                'slug' => $slug,
                'name' => $name,
                'username' => $username,
                'passwd' => Security::hash($password),
                'created_at' => $now,
                'updated_at' => $now,
            ));

            $object = $this->retrieveBySlug($slug);

            // Save groups
            foreach ($groups as $group) {
                $this->db->insert('user_has_group', array(
                    'user_id' => $object['id'],
                    'group_id' => $group,
                ));
            }
            
            return new JsonResponse(array('message' => 'The user is save', 'object' => $object), 201);
        } catch (\Exception $e) {
            return new ErrorResponse('Error while save the user');
        }
    }


    /**
     * Update an existing user
     */
    private function update($slug)
    {
        $object = $this->retrieveBySlug($slug);

        if ($object === false) {
            return new ErrorResponse('Impossible to find object '.$slug, 404);
        }

        list($name, $username, $password, $groups) = $this->getDataFromForm(true);

        try {
            $update_data = array('name' => $name, 'username' => $username, 'updated_at' => date('Y-m-d H:i:s'));
            if (empty($password) === false) {
                $update_data['passwd'] = Security::hash($password);
            }
            $this->db->update($this->db->quoteIdentifier($this->table), $update_data, array('id' => $object['id']));

            // Save fields
            $this->db->delete('user_has_group', array('user_id' => $object['id']));
            foreach ($groups as $group) {
                $this->db->insert('user_has_group', array(
                    'user_id' => $object['id'],
                    'group_id' => $group,
                ));
            }
            
            return new JsonResponse(array('message' => 'The user is save'), 200);
        } catch (\Exception $e) {
            return new ErrorResponse('Error while save the user');
        }
    }


    /**
     * Extract all parameters from the HTTP request
     *
     * @param   bool    $allow_empty_password               True : allow empty password from the HTTP request, false else
     * @return  array<name, username, password, groups>     The datas of the request
     */
    private function getDataFromForm($allow_empty_password)
    {
        $name = $this->request->get('name', '');
        $username = $this->request->get('username', '');
        $password = $this->request->get('password', '');
        $groups = $this->request->get('groups', '[]');

        if (is_string($name) === false || empty($name) === true) {
            throw new \Exception('Name can\'t be empty', 400);
        }

        if (is_string($username) === false || empty($username) === true) {
            throw new \Exception('Username must be a string', 400);
        }

        if (is_string($password) === false) {
            throw new \Exception('Password must be a string', 400);
        }
        if ($allow_empty_password === false && empty($password) === true) {
            throw new \Exception('Password can\'t be empty', 400);
        }

        if (is_string($groups) === false || ($groups = json_decode($groups)) === false || is_array($groups) === false) {
            throw new \Exception('Groups must be a JSON array', 400);
        }

        return array(trim((string)$name), trim((string)$username), trim((string)$password), $groups);
    }
}