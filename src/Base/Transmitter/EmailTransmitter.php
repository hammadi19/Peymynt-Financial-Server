<?php

namespace App\Base\Transmitter;

use App\Base\Dictionary\EmailDictionary;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Email transmitter
 *
 * Class EmailTransmitter
 * @package App\Base\Transmitter
 */
class EmailTransmitter
{

    /**
     * @var ContainerInterface $container
     */
    protected $container;

    /**
     * @var $nowDate
     */
    protected $nowDate;

    /**
     * @var $dictionary
     */
    private $dictionary;

    /**
     * @var $emailHandler
     */
    private $emailHandler;

    /**
     * @param ContainerInterface $container
     */
    public function __construct( ContainerInterface $container){
        $this->container = $container;
        $this->nowDate = new \DateTime('now');
    }


    public function trigger($schema)
    {
        // EMAIL DISABLED
        $this->emailHandler = $this->container->get('app_mail_handler');
        $this->dictionary = new EmailDictionary();
        $this->dictionary->setSchema($schema);
        switch ($schema['action_key']) {
            case 'create_user':
                $this->emailHandler->setDictionary($this->dictionary->getCreateUserSchema());
                break;
            case 'recover_user_password':
                $this->emailHandler->setDictionary($this->dictionary->getRecoverUserPasswordSchema());
                break;
            case 'contact_us':
                echo "here";
                $this->emailHandler->setDictionary($this->dictionary->getContactUsSchema());
                break;
            case 'contact_us_responder':
                echo "there";
                $this->emailHandler->setDictionary($this->dictionary->getContactUsResponderSchema());
                break;
        }

        $this->emailHandler->sendEmail();
        $this->emailHandler->reset();
    }


}//@