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

class AccountType extends AbstractApp implements IApplication
{
    public function __construct(\Silex\Application $app)
    {
        parent::__construct($app);

        $this->table            = 'account_type';
        $this->fields           = 'id, slug, name, description';
        $this->order            = 'name ASC';
        $this->criteria         = null;
        $this->criteria_values  = array();
        $this->search           = array('name');
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

        // Get the fields enable for the account type
        $sql = 'SELECT f.id, f.slug, f.name, f.description, f.crypt, f.type, af.position
                FROM '.$this->db->quoteIdentifier('field').' f
                    INNER JOIN '.$this->db->quoteIdentifier('account_type_has_field').' af
                        ON f.id = af.field_id
                WHERE af.account_type_id = :account_type_id
                ORDER BY af.position';
        $fields = $this->db->fetchAll($sql, array(':account_type_id' => $object['id']));

        $object['enable_fields'] = $fields;

        // Get the fields always available for the account type
        $sql = 'SELECT DISTINCT f.id, f.slug, f.name, f.description, f.crypt, f.type
                FROM '.$this->db->quoteIdentifier('field').' f
                WHERE f.id NOT IN (
                    SELECT af.field_id
                    FROM '.$this->db->quoteIdentifier('account_type_has_field').' af
                    WHERE af.account_type_id = :account_type_id
                )
                ORDER BY f.name';
        $fields = $this->db->fetchAll($sql, array(':account_type_id' => $object['id']));

        $object['available_fields'] = $fields;

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
        throw new \Exception('Not implemented', 501);
    }


    /**
     * Insert a new account type
     */
    private function insert()
    {
        list($name, $description, $fields) = $this->getDataFromForm();

        $slug = $this->getSlug($name);

        try {
            $this->db->insert($this->table, array(
                'slug' => $slug,
                'name' => $name,
                'description' => $description,
            ));

            $object = $this->retrieveBySlug($slug);

            // Save fields
            $position = 0;
            foreach ($fields as $field) {
                $this->db->insert('account_type_has_field', array(
                    'account_type_id' => $object['id'],
                    'field_id' => $field,
                    'position' => $position++,
                ));
            }
            
            return new JsonResponse(array('message' => 'The account type is save', 'object' => $object), 201);
        } catch (\Exception $e) {
            return new ErrorResponse('Error while save the account type');
        }
    }


    /**
     * Update an existing account type
     */
    private function update($slug)
    {
        $object = $this->retrieveBySlug($slug);

        if ($object === false) {
            return new ErrorResponse('Impossible to find object '.$slug, 404);
        }

        list($name, $description, $fields) = $this->getDataFromForm();

        try {
            $this->db->update($this->table, array(
                'name' => $name,
                'description' => $description,
            ), array('id' => $object['id']));

            // Save fields
            $this->db->delete('account_type_has_field', array('account_type_id' => $object['id']));
            $position = 0;
            foreach ($fields as $field) {
                $this->db->insert('account_type_has_field', array(
                    'account_type_id' => $object['id'],
                    'field_id' => $field,
                    'position' => $position++,
                ));
            }
            
            return new JsonResponse(array('message' => 'The account type is save'), 200);
        } catch (\Exception $e) {
            return new ErrorResponse('Error while save the account type');
        }
    }


    /**
     * Extract all parameters from the HTTP request
     *
     * @return  array<name, description, fields>     The datas of the request
     */
    private function getDataFromForm()
    {
        $name = $this->request->get('name', '');
        $description = $this->request->get('description', '');
        $fields = $this->request->get('fields', '[]');

        if (is_string($name) === false || empty($name) === true) {
            throw new \Exception('Name can\'t be empty', 400);
        }

        if (is_string($description) === false) {
            throw new \Exception('Description must be a string', 400);
        }

        if (is_string($fields) === false || ($fields = json_decode($fields)) === false || is_array($fields) === false) {
            throw new \Exception('Fields must be a JSON array', 400);
        }

        return array(trim((string)$name), trim((string)$description), $fields);
    }
}