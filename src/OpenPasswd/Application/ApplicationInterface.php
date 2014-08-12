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

interface ApplicationInterface
{
    /**
     * List all items in the model
     */
    public function listAction();

    /**
     * Get an iten in the model by slug
     */
    public function getAction($slug);

    /**
     * Search items in the model
     */
    public function searchAction($search);

    /**
     * Save the data in the model
     */
    public function saveAction($slug);

    /**
     * Remove the data in the model
     */
    public function deleteAction($slug);
}