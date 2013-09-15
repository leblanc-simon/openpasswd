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
use OpenPasswd\Core\Config;

class Field extends AbstractApp implements IApplication
{
    static private $available_types = array('text','textarea','date','numeric','email','url');

    public function __construct(\Silex\Application $app)
    {
        parent::__construct($app);

        $this->table            = 'field';
        $this->fields           = 'id, slug, name, description, crypt, type';
        $this->order            = 'name ASC';
        $this->criteria         = null;
        $this->criteria_values  = array();
        $this->search           = array('name');
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
        throw new \Exception('Not implemented', 501);
    }


    /**
     * Insert a new field
     */
    private function insert()
    {
        list($name, $description, $crypt, $type) = $this->getDataFromForm();

        $slug = $this->getSlug($name);

        try {
            $this->db->insert($this->table, array(
                'slug' => $slug,
                'name' => $name,
                'description' => $description,
                'crypt' => $crypt,
                'type' => $type,
            ));

            $object = $this->retrieveBySlug($slug);
            
            return new JsonResponse(array('message' => 'The field is save', 'object' => $object), 201);
        } catch (\Exception $e) {
            return new ErrorResponse('Error while save the field');
        }
    }


    /**
     * Update an existing field
     */
    private function update($slug)
    {
        $object = $this->retrieveBySlug($slug);

        if ($object === false) {
            return new ErrorResponse('Impossible to find object '.$slug, 404);
        }

        list($name, $description, $crypt, $type) = $this->getDataFromForm();

        try {
            $this->db->update($this->table, array(
                'name' => $name,
                'description' => $description,
                'crypt' => $crypt,
                'type' => $type,
            ), array('id' => $object['id']));

            return new JsonResponse(array('message' => 'The field is save'), 200);
        } catch (\Exception $e) {
            return new ErrorResponse('Error while save the field');
        }
    }


    /**
     * Extract all parameters from the HTTP request
     *
     * @return  array<name, description, crypt, type>     The datas of the request
     */
    private function getDataFromForm()
    {
        $name = $this->request->get('name', '');
        $description = $this->request->get('description', '');
        $crypt = $this->request->get('crypt', '0');
        $type = $this->request->get('type', '');

        if (is_string($name) === false || empty($name) === true) {
            throw new \Exception('Name can\'t be empty', 400);
        }

        if (is_string($description) === false) {
            throw new \Exception('Description must be a string', 400);
        }

        if (is_numeric($crypt) === false || ($crypt != 0 && $crypt != 1)) {
            throw new \Exception('Crypt must be 0 or 1', 400);
        }

        if (is_string($type) === false || empty($type) === true) {
            throw new \Exception('Type can\'t be empty', 400);
        }

        if (in_array($type, self::$available_types) === false) {
            throw new \Exception('Type must be : '.implode(' or ', self::$available_types), 400);
        }

        return array(trim((string)$name), trim((string)$description), (int)$crypt, (string)$type);
    }
}