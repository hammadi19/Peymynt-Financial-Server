<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Entity\AppUser as BaseUser;
use Doctrine\ORM\EntityManager;



class JWTCreatedListener {

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @param RequestStack $requestStack
     * @param EntityManager $em
     */
    public function __construct(RequestStack $requestStack , EntityManager $em)
    {
        $this->requestStack = $requestStack;
        $this->em   = $em;
    }

    /**
     * @param JWTCreatedEvent $event
     *
     * @return void
     */
    public function onJWTCreated(JWTCreatedEvent $event)
    {
        $request = $this->requestStack->getCurrentRequest();

        $payload        = $event->getData();
        $user           = $event->getUser();
        $payload['uid'] = $user->getId();
        $payload['first_name'] = $user->getFirstName();
        $payload['last_name'] = $user->getLastName();
        $payload['email'] = $user->getEmail();
        $payload['profile_image'] = $user->getProfileImage();
        $output = $this->getPrimaryBusiness($user);
        if(array_key_exists("id",$output)){
            $payload['business_id'] = $output['id'];
            $payload['business_currency'] = $output['currency'];
            $payload['is_personal'] = ($output['is_personal'] != null) ? TRUE : 0;
        }

        $user->setLastLoginDate(new \DateTime('now'));
        $this->em->persist($user);
        $this->em->flush();

        $event->setData($payload);
    }

    public function getPrimaryBusiness($user){
        $conn = $this->em->getConnection();
        $sql = sprintf('SELECT id,is_personal,currency FROM business WHERE is_primary = TRUE AND user_id = %d',$user->getId());
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $response = $stmt->fetch();
        if("array" === gettype($response)){
            return $response;
        }else{
        $sql = sprintf('SELECT id,is_personal,currency FROM business WHERE user_id = %d',$user->getId());
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $response = $stmt->fetchAll();
            if("array" === gettype($response)){
                return $response[0];
            }
        }
        return array();
    }


}