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

    }


    private function insert()
    {
        list($name, $description, $crypt, $type) = $this->getDataFromForm();

        $sql = 'INSERT INTO '.$this->table.'
                (slug, name, description, crypt, type) VALUES
                (:slug, :name, :description, :crypt, :type);';

        $stmt = $this->db->prepare($sql);
        if ($stmt === false) {
            throw new \Exception('Error while insert the field'.(Config::get('debug', false) ?: ' : '.$sql), 500);
        }

        $res = $stmt->execute(array(
            ':slug' => $slug,
            ':name' => $name,
            ':description' => $description,
            ':crypt' => $crypt,
            ':type' => $type,
        ));

        if ($res === true) {
            return new JsonResponse(array('message' => 'The field is save'), 201);
        }
    }


    private function update($slug)
    {
        $sql = 'SELECT '.$this->fields.' FROM '.$this->db->quoteIdentifier($this->table).($this->criteria ?: ' WHERE 1=1').' AND slug = ?';
        $object = $this->db->fetchAssoc($sql, array_merge($this->criteria_values, array($slug)));

        if ($object === false) {
            return new ErrorResponse('Impossible to find object '.$slug, 404);
        }

        list($name, $description, $crypt, $type) = $this->getDataFromForm();

        $sql = 'UPDATE '.$this->table.' SET
                    name = :name, 
                    description = :description, 
                    crypt = :crypt, 
                    type = :type
                    WHERE id = :id;';

        $stmt = $this->db->prepare($sql);
        if ($stmt === false) {
            throw new \Exception('Error while insert the field'.(Config::get('debug', false) ?: ' : '.$sql), 500);
        }

        $res = $stmt->execute(array(
            ':name' => $name,
            ':description' => $description,
            ':crypt' => $crypt,
            ':type' => $type,
            ':id' => $object['id'],
        ));

        if ($res === true) {
            return new JsonResponse(array('message' => 'The field is save'), 200);
        }
    }

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

        return array((string)$name, (string)$description, (int)$crypt, (string)$type);
    }
}