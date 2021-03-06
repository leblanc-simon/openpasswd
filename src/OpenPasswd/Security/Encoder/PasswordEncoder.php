<?php
/**
 * This file is part of the OpenPasswd package.
 *
 * (c) Simon Leblanc <contact@leblanc-simon.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenPasswd\Security\Encoder;

use OpenPasswd\Security\Security;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class PasswordEncoder implements PasswordEncoderInterface
{
    /**
     * Encodes the raw password.
     *
     * @param string $raw The password to encode
     * @param string $salt The salt
     *
     * @return string The encoded password
     */
    public function encodePassword($raw, $salt)
    {
        return Security::hash($raw);
    }

    /**
     * Checks a raw password against an encoded password.
     *
     * @param string $encoded An encoded password
     * @param string $raw A raw password
     * @param string $salt The salt
     *
     * @return Boolean true if the password is valid, false otherwise
     */
    public function isPasswordValid($encoded, $raw, $salt)
    {
        return Security::verify($raw, $encoded);
    }

}