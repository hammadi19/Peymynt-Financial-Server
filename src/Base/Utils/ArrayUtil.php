<?php

namespace App\Base\Utils;
use Symfony\Component\HttpFoundation\Response;


class ArrayUtil {


    /**
     * Transform Row to column [suitable for column based forms]
     *
     * @param $receivedDocArray
     * @param $noRow
     * @param $noColumns
     * @return array
     */
    public static function getRowToColumnTransform($receivedDocArray , $noRow , $noColumns){
        $rowTransformedArray = array();
        $counter = 1;
        for($outer = 0; $outer < $noRow; $outer++){
            $rowTransformedArray[$counter] = array();
            for($inner = 0; $inner < $noColumns; $inner++){
                array_push($rowTransformedArray[$counter],
                   $receivedDocArray[$outer][$inner]
                );
            }
            $counter++;
        }
        return $rowTransformedArray;
    }


    /**
     * Reverse of above Action
     *
     * @param $formArray
     * @return array
     */
    public static function getColumnToRowTransform($formArray){
        $groupTransformedArray = array();
        foreach($formArray as $group => $fieldsArray){
            foreach($fieldsArray as $rawField => $value){
                $fieldArray = explode('_',$rawField);
                $indexKey = $fieldArray[1];
                $indexField = $fieldArray[0];
                if(array_key_exists($indexKey,$groupTransformedArray) && 'array' === gettype($groupTransformedArray[$indexKey])){
                    $groupTransformedArray[$indexKey][$indexField] = $value;
                }else{
                    $groupTransformedArray[$indexKey] = array();
                    $groupTransformedArray[$indexKey][$indexField] = $value;
                }
            }
        }
        return $groupTransformedArray;
    }

    /**
     * @param $formArray
     * @return array
     */
    public static function getSingleColumnToRowTransform($formArray){
        $groupTransformedArray = array();
        foreach($formArray as $rawField => $fieldValue){
            $fieldArray = explode('_',$rawField);
            $indexKey = $fieldArray[1];
            $indexField = $fieldArray[0];
            if(array_key_exists($indexKey,$groupTransformedArray) && 'array' === gettype($groupTransformedArray[$indexKey])){
                $groupTransformedArray[$indexKey][$indexField] = $fieldValue;
            }else{
                $groupTransformedArray[$indexKey] = array();
                $groupTransformedArray[$indexKey][$indexField] = $fieldValue;
            }
        }
        return $groupTransformedArray;
    }


    public static function arrayEqual($a, $b) {
        return (
            is_array($a) && is_array($b) &&
            count($a) == count($b) &&
            array_diff($a, $b) === array_diff($b, $a)
        );
    }


    public static function validate($request , $requiredSchema){
        if($requiredSchema['allow_method'] === $request->getMethod()){

        }
        return array('code' => Response::HTTP_METHOD_NOT_ALLOWED, 'message' => 'Invalid request method');
    }


    public static function isParamExist($requiredArray, $outputArray){
        if(count($outputArray) > 0){
            $outputKeys = array_keys($outputArray);
            $result = array_diff($requiredArray, $outputKeys);
            if(count($result) > 0){
                $missing = array_values($result);
                $params = array_map(function($el) {
                    return '@'.$el;
                }, $missing);
                return "Missing parameters ".join(",",$params);
            }
            return TRUE;
        }
        $params = array_map(function($el) { return '@'.$el; }, $requiredArray);
        return "Missing parameters ".join(",",$params);
    }






}