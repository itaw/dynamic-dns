<?php

namespace itaw\Util;

class PasswordEncoder
{
    /**
     * Encodes a password with a salt
     *
     * @param string $password
     * @param string $salt
     * @return string
     */
    public static function encode($password, $salt)
    {
        return hash('sha512', $password . $salt);
    }
}