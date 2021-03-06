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

class TextareaType
    extends ASimpleType
    implements FormTypeInterface
{
    /**
     * @return string   The name of the form type (use in database for field)
     */
    public function getName()
    {
        return 'textarea';
    }

    /**
     * @return string   The id attribute of the jquery template associated with this form type
     */
    public function getTemplateId()
    {
        return 'tpl-form-type-textarea';
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
                            <textarea data-template-bind='[{"attribute": "name", "value": "input_id"}, {"attribute": "id", "value": "input_id"}, {"attribute": "required", "value": "required"}]'
                                      class="form-control"></textarea>
                        </div>
                    </script>
EOF;
    }
}