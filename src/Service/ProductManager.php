<?php

namespace App\Service;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Product;
use App\Entity\Business;
use App\Entity\AppUser;
class ProductManager
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager    = $entityManager;
    }

    public function createProduct($request,$business, AppUser $user)
    {
        $product = new Product();
       
        $name = $request->get('name');
        $description = $request->get('description');
        $price = $request->get('price');
        $incomeAccount = $request->get('income_account')??'';
        $expenseAccount = $request->get('expense_account')??'';
        $salesTax = $request->get('sales_tax');

        $product->setName($name);
        $product->setDescription($description);
        $product->setPrice($price);
        $product->setIsSell((bool)$request->get('is_sell'));
        $product->setIsBuy((bool)$request->get('is_buy'));
        $product->setBusiness($business);
        $product->setIncomeAccount($incomeAccount);
        $product->setExpenseAccount($expenseAccount);
        $product->setSalesTax($salesTax);
       
        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return array(
            'code' => Response::HTTP_OK,
            'message' => sprintf("A user product created successfully"),
            'id' => $product->getId(),
        );
    }
    
     public function updateProduct($request,$business, AppUser $user,$product_id)
    {
        
        $productEntity = $this->entityManager->getRepository(Product::class)->findOneBy(
            ['id'=>$product_id,'business'=>$business]);
         if("object" === gettype($productEntity)){
            $name = $request->get('name');
            $description = $request->get('description');
            $price = $request->get('price');
            $isSell = $request->get('is_sell');
            $isBuy = $request->get('is_buy');
            $incomeAccount = $request->get('income_account')??'';
            $expenseAccount = $request->get('expense_account')??'';
            $salesTax = $request->get('sales_tax');

            $productEntity->setName($name);
            $productEntity->setDescription($description);
            $productEntity->setPrice($price);
            $productEntity->setIsSell($isSell);
            $productEntity->setIsBuy($isBuy);
            $productEntity->setBusiness($business);
            $productEntity->setIncomeAccount($incomeAccount);
            $productEntity->setExpenseAccount($expenseAccount);
            $productEntity->setSalesTax($salesTax);
           
            $this->entityManager->persist($productEntity);
            $this->entityManager->flush();

            return array(
                'code' => Response::HTTP_OK,
                'message' => sprintf("A user product update successfully")
            );
      }
      return array(
                'code' => Response::HTTP_BAD_REQUEST,
                'message' => sprintf("Product is invalid")
            );
    }

    public function listProduct($business)
    {
        $conn = $this->entityManager->getConnection();
        $sql = sprintf('SELECT * FROM product WHERE business_id = %d',$business->getId());
        
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getProduct($business,$product_id)
    {
        $conn = $this->entityManager->getConnection();
        $sql = sprintf('SELECT * FROM product WHERE business_id = %d and id = %d',$business->getId(),$product_id);
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch();

    }

    public function deleteProduct($business,$product_id)
    {
        if(!empty($this->getProduct($business,$product_id))){
            $conn = $this->entityManager->getConnection();
            $sql = sprintf('DELETE FROM product WHERE business_id = %d and id = %d',$business->getId(),$product_id);
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return array(
                    'code' => Response::HTTP_OK,
                    'message' => sprintf("A user product delete successfully")
                );
        }else{
            return array(
                'code' => Response::HTTP_BAD_REQUEST,
                'message' => sprintf("Product does not exist")
            );
        }
    }

}