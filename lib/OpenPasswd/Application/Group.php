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

    }


    private function insert()
    {
        list($name, $description) = $this->getDataFromForm();

        $slug = $this->getSlug($name);

        $sql = 'INSERT INTO '.$this->db->quoteIdentifier($this->table).'
                (slug, name, description, created_at, updated_at) VALUES
                (:slug, :name, :description, :date, :date);';

        $stmt = $this->db->prepare($sql);
        if ($stmt === false) {
            throw new \Exception('Error while insert the field'.(Config::get('debug', false) ?: ' : '.$sql), 500);
        }

        $res = $stmt->execute(array(
            ':slug' => $slug,
            ':name' => $name,
            ':description' => $description,
            ':date' => date('Y-m-d H:i:s'),
        ));

        if ($res === true) {
            $object = $this->retrieveBySlug($slug);
            return new JsonResponse(array('message' => 'The group is save', 'object' => $object), 201);
        } else {
            return new ErrorResponse('Error while save the group');
        }
    }


    private function update($slug)
    {
        $object = $this->retrieveBySlug($slug);

        if ($object === false) {
            return new ErrorResponse('Impossible to find object '.$slug, 404);
        }

        list($name, $description) = $this->getDataFromForm();

        $sql = 'UPDATE '.$this->db->quoteIdentifier($this->table).' SET
                    name = :name, 
                    description = :description, 
                    updated_at = :date
                    WHERE id = :id;';

        $stmt = $this->db->prepare($sql);
        if ($stmt === false) {
            throw new \Exception('Error while update the group'.(Config::get('debug', false) ?: ' : '.$sql), 500);
        }

        $res = $stmt->execute(array(
            ':name' => $name,
            ':description' => $description,
            ':date' => date('Y-m-d H:i:s'),
            ':id' => $object['id'],
        ));

        if ($res === true) {
            return new JsonResponse(array('message' => 'The group is save'), 200);
        } else {
            return new ErrorResponse('Error while save the group');
        }
    }

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

        return array((string)$name, (string)$description);
    }
}