<?php

namespace App\Base\Utils;


/**
 * Class StringUtil
 * @package ATMedics\SystemBundle\Base\Utils
 */
class StringUtil {


    /**
     * Cut full string words
     *
     * @param $text
     * @param $maxchar
     * @param string $end
     * @return string
     */
    public static function subStringWords($text, $maxchar, $end='...') {
        if (strlen($text) > $maxchar || $text == '') {
            $words = preg_split('/\s/', $text);
            $output = '';
            $i      = 0;
            while (1) {
                $length = strlen($output)+strlen($words[$i]);
                if ($length > $maxchar) {
                    break;
                }
                else {
                    $output .= " " . $words[$i];
                    ++$i;
                }
            }
            $output .= $end;
        }
        else {
            $output = $text;
        }
        return $output;
    }

    /**
     * 0 not equal to ''
     *
     * @param $var
     * @return bool
     */
    public static function isEmpty($var) {
        return ($var===0||$var);
    }

    /**
     * Convert from label to key
     *
     * @param $targetString
     * @return mixed
     */
    public static function labelToKey($targetString){
        $prepositionsPatterns = array('/the/','/he/','/to/','/in/','/on/','/by/','/with/','/a/');
        $output = preg_replace("/(\s){2,}/",'$1',trim(preg_replace($prepositionsPatterns, '', strtolower($targetString))));
        return str_replace(" ", "_",$output);
    }



}//@