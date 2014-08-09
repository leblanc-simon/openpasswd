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

class TextType
    extends ASimpleType
    implements FormTypeInterface
{
    /**
     * @return string   The name of the form type (use in database for field)
     */
    public function getName()
    {
        return 'text';
    }
}