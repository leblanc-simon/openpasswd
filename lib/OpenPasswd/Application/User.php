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