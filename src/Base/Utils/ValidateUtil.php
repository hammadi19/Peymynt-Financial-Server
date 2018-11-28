<?php

namespace App\Base\Utils;

use Symfony\Component\HttpFoundation\Response;
use Respect\Validation\Validator as v;


/**
 * Create validator
 *
 * Class ValidateUtil
 * @package ATTech\SystemBundle\Base\Utils
 */
class ValidateUtil {


    /**
     * Validate REST form fields
     *
     * @param $request
     * @param $requiredSchema
     * @return array|bool
     */
    public static function restValidate($request,$requiredSchema){
        if($requiredSchema['allow_method'] === $request->getMethod()){

            if(array_key_exists('content_type',$requiredSchema)){
                // json,txt,form
                if($requiredSchema['content_type'] != $request->getContentType()){
                    return array('code' => Response::HTTP_METHOD_NOT_ALLOWED,
                        'message' => sprintf('Invalid request content type, @%s format required',$requiredSchema['content_type'])
                    );
                }
            }

            // run test on 'fields' & 'optional_fields'
            if(array_key_exists('fields',$requiredSchema)){
                $isParamFound = ArrayUtil::isParamExist(array_keys($requiredSchema['fields']),$request->request->all());
                if("string" === gettype($isParamFound)){
                    return array( 'code' => Response::HTTP_NOT_ACCEPTABLE,  'message' => $isParamFound );
                }else{
                    // validate fields now
                    $errors = array();
                    foreach($requiredSchema['fields'] as $fieldName => $schema){
                        $validArray = ValidateUtil::validateSchema($fieldName, $schema, $request->request->get($fieldName));
                        if(count($validArray) > 0){
                            $errors[$fieldName] = $validArray;
                            //array_push($errors,$validArray);
                        }
                    }
                    if(array_key_exists('optional_fields',$requiredSchema)){
                        foreach($requiredSchema['optional_fields'] as $fieldName => $schema){
                            if($request->request->has($fieldName)){
                                $validArray = ValidateUtil::validateSchema($fieldName, $schema, $request->request->get($fieldName));
                                if(count($validArray) > 0){
                                    $errors[$fieldName] = $validArray;
                                }
                            }
                        }
                    }
                    if(count($errors) > 0){
                        return array( 'code' => Response::HTTP_NOT_ACCEPTABLE,  'message' => array(
                            'errors' => $errors
                        ) );
                    }
                    return TRUE;
                }
            }


            // just check 'allow_method', set default behavior
            return TRUE;
        }
        return array('code' => Response::HTTP_METHOD_NOT_ALLOWED, 'message' => 'Invalid request method');
    }


    /**
     * Validate a field
     *
     * @param $fieldName
     * @param $schema
     * @param $fieldValue
     * @return array
     */
    public static function validateSchema($fieldName, $schema, $fieldValue){
        $errors = array();
        if(in_array('required',$schema) && v::notBlank()->validate($fieldValue) == false){
            array_push(
                $errors,
                sprintf('Field required',$fieldName)
            );
        }
        if(in_array('email',$schema) && v::email()->validate($fieldValue) == false){
            array_push(
                $errors,
                sprintf('Invalid email address',$fieldName)
            );
        }
        if(in_array('json',$schema) && v::json()->validate($fieldValue) == false){
            array_push(
                $errors,
                sprintf('Invalid field format, json format required',$fieldName)
            );
        }
        if(in_array('number',$schema) && v::numeric()->validate($fieldValue) == false){
            array_push(
                $errors,
                sprintf('Invalid field format, number format required',$fieldName)
            );
        }
        if(in_array('bool',$schema) && v::boolVal()->validate($fieldValue) == false){
            array_push(
                $errors,
                sprintf('Invalid field format, boolean format required',$fieldName)
            );
        }
        if(in_array('date',$schema) && v::date()->validate($fieldValue) == false){
            array_push(
                $errors,
                sprintf('Invalid date format, proper date format required',$fieldName)
            );
        }
        return $errors;
    }




}