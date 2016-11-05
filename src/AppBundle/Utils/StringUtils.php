<?php

namespace AppBundle\Utils;

/**
 * @author Konstantin Grachev <me@grachevko.ru>
 */
final class StringUtils
{
    /**
     * @param        $string
     * @param string $delimiter
     *
     * @return string
     */
    public static function splitCamelByDelimiter($string, $delimiter = '_')
    {
        return strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1'.$delimiter, $string));
    }
}
