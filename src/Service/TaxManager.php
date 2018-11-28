<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\AppUser;
use App\Entity\Business;
use App\Entity\BusinessDetail;
use App\Entity\Tax;


class TaxManager
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }

    public function createTax($request,$user)
    {
        $business = $this->entityManager->getRepository(Business::class)->find($request->get('business_id'));

        if("object" === gettype($business)){
            $tax = new Tax();
            $tax->setName($request->get('name'));
            $tax->setBusiness($business);
            $tax->setAbbreviation($request->get('name'));
            $tax->setTaxRate($request->get('tax_rate'));
            if($request->has('tax_number')){
                $tax->setTaxNumber($request->get('tax_number'));
            }
            if($request->has('description')){
                $tax->setDescription($request->get('description'));
            }
            if($request->has('is_tax_recoverable')) {
                $tax->setIsTaxRecoverable($request->get('is_tax_recoverable'));
            }
            if($request->has('is_compound_tax')) {
                $tax->setIsCompoundTax($request->get('is_compound_tax'));
            }
            if($request->has('is_tax_no_show')) {
                $tax->setIsTaxNoShow($request->get('is_tax_no_show'));
            }
            $this->entityManager->persist($tax);
            $this->entityManager->flush();

            return array(
                'code' => Response::HTTP_OK,
                'message' => sprintf("Tax created successfully"),
                'id' => $tax->getId(),
            );
        }

        return array(
            'code' => Response::HTTP_CONFLICT,
            'message' => sprintf("no business found with this id")
        );

    }


    public function listTaxes(Business $business, AppUser $user){
        // check that user is allowed to see that business
        if($business->getUser() !== $user){
            return [];
        }

        $conn = $this->entityManager->getConnection();
        $sql = sprintf('SELECT * FROM tax WHERE business_id = %d',$business->getId());
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}//@




























































































































































































































































































































