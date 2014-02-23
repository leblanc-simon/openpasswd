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

class Account extends AbstractApp implements IApplication
{
    public function __construct(\Silex\Application $app)
    {
        parent::__construct($app);

        $this->table            = 'account';
        $this->fields           = 'id, slug, name, description, account_type_id';
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


    public function showAction($slug)
    {
        $object = $this->retrieveBySlug($slug);

        if ($object === false) {
            return new ErrorResponse('Impossible to find object '.$slug, 404);
        }

        $groups = array(6, 7);
        $sql = 'SELECT DISTINCT name, description, crypt, type, value
                FROM '.$this->db->quoteIdentifier('account_view').'
                WHERE slug = ? AND group_id IN ('.implode(', ', array_fill(0, count($groups), '?')).')';

        $fields = $this->db->fetchAll($sql, array_merge(array($slug), $groups));

        for ($i = 0, $max = count($fields); $i < $max; $i++) {
            if ($fields[$i]['crypt'] === '1') {
                $fields[$i]['value'] = $fields[$i]['value'];
            }
        }

        return new JsonResponse(array('account' => $object, 'fields' => $fields), 200);
    }


    private function insert()
    {
        $name = $this->request->get('name');
        $description = $this->request->get('description');
        $account_type_slug = $this->request->get('account-type');

        $fields = $this->request->get('field');

        $slug = $this->getSlug($name);

        $account_type = new AccountType($this->app);
        $account_type = $account_type->retrieveBySlug($account_type_slug);
        if (false === $account_type) {
            throw new \Exception('Impossible to retrieve the account type');
        }

        try {
            $this->db->insert($this->table, array(
                'slug' => $slug,
                'name' => $name,
                'description' => $description,
                'account_type_id' => $account_type['id'],
            ));

            $object = $this->retrieveBySlug($slug);

            // Save fields
            foreach ($fields as $field_id => $field_value) {
                $this->db->insert('account_has_field', array(
                    'account_id' => $object['id'],
                    'field_id' => $field_id,
                    'value' => $field_value,
                ));
            }

            return new JsonResponse(array('message' => 'The account type is save', 'object' => $object), 201);
        } catch (\Exception $e) {
            return new ErrorResponse('Error while save the account : '.$e->getMessage());
        }
    }


    private function update($slug)
    {
        $object = $this->retrieveBySlug($slug);

        if ($object === false) {
            return new ErrorResponse('Impossible to find object '.$slug, 404);
        }

        $name = $this->request->get('name');
        $description = $this->request->get('description');

        $fields = $this->request->get('field');

        try {
            $this->db->update($this->table, array(
                'name' => $name,
                'description' => $description,
            ), array('id' => $object['id']));

            // Delete old field
            $this->db->delete('account_has_field', array('account_id' => $object['id']));

            // Save fields
            foreach ($fields as $field_id => $field_value) {
                $this->db->insert('account_has_field', array(
                    'account_id' => $object['id'],
                    'field_id' => $field_id,
                    'value' => $field_value,
                ));
            }

            return new JsonResponse(array('message' => 'The account type is save', 'object' => $object), 201);
        } catch (\Exception $e) {
            return new ErrorResponse('Error while save the account');
        }
    }
}