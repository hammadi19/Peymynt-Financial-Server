<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\AppUser;
use App\Entity\Business;
use App\Entity\BusinessDetail;

class BusinessManager
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }

    public function createBusiness($request,$user)
    {
        $business = new Business();
        // needs to create new user
        $business->setName($request->get('name'));
        $business->setUser($user);
        $business->setBusinessType($request->get('business_type'));
        if($request->has('business_sub_type')){
            $business->setBusinessSubType($request->get('business_sub_type'));
        }
        $business->setCountry($request->get('country'));
        $business->setCurrency($request->get('currency'));
        $business->setOrganizationType($request->get('organization_type'));

        $this->entityManager->persist($business);
        $this->entityManager->flush();

        $businessDetail = new BusinessDetail();
        $businessDetail->setBusiness($business);
        $this->entityManager->persist($businessDetail);
        $this->entityManager->flush();

        return array(
            'code' => Response::HTTP_OK,
            'message' => sprintf("A user business created successfully")
        );
    }

    public function modifyBusiness($request,$business_id)
    {
        $business = $this->entityManager->getRepository(Business::class)->find($business_id);
        //$businessDetail = $this->entityManager->getRepository(BusinessDetail::class)->find($business_id);
        if($business){
            $business->setName($request->get('name'));
            $business->setBusinessType($request->get('business_type'));
            if($request->has('business_sub_type')){
                $business->setBusinessSubType($request->get('business_sub_type'));
            }
            $business->setCountry($request->get('country'));
            $business->setCurrency($request->get('currency'));
            $business->setOrganizationType($request->get('organization_type'));

            $this->entityManager->persist($business);
            $this->entityManager->flush();


//            $businessDetail->setBusiness($business);
//            $this->entityManager->persist($businessDetail);
//            $this->entityManager->flush();


                return array(
                    'code' => Response::HTTP_OK,
                    'message' => sprintf("Selected business updated successfully")
                );
        }

        return array(
            'code' => Response::HTTP_CONFLICT,
            'message' => sprintf("no business found with this id")
        );
    }

    public function listBusiness($request,$user)
    {
        $conn = $this->entityManager->getConnection();
        $sql = sprintf('SELECT * FROM business WHERE user_id = %d',$user->getId());
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function makeBusinessPrimary($request,$user){
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
    }


    public function loadUserBusiness($business_id,$user){
            $businessEntity = $this->entityManager->getRepository(Business::class)->findOneBy(
            ['id'=>$business_id,'user'=>$user]);
            return $businessEntity;
    }

    public function viewBusiness($business_id)
    {
        $conn = $this->entityManager->getConnection();
        $sql = sprintf('SELECT b.*, bd.address_line1, bd.address_line2, bd.city, bd.province, bd.zip_code, bd.time_zone, bd.phone, bd.mobile, bd.toll_free, bd.website FROM business b LEFT JOIN business_detail bd ON bd.business_id=b.id WHERE b.id = %d',$business_id);
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

}//@




























































































































































































































































































































