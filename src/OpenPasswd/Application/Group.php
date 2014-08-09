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
use OpenPasswd\Core\ErrorResponse;

class Group extends AbstractApp implements IApplication
{
    public function __construct(\Silex\Application $app)
    {
        parent::__construct($app);

        $this->table            = 'group';
        $this->fields           = 'id, slug, name, description, created_at, updated_at';
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
     * Insert a new group
     */
    private function insert()
    {
        try {
            list($name, $description) = $this->getDataFromForm();

            $slug = $this->getSlug($name);

            $now = date('Y-m-d H:i:s');
            $this->db->insert($this->db->quoteIdentifier($this->table), array(
                'slug' => $slug,
                'name' => $name,
                'description' => $description,
                'created_at' => $now,
                'updated_at' => $now,
            ));

            $object = $this->retrieveBySlug($slug);
            return new JsonResponse(array('message' => 'The group is save', 'object' => $object), 201);
        } catch (\Exception $e) {
            return new ErrorResponse('Error while save the group : '.$e->getMessage());
        }
    }


    /**
     * Update an existing group
     */
    private function update($slug)
    {
        $object = $this->retrieveBySlug($slug);

        if ($object === false) {
            return new ErrorResponse('Impossible to find object '.$slug, 404);
        }

        list($name, $description) = $this->getDataFromForm();

        try {
            $update_data = array('name' => $name, 'description' => $description, 'updated_at' => date('Y-m-d H:i:s'));
            $this->db->update($this->db->quoteIdentifier($this->table), $update_data, array('id' => $object['id']));
            
            return new JsonResponse(array('message' => 'The group is save'), 200);
        } catch (\Exception $e) {
            return new ErrorResponse('Error while save the group');
        }
    }


    /**
     * Extract all parameters from the HTTP request
     *
     * @return  array<name, description>     The datas of the request
     */
    private function getDataFromForm()
    {
        $name = $this->request->get('name', '');
        $description = $this->request->get('description', '');

        if (is_string($name) === false || empty($name) === true) {
            throw new \Exception('Name can\'t be empty', 400);
        }

        if (is_string($description) === false) {
            throw new \Exception('Description must be a string', 400);
        }

        return array(trim((string)$name), trim((string)$description));
    }
}