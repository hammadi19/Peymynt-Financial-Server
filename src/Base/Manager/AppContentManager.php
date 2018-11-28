<?php

namespace App\Base\Manager;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Base\Utils\TokenUtil;
use App\Base\Utils\ArrayUtil;
use App\Entity\ContactUs;


class AppContentManager
{
    /**
     * @var $container
     */
    protected $container;

    /**
     * $em
     */
    private $em;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container){
        $this->container = $container;
        $this->em = $this->container->get('doctrine.orm.entity_manager');
    }

    /**
     * @param $request
     */
    public function storeContactUs($request){
        $contactEntity = new ContactUs();
        $contactEntity->setFullName($request->get('name'));
        $contactEntity->setEmail($request->get('email'));
        $contactEntity->setContactNo($request->get('contact_no'));
        if($request->has('message')){
            $contactEntity->setMessage($request->get('message'));
        }
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        if($request->has('user_agent')){
            $userAgent = $request->get('user_agent');
        }
        $ipAddress = $_SERVER['REMOTE_ADDR'];
        if($request->has('ip_address')){
            $ipAddress = $request->get('ip_address');
        }
        $contactEntity->setUserAgent($userAgent);
        $contactEntity->setIp($ipAddress);
        $contactEntity->setCreatedAt(new \DateTime());
        $this->em->persist($contactEntity);
        $this->em->flush();


        // Email Triggers
        $this->sendContactUsEmailToSupport($request,$userAgent,$ipAddress);
        $this->sendContactUsAutoResponderEmail($request);
    }

    /**
     * Trigger Email on contact us to support team
     *
     * @param $request
     * @param $userAgent
     * @param $ipAddress
     */
    private function sendContactUsEmailToSupport($request,$userAgent,$ipAddress){
        // trigger email
        $emailParams = array(
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'contact_no' => $request->get('contact_no'),
            'message' =>  ($request->has('message')) ? $request->get('message') : '',
            'ip' => $ipAddress,
            'agent' => $userAgent
        );

        $emailParameters = array(
            'action_key' => 'contact_us',
            'email_schema' => array(
                'subject' => 'Task-Bee Contact Us Information',
                'to' => array('1unit.team@gmail.com')
            ),
            'email_data' => $emailParams
        );
        $transmitter  = $this->container->get('app_email_transmitter');
        $transmitter->trigger($emailParameters);
    }


    /**
     * Send Email trigger to user as auto responder
     *
     * @param $request
     */
    private function sendContactUsAutoResponderEmail($request){

        $emailParams = array(
            'full_name' => $request->get('name')
        );

        $emailParameters = array(
            'action_key' => 'contact_us_responder',
            'email_schema' => array(
                'subject' => 'Task-Bee Enquiry Submission',
                'to' => array($request->get('email'))
            ),
            'email_data' => $emailParams
        );
        $transmitter  = $this->container->get('app_email_transmitter');
        $transmitter->trigger($emailParameters);
    }



}//@