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

class Collection
{
    /**
     * @var array
     */
    private $form_types;

    public function __construct()
    {
        $this->form_types = array();
    }


    public function add(IFormType $form_type)
    {
        $this->form_types[$form_type->getName()] = $form_type;
    }


    public function render()
    {
        $template_ids = array();
        $render = '';
        foreach ($this->form_types as $form_type) {
            if (in_array($form_type->getTemplateId(), $template_ids) === true) {
                continue;
            }

            $render .= $form_type->renderTemplate();
            $template_ids[] = $form_type->getTemplateId();
        }

        return $render;
    }


    /**
     * @return Collection[]
     */
    public function getAll()
    {
        return $this->form_types;
    }


    /**
     * @return array
     */
    public function getAllNames()
    {
        $names = array();

        foreach ($this->form_types as $form_type) {
            $names[] = $form_type->getName();
        }

        return $names;
    }


    /**
     * @param $name
     * @return IFormType
     */
    public function get($name)
    {
        if (isset($this->form_types[$name]) === false) {
            return null;
        }

        return $this->form_types[$name];
    }


    public function getTemplateIds()
    {
        $template_ids = array();

        foreach ($this->form_types as $form_type) {
            $template_ids[$form_type->getName()] = $form_type->getTemplateId();
        }

        return $template_ids;
    }


    public function getAvailableValues()
    {
        $values = array();

        foreach ($this->form_types as $form_type) {
            $values[$form_type->getName()] = $form_type->getAvailableValues();
        }

        return $values;
    }
}