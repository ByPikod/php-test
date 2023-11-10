<?php

namespace PHPTest;

/**
 * Utilities class
 */
class Utilities
{
    /**
     * Add indent to a string
     * @param int $amount Amount of indent
     * @return string String with indent
     * @since 1.0.0
     */
    public static function indent(int $amount = 1, string $stringToIndent = null): string
    {
        if ($stringToIndent !== null) {
            return preg_replace('/^/m', self::indent($amount), $stringToIndent);
        }
        return str_repeat('    ', $amount);
    }

    /**
     * Add bullet to a string
     * @param string $str String to add bullet
     * @return string String with bullet
     * @since 1.0.0
     */
    public static function bullet($str): string
    {
        return "\u{2022} " . $str;
    }
}
