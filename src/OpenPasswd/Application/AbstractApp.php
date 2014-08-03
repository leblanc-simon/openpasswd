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

use OpenPasswd\Security\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use OpenPasswd\Core\ErrorResponse;
use OpenPasswd\Core\Config;
use OpenPasswd\Core\Utils;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractApp
{
    protected $app      = null;

    /**
     * @var \Doctrine\DBAL\Connection
     */
    protected $db       = null;
    protected $request  = null;
    protected $response = null;
    protected $user     = null;
    protected $security = null;

    protected $table            = null;
    protected $fields           = null;
    protected $order            = null;
    protected $criteria         = null;
    protected $criteria_values  = array();
    protected $search           = array();


    public function __construct(\Silex\Application $app)
    {
        $this->app          = $app;
        $this->db           = $app['db'];
        $this->request      = $app['request'];
        $this->response     = new Response();

        $token = $app['security']->getToken();
        if (null !== $token) {
            $this->user = $token->getUser();
            if ($this->user instanceof UserInterface) {
                $this->security = new Security($this->app, $this->user);


            }
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
        $object = $this->retrieveBySlug($slug);

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


    protected function getSlug($name)
    {
        $base_slug = Utils::toSlug($name);

        $iterator = 0;
        do {
            $slug = $base_slug.($iterator++ > 0 ? '-'.$iterator : '');

            $sql = 'SELECT COUNT(id) as nb FROM '.$this->db->quoteIdentifier($this->table).' WHERE `slug` = :slug';

            $stmt = $this->db->prepare($sql);
            if ($stmt === false) {
                throw new \Exception('Error while count slug'.(Config::get('debug', false) ?: ' : '.$sql), 500);
            }

            $row = $stmt->execute(array(':slug' => $slug));
            if ($row === false || $row['nb'] == 0) {
                $final_slug = $slug;
            }

        } while (isset($final_slug) === false);

        return $final_slug;
    }


    protected function retrieveBySlug($slug)
    {
        $sql = 'SELECT '.$this->fields.' FROM '.$this->db->quoteIdentifier($this->table).($this->criteria ?: ' WHERE 1=1').' AND slug = ?';
        return $this->db->fetchAssoc($sql, array_merge($this->criteria_values, array($slug)));
    }


    /**
     * Translate a term
     *
     * @param   string  $id             The term to translate
     * @param   array   $parameters     The parameter for the translate
     * @param   null    $domain         The domain of the translation (null = current domain)
     * @param   null    $locale         The locale to use (null = current locale)
     * @return  string                  The term translated
     */
    public function l10n($id, array $parameters = array(), $domain = null, $locale = null)
    {
        return $this->app['translator']->trans($id, $parameters, $domain, $locale);
    }


    /**
     * @param   string  $name
     * @param   array   $parameters
     * @param   bool    $reference_type
     * @return  mixed
     */
    public function url($name, $parameters = array(), $reference_type = UrlGenerator::ABSOLUTE_PATH)
    {
        return $this->app['url_generator']->generate($name, $parameters, $reference_type);
    }
}