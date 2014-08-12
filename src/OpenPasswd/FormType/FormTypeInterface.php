<?php
/**
 * This file is part of the OpenPasswd package.
 *
 * (c) Simon Leblanc <contact@leblanc-simon.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenPasswd\FormType;

interface FormTypeInterface
{
    /**
     * @return string   The name of the form type (use in database for field)
     */
    public function getName();

    /**
     * @return string   The id attribute of the jquery template associated with this form type
     */
    public function getTemplateId();

    /**
     * @return string   The content of the template
     */
    public function renderTemplate();

    /**
     * @return null|array   The list of available value
     */
    public function getAvailableValues();

    /**
     * @param   string  $value  The value get from a form
     * @return  string          The value converted for the database
     */
    public function transformValue($value);
}