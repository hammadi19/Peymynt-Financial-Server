<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\AppUser;
use App\Entity\UserEmailAccount;

class EmailAccountManager
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }


    public function createUserEmailAccount($request,$user)
    {
        $account = new UserEmailAccount();
        // needs to create new user
        $account->setEmail($request->get('email'));
        $account->setUser($user);
        $account->setIsPrimary(FALSE);
        $account->setIsConfirmed(FALSE);
        $account->setAccountHash("ABBC");

        $this->entityManager->persist($account);
        $this->entityManager->flush();

        // trigger an email

        return array(
            'code' => Response::HTTP_OK,
            'message' => sprintf("A user email account created successfully")
        );
    }



    public function loadUserEmailAccounts($user)
    {
        $conn = $this->entityManager->getConnection();
        $sql = sprintf('SELECT email,is_confirmed,is_primary FROM user_email_account WHERE user_id = %d',$user->getId());
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $outputArray = $stmt->fetchAll();
        array_push($outputArray,array(
            'email' => $user->getEmail(),
            'is_confirmed' => TRUE,
            'is_primary' => TRUE
        ));
        $outputArray = array_reverse($outputArray);


        return $outputArray;
    }


    public function makePrimaryEmailAccount($request,$user){

        $email  = $this->entityManager->getRepository(UserEmailAccount::class)->findOneBy(['email' =>$request->get('email'),'user' => $user]);
        if("object" == gettype($email)){
            $primaryEmail = $user->getEmail();
            $user->setEmail($email->getEmail());
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $email->setEmail($primaryEmail);
            $this->entityManager->persist($email);
            $this->entityManager->flush();

            return true;
        }


        /*
        $repository = $this->entityManager->getRepository(Business::class);
        $currentPrimary = $repository->findOneBy(
            ['is_primary' => true , 'user' => $user]
        );
        if("object" === gettype($currentPrimary)){

            $currentPrimary->setIsPrimary(FALSE);
            $this->entityManager->persist($currentPrimary);
        }

        $businessEntity = $this->entityManager->getRepository(Business::class)->find($request->get('business_id'));
        if("object" === gettype($businessEntity)){
            $businessEntity->setIsPrimary(TRUE);
            $this->entityManager->persist($businessEntity);
        }
        $this->entityManager->flush();
        */
    }


    public function loadUserBusiness($business_id,$user){

    }


    public function removeEmailAccount($request,$user){
        $email  = $this->entityManager->getRepository(UserEmailAccount::class)->findOneBy(['email' =>$request->get('email'),'user' => $user]);
        if("object" == gettype($email)){
            $this->entityManager->remove($email);
            $this->entityManager->flush();
            return array(
                'code' => Response::HTTP_OK,
                'message' => sprintf("Selected user email account removed successfully")
            );
        }
        return array(
            'code' => Response::HTTP_FORBIDDEN,
            'message' => sprintf("No email address found")
        );
    }




}//@




























































































































































































































































































































