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

class Account extends AbstractApp implements ApplicationInterface
{
    const GROUP_ADMIN = 1;

    public function __construct(\Silex\Application $app)
    {
        parent::__construct($app);

        $this->table            = 'account';
        $this->fields           = 'id, slug, name, description, account_type_id';
        $this->order            = 'name ASC';

        $groups = $this->security->getEnableGroups();
        $this->criteria         = ' INNER JOIN
                                        account_has_group
                                    ON account.id = account_has_group.account_id
                                    WHERE account_has_group.group_id IN ('.implode(', ', array_fill(0, count($groups), '?')).')';
        $this->criteria_values  = $groups;
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

        if ($this->security->isAllowedToShowAccount($object['id']) === false) {
            return new ErrorResponse(null, 403);
        }

        $object['description'] = nl2br($object['description']);

        $groups = $this->security->getEnableGroups();

        $sql = 'SELECT DISTINCT name, description, crypt, type, value
                FROM '.$this->db->quoteIdentifier('account_view').'
                WHERE slug = ? AND group_id IN ('.implode(', ', array_fill(0, count($groups), '?')).')';

        $fields = $this->db->fetchAll($sql, array_merge(array($slug), $groups));

        for ($i = 0, $max = count($fields); $i < $max; $i++) {
            if ($fields[$i]['crypt'] === '1') {
                $fields[$i]['value'] = $this->security->decrypt($fields[$i]['value']);
            }
            if ($fields[$i]['type'] === 'textarea') {
                $fields[$i]['value'] = nl2br($fields[$i]['value']);
            }
        }

        return new JsonResponse(array('account' => $object, 'fields' => $fields), 200);
    }


    private function insert()
    {
        $name = $this->request->get('name');
        $description = $this->request->get('description');
        $account_type_slug = $this->request->get('account-type');

        $slug = $this->getSlug($name);

        $account_type = new AccountType($this->app);
        $account_type = $account_type->retrieveBySlug($account_type_slug);
        if (false === $account_type) {
            throw new \Exception('Impossible to retrieve the account type');
        }

        try {
            $this->db->beginTransaction();

            $this->db->insert($this->table, array(
                'slug' => $slug,
                'name' => $name,
                'description' => $description,
                'account_type_id' => $account_type['id'],
            ));

            $last_object_id = $this->db->lastInsertId();

            if (is_numeric($last_object_id) !== true || $last_object_id <= 0) {
                throw new \Exception('Impossible to save account');
            }

            // Save fields
            $this->insertFields($last_object_id);

            // Save groups
            $this->insertGroups($last_object_id);

            $object = $this->retrieveBySlug($slug);

            $this->db->commit();

            return new JsonResponse(array('message' => 'The account type is save', 'object' => $object), 201);
        } catch (\Exception $e) {
            $this->db->rollback();
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

        try {
            $this->db->beginTransaction();

            $this->db->update($this->table, array(
                'name' => $name,
                'description' => $description,
            ), array('id' => $object['id']));

            // Delete old field
            $this->db->delete('account_has_field', array('account_id' => $object['id']));

            // Save fields
            $this->insertFields($object['id']);

            // Delete old groups
            $this->db->delete('account_has_group', array('account_id' => $object['id']));

            // Save groups
            $this->insertGroups($object['id']);

            $this->db->commit();

            return new JsonResponse(array('message' => 'The account type is save', 'object' => $object), 201);
        } catch (\Exception $e) {
            $this->db->rollback();
            return new ErrorResponse('Error while save the account');
        }
    }


    /**
     * @param   int $account_id
     * @throws  \Exception
     * @TODO    parse field with the account and not the user's datas
     */
    private function insertFields($account_id)
    {
        $fields = $this->request->get('field');

        foreach ($fields as $field_id => $field_value) {
            $field = $this->db->executeQuery('SELECT * FROM field WHERE id = ?', array((int)$field_id))->fetch();
            if (false === $field) {
                throw new \Exception('Impossible to find field');
            }

            if (empty($field_value) === true && '1' === $field['required']) {
                throw new \Exception($field['name'].' is required');
            }

            $this->db->insert('account_has_field', array(
                'account_id' => $account_id,
                'field_id' => $field['id'],
                'value' => $field['crypt'] === '1' ? $this->security->encrypt($field_value) : $field_value,
            ));
        }
    }

    /**
     * @param int $account_id
     * @throws \Exception
     */
    private function insertGroups($account_id)
    {
        $groups = $this->request->get('group');
        if (is_array($groups) === false) {
            throw new \Exception('group must be an array');
        }

        // Account must have at least one user group selected
        $user_groups = $this->getSecurity()->getEnableGroups();
        $selected_group = array_keys($groups);
        if (count(array_intersect($user_groups, $selected_group)) === 0) {
            throw new \Exception('You must select at least one of your groups');
        }

        foreach ($groups as $group_id => $group_value) {
            $group = $this->db->executeQuery('
                SELECT *
                FROM '.$this->db->quoteIdentifier('group').'
                WHERE id = ?',
                array((int)$group_id)
            )->fetch();

            if (false === $group) {
                throw new \Exception('Impossible to find group');
            }

            $this->db->insert('account_has_group', array(
                'account_id' => $account_id,
                'group_id' => $group['id'],
            ));
        }

        if (!isset($groups[self::GROUP_ADMIN])) {
            $this->db->insert('account_has_group', array(
                'account_id' => $account_id,
                'group_id' => self::GROUP_ADMIN,
            ));
        }

    }
}