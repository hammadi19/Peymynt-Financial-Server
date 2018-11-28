<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\AppUser;
use App\Entity\Business;
use App\Entity\BusinessDetail;
use App\Entity\Customer;

class CustomerManager
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }

    public function createCustomer($request,$user)
    {
        $business = $this->entityManager->getRepository(Business::class)->find($request->get('business_id'));

        if("object" === gettype($business)){
            $customer = new Customer();
            $customer->setName($request->get('company_name'));
            $customer->setBusiness($business);
            if($request->has('email')) {
                $customer->setEmail($request->get('email'));
            }
            if($request->has('first_name')) {
                $customer->setFirstName($request->get('first_name'));
            }
            if($request->has('last_name')) {
                $customer->setLastName($request->get('last_name'));
            }
            if($request->has('currency')) {
                $customer->setCurrency($request->get('currency'));
            }
            if($request->has('account_no')) {
                $customer->setAccountNo($request->get('account_no'));
            }
            if($request->has('phone')) {
                $customer->setPhone($request->get('phone'));
            }
            if($request->has('mobile')) {
                $customer->setMobile($request->get('mobile'));
            }
            if($request->has('toll_free')) {
                $customer->setTollFree($request->get('toll_free'));
            }
            if($request->has('website')) {
                $customer->setWebsite($request->get('website'));
            }
            if($request->has('country')) {
                $customer->setCountry($request->get('country'));
            }
            if($request->has('address_1')) {
                $customer->setAddress1($request->get('address_1'));
            }
            if($request->has('address_2')) {
                $customer->setAddress2($request->get('address_2'));
            }
            if($request->has('city')) {
                $customer->setCity($request->get('city'));
            }
            if($request->has('zip_code')) {
                $customer->setZipCode($request->get('zip_code'));
            }

            $this->entityManager->persist($customer);
            $this->entityManager->flush();

            return array(
                'code' => Response::HTTP_OK,
                'message' => sprintf("Customer created successfully"),
                'id' => $customer->getId(),
            );
        }

        return array(
            'code' => Response::HTTP_CONFLICT,
            'message' => sprintf("no business found with this id")
        );
    }

    public function modifyCustomer($request,$business_id,$customer_id)
    {
        $business = $this->entityManager->getRepository(Business::class)->find($business_id);
        if($business){
            $repository = $this->entityManager->getRepository(Customer::class);
            $customer = $repository->findOneBy(array('id'=>$customer_id, 'business' => $business));
            if($customer){
                $customer->setName($request->get('company_name'));
                $customer->setBusiness($business);
                if($request->has('email')) {
                    $customer->setEmail($request->get('email'));
                }
                if($request->has('first_name')) {
                    $customer->setFirstName($request->get('first_name'));
                }
                if($request->has('last_name')) {
                    $customer->setLastName($request->get('last_name'));
                }
                if($request->has('currency')) {
                    $customer->setCurrency($request->get('currency'));
                }
                if($request->has('account_no')) {
                    $customer->setAccountNo($request->get('account_no'));
                }
                if($request->has('phone')) {
                    $customer->setPhone($request->get('phone'));
                }
                if($request->has('mobile')) {
                    $customer->setMobile($request->get('mobile'));
                }
                if($request->has('toll_free')) {
                    $customer->setTollFree($request->get('toll_free'));
                }
                if($request->has('website')) {
                    $customer->setWebsite($request->get('website'));
                }
                if($request->has('country')) {
                    $customer->setCountry($request->get('country'));
                }
                if($request->has('address_1')) {
                    $customer->setAddress1($request->get('address_1'));
                }
                if($request->has('address_2')) {
                    $customer->setAddress2($request->get('address_2'));
                }
                if($request->has('city')) {
                    $customer->setCity($request->get('city'));
                }
                if($request->has('zip_code')) {
                    $customer->setZipCode($request->get('zip_code'));
                }

                $this->entityManager->persist($customer);
                $this->entityManager->flush();

                return array(
                    'code' => Response::HTTP_OK,
                    'message' => sprintf("Selected customer updated successfully")
                );
            }

            return array(
                'code' => Response::HTTP_CONFLICT,
                'message' => sprintf("no customer found with this id")
            );
        }


        return array(
            'code' => Response::HTTP_CONFLICT,
            'message' => sprintf("no customer found with this id")
        );
    }

    public function listCustomers($business_id,$user)
    {
        $conn = $this->entityManager->getConnection();
        $sql = sprintf('SELECT id,name,email,phone, currency FROM customer WHERE business_id = %d',$business_id);
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function viewCustomer($business_id,$customer_id)
    {
        $conn = $this->entityManager->getConnection();
        $sql = sprintf('SELECT * FROM customer WHERE (id=%d AND business_id = %d)',$customer_id,$business_id);
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

}//@




























































































































































































































































































































