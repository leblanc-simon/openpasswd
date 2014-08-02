<?php
/**
 * This file is part of the OpenPasswd package.
 *
 * (c) Simon Leblanc <contact@leblanc-simon.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenPasswd\Core;

class Utils
{
    static public function toSlug($string, $delimiter = '-')
    {
        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
        $clean = preg_replace("#[^a-zA-Z0-9/_|+ -]#", '', $clean);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("#[/_|+ -]+#", $delimiter, $clean);

        return $clean;
    }
}