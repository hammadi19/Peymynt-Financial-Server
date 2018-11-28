<?php

namespace App\Base\Dictionary;
use App\Base\Dictionary\AbstractEmailDictionary;

/**
 * Generic email dictionary
 *
 * Class EmailDictionary
 * @package App\Base\EmailDictionary
 */
class EmailDictionary extends AbstractEmailDictionary
{

    /**
     * When a user created
     *
     * @return array
     */
    public function getCreateUserSchema(){
        $productSchema = $this->getSchema();
        return array(
            'email_template' => 'create-user-email-tpl',
            'from' => $this->appFrom,
            'to' => $productSchema['email_schema']['to'],
            'subject' => $productSchema['email_schema']['subject'],
            'cc' => array(),
            'bcc' => array(),
            'email_params' => array_merge(
                array(
                    'base_path' => $this->getBaseUrl()
                ),
                $productSchema['email_data']
            ),
        );
    }

    /**
     * Recover User Password
     *
     * @return array
     */
    public function getRecoverUserPasswordSchema(){
        return array(
            'email_template' => 'recover-user-password-email-tpl',
            'from' => $this->appFrom,
            'to' => $this->emailSchema['email_schema']['to'],
            'subject' => $this->emailSchema['email_schema']['subject'],
            'cc' => array(),
            'bcc' => array(),
            'email_params' => array_merge(
                array(
                    'base_path' => $this->getBaseUrl()
                ),
                $this->emailSchema['email_data']
            ),
        );
    }

    /**
     *  Contact Us
     *
     * @return array
     */
    public function getContactUsSchema(){
        return array(
            'email_template' => 'contact-us-email-tpl',
            'from' => $this->appFrom,
            'to' => $this->emailSchema['email_schema']['to'],
            'subject' => $this->emailSchema['email_schema']['subject'],
            'cc' => array(),
            'bcc' => array(),
            'email_params' => array_merge(
                array(
                    'base_path' => $this->getBaseUrl()
                ),
                $this->emailSchema['email_data']
            ),
        );
    }

    /**
     * Contact Us Responder
     *
     * @return array
     */
    public function getContactUsResponderSchema(){
        return array(
            'email_template' => 'contact-us-responder-email-tpl',
            'from' => $this->appFrom,
            'to' => $this->emailSchema['email_schema']['to'],
            'subject' => $this->emailSchema['email_schema']['subject'],
            'cc' => array(),
            'bcc' => array(),
            'email_params' => array_merge(
                array(
                    'base_path' => $this->getBaseUrl()
                ),
                $this->emailSchema['email_data']
            ),
        );
    }


}//@