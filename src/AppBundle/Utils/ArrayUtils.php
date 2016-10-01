<?php

namespace AppBundle\Utils;

/**
 * @author Konstantin Grachev <me@grachevko.ru>
 */
final class ArrayUtils
{
    /**
     * @param array $arr
     *
     * @return bool
     */
    public static function isAssoc(array $arr): bool
    {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}
