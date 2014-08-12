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

abstract class ASimpleType implements FormTypeInterface
{
    /**
     * @return string   The name of the form type (use in database for field)
     */
    public function getName()
    {
        return 'simple_type';
    }

    /**
     * @return string   The id attribute of the jquery template associated with this form type
     */
    public function getTemplateId()
    {
        return 'tpl-form-type-simple_type';
    }

    /**
     * @return string   The content of the template
     */
    public function renderTemplate()
    {
        $template_id = $this->getTemplateId();
        return <<<EOF

                    <script type="text/html" id="$template_id">
                        <div class="form-group">
                            <label data-template-bind='[{"attribute": "for", "value": "input_id"}]' data-content="name"></label>
                            <input data-template-bind='[{"attribute": "name", "value": "input_id"}, {"attribute": "id", "value": "input_id"}, {"attribute": "type", "value": "type"}, {"attribute": "required", "value": "required"}]' class="form-control" />
                        </div>
                    </script>
EOF;
    }

    /**
     * @return null|array   The list of available value
     */
    public function getAvailableValues()
    {
        return null;
    }

    /**
     * @param   string $value The value get from a form
     * @return  string          The value converted for the database
     */
    public function transformValue($value)
    {
        return $value;
    }
}