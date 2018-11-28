<?php

namespace App\Base\Dictionary;


/**
 * Abstract email dictionary
 *
 * Class AbstractEmailDictionary
 * @package App\Base\EmailDictionary
 */
class AbstractEmailDictionary {

    /**
     * @var string $env
     */
    protected $env = 'dev'; // dev, live

    /**
     * @var array $appFrom
     */
    protected $appFrom = 'info@taskbee.ca';

    /**
     * @var array $baseUrls
     */
    protected $baseUrls = array(
        'dev' => '',
        'live' => ''
    );

    /**
     * @var array $ccArray
     */
    protected $ccArray = array(
        'dev' => array(),
        'live' => array()
    );

    /**
     * @var array $bccArray
     */
    protected $bccArray = array(
        'dev' => array(),
        'live' => array()
    );

    /**
     * @var $emailSchema;
     */
    protected $emailSchema = array();

    /**
     * Set defaults
     *
     * @param $env
     */
    public function __construct($env='dev'){
        $this->env = $env;
    }

    /**
     * @return mixed
     */
    public function getBaseUrl(){
        return $this->baseUrls[$this->env];
    }

    /**
     * @return mixed
     */
    public function getMailCC(){
        return $this->ccArray[$this->env];
    }

    /**
     * @return mixed
     */
    public function getMailBCC(){
        return $this->bccArray[$this->env];
    }

    /**
     * @param $schemaArray
     * @return mixed
     */
    public function setSchema($schemaArray){
        $this->emailSchema = $schemaArray;
    }

    /**
     * @return array
     */
    public function getSchema(){
        return $this->emailSchema;
    }



}