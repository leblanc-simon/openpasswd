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

abstract class AbstractApp
{
    protected $app      = null;
    protected $db       = null;
    protected $request  = null;
    protected $response = null;
    protected $user     = null;

    protected $table            = null;
    protected $fields           = null;
    protected $order            = null;
    protected $criteria         = null;
    protected $criteria_values  = array();
    protected $search           = array();


    public function __construct(\Silex\Application $app)
    {
        $this->app         = $app;
        $this->db          = $app['db'];
        $this->request     = $app['request'];
        $this->response = new \Symfony\Component\HttpFoundation\Response();

        $token = $app['security']->getToken();
        if (null !== $token) {
            $this->user = $token->getUser();
        }
    }
    /**
     * List all items in the model
     */
    public function listAction()
    {
        $sql = 'SELECT '.$this->fields.' FROM '.$this->db->quoteIdentifier($this->table).($this->criteria ?: '').' ORDER BY '.$this->order;
        $objects = $this->db->fetchAll($sql, $this->criteria_values);

        return new JsonResponse($objects);
    }

    /**
     * Get an iten in the model by slug
     */
    public function getAction($slug)
    {
        $sql = 'SELECT '.$this->fields.' FROM '.$this->db->quoteIdentifier($this->table).($this->criteria ?: ' WHERE 1=1').' AND slug = ?';
        $object = $this->db->fetchAssoc($sql, array_merge($this->criteria_values, array($slug)));

        if ($object === false) {
            return new ErrorResponse('Impossible to find object '.$slug, 404);
        }

        return new JsonResponse($object);
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

        $available_searchs = $this->search;

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
        $sql = 'SELECT '.$this->fields.' FROM '.$this->db->quoteIdentifier($this->table).($this->criteria ?: ' WHERE 1=1').' AND '.implode(' AND ', $where);
        $objects = $this->db->fetchAll($sql, array_merge($this->criteria_values, array($where_values)));

        return new JsonResponse($objects);
    }
}