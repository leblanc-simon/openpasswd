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
     * Search items in the model
     */
    public function searchAction($search)
    {
        $searchs = explode(',', $search);
        if (false === is_array($searchs) || true === empty($search)) {
            return new ErrorResponse('Bad Query', 400);
        }

        $available_searchs = array('slug', 'username', 'name');

        $where = array();
        $where_values = array();

        foreach ($searchs as $search) {
            list($key, $value) = explode('=', $search);
            if (false === in_array($key, $available_searchs)) {
                return new ErrorResponse($key.' is not in available key search ('.implode(', ', $available_searchs).')', 400);
            }

            $where[] = $key.' LIKE ?';
            $where_values[] = '%'.$value.'%';
        }
        $sql = 'SELECT id, slug, username, name, created_at, updated_at, last_connection
                FROM user
                WHERE '.implode(' AND ', $where);
        $users = $this->db->fetchAll($sql, $where_values);

        return new JsonResponse($users);
    }

    /**
     * Save the data in the model
     */
    public function saveAction($slug = null)
    {

    }

    /**
     * Remove the data in the model
     */
    public function deleteAction($slug)
    {

    }
}