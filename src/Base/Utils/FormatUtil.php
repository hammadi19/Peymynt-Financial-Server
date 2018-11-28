<?php

namespace App\Base\Utils;

/**
 * Format utils, help users to formatting string
 *
 * Class FormatUtil
 * @package ATMedics\SystemBundle\Base\Utils
 */
class FormatUtil
{

    /**
     * Convert Camel case to underscore
     *
     * @param $input
     * @return string
     */
    public static function camelCaseToUnderscore($input){
        //strtolower(preg_replace('%([a-z])([A-Z])%', '\1-\2', $input));
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
    }

    /**
     * @param $input
     * @param string $separator
     * @return mixed
     */
    public static function inToCamelCase($input, $separator = '_'){
        return str_replace($separator, '', ucwords($input, $separator));
    }

}//@