<?php

namespace App\Service;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\AppUser;
use App\Entity\Business;
use App\Entity\Estimate;
use App\Entity\Product;
use App\Entity\Tax;
use App\Entity\EstimateProduct;
use App\Entity\Customer;

class EstimateManager
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager    = $entityManager;
    }

    public function loadCustomerByBusiness($business_id)
    {
        $conn = $this->entityManager->getConnection();
        $sql = sprintf('SELECT id,name as text FROM customer WHERE business_id = %d',$business_id);
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function loadEstimateCount($business_id)
    {
        $conn = $this->entityManager->getConnection();
        $sql = sprintf('SELECT COUNT(id) as total FROM estimate WHERE business_id = %d',$business_id);
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->rowCount();
    }

    public function loadDefaultTax($business_id)
    {
        $conn = $this->entityManager->getConnection();
        $sql = "SELECT id,abbreviation || ' ' || tax_rate || '%' as text,tax_rate,abbreviation FROM tax WHERE business_id = ".$business_id;
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }


    public function loadDefault($request,$user,$business_id)
    {
        $conn = $this->entityManager->getConnection();
        $sql = sprintf('SELECT id,name as text,description,price FROM product WHERE business_id = %d',$business_id);
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function loadEstimates($request,$business_id){
        $sqlQuery = $this->buildQuery($request->query,$business_id);
        $stmt = $this->entityManager->getConnection()->executeQuery($sqlQuery);
        $dataArray = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        if ("array" != gettype($dataArray)) {
            return array();
        }
        return $dataArray;
    }

    public function buildQuery($query,$business_id){
        //$sqlQuery = sprintf("SELECT * FROM estimate");
        $sqlQuery = sprintf("SELECT e.id,e.estimate_date,e.estimate_no,c.name, e.total_amount,e.status FROM estimate e INNER JOIN customer c ON e.customer_id=c.id  WHERE e.business_id=%d",$business_id);
        /*
        if ($query->has('g')) {
            $sqlQuery .= sprintf(" AND g.name='%s'", $this->getUserGroup($query->get('g')));
        }
        if ($query->has('o')) {
            $sqlQuery .= sprintf(" AND ug.organization_id IN (%s)", $query->get('o'));
        }
        */
        $sqlQuery .= " ORDER BY id ASC";
        return $sqlQuery;
    }

    private function getCustomerById($id){
        $entity = $this->entityManager->getRepository(Customer::class)->find($id);
        if("object" === gettype($entity)){
            return $entity;
        }
        return null;
    }

    private function getProductById($id){
        $entity = $this->entityManager->getRepository(Product::class)->find($id);
        if("object" === gettype($entity)){
            return $entity;
        }
        return null;
    }

    private function getBusinessById($id){
        $entity = $this->entityManager->getRepository(Business::class)->find($id);
        if("object" === gettype($entity)){
            return $entity;
        }
        return null;
    }

    private function getEstimateById($id){
        $entity = $this->entityManager->getRepository(Estimate::class)->find($id);
        if("object" === gettype($entity)){
            return $entity;
        }
        return null;
    }

    private function getEstimateProductById($id){
        $entity = $this->entityManager->getRepository(EstimateProduct::class)->find($id);
        if("object" === gettype($entity)){
            return $entity;
        }
        return null;
    }


    public function createEstimates($request,$business_id,$user)
    {
        $postedData = $request->all();

        if(array_key_exists("form_data",$postedData)){
            $formData       = $postedData["form_data"];
            $productData    = $postedData["product_data"];

            $transformData = array_column($formData, 'value', 'name');

            $estimate = new Estimate();
            $estimateEntity = $this->updateEstimateEntity($estimate,$business_id,$transformData);
            $this->entityManager->persist($estimateEntity);
            $this->entityManager->flush();

            if(count($productData) > 0){
                foreach($productData as $row){
                    $estimateProduct = $this->updateProduct(
                        new EstimateProduct(),
                        $estimateEntity,
                        $row
                    );
                    $this->entityManager->persist($estimateProduct);
                }
                $this->entityManager->flush();
            }

            return array(
                'code' => Response::HTTP_OK,
                'last_id' => $estimateEntity->getId(),
                'message' => sprintf("A business estimate created successfully")
            );
        }

        return array("no data found");
    }


    public function getEstimateView($request,$business_id,$estimate_id,$user){
        $estimateQuery = sprintf("SELECT * FROM estimate WHERE id=%d",$estimate_id);
        $estimateProductQuery = sprintf("SELECT p.id AS product_id,ep.id AS estimate_product_id,p.name,ep.description,ep.price,ep.quantity,ep.taxes FROM product p INNER JOIN estimate_product ep ON p.id=ep.product_id WHERE ep.estimate_id=%d",$estimate_id);
        $stmt1 = $this->entityManager->getConnection()->executeQuery($estimateQuery);
        $dataArray1 = $stmt1->fetch(\PDO::FETCH_ASSOC);
        if ("array" != gettype($dataArray1)) {
            $dataArray1 = array();
        }
        $stmt2 = $this->entityManager->getConnection()->executeQuery($estimateProductQuery);
        $dataArray2 = $stmt2->fetchAll(\PDO::FETCH_ASSOC);
        if ("array" != gettype($dataArray2)) {
            $dataArray2 = array();
        }

        $customerQuery = sprintf("SELECT * FROM customer WHERE id=%d",$dataArray1['customer_id']);
        $stmt3 = $this->entityManager->getConnection()->executeQuery($customerQuery);
        $dataArray3 = $stmt3->fetch(\PDO::FETCH_ASSOC);
        if ("array" != gettype($dataArray3)) {
            $dataArray3 = array();
        }

        $businessQuery = sprintf("SELECT b.id AS business_id,b.name AS business_name,b.country AS business_country,bd.province AS business_state FROM business b LEFT JOIN business_detail bd ON b.id=bd.business_id WHERE b.id=%d",$business_id);
        $stmt4 = $this->entityManager->getConnection()->executeQuery($businessQuery);
        $dataArray4 = $stmt4->fetch(\PDO::FETCH_ASSOC);
        if ("array" != gettype($dataArray1)) {
            $dataArray4 = array();
        }

        return array(
            'code' => Response::HTTP_OK,
            'data' => array(
                'estimate_data' => $dataArray1,
                'customer_data' => $dataArray3,
                'estimate_product_data' => $dataArray2,
                'business_data' => $dataArray4
            )
        );

    }

    /**
     * Modify an estimate
     */
    public function modifyEstimates($request,$estimate_id,$business_id,$user)
    {
        $postedData = $request->all();
        if (array_key_exists("form_data", $postedData)) {
            $formData = $postedData["form_data"];
            $productData = $postedData["product_data"];
            $transformData = array_column($formData, 'value', 'name');

            $estimate = $this->getEstimateById($estimate_id);
            if($estimate != NULL){

                // Update estimate

                $estimateEntity = $this->updateEstimateEntity($estimate,$business_id,$transformData);
                $this->entityManager->persist($estimateEntity);
                $this->entityManager->flush();

                // Update estimate products
                if(count($productData) > 0){
                    $defaultProductIds = $this->getEstimateProductIds($estimate_id);
                    // In case of any default product exists
                    if(count($defaultProductIds) > 0){
                        $productIds = array_column($productData,'id');
                        $diffProductIds = array_diff($defaultProductIds,$productIds);
                        $this->removeEstimateProduct($diffProductIds);
                    }
                    foreach($productData as $row){
                        if(array_key_exists('id',$row)){
                            $estimateProduct = $this->getEstimateProductById($row['id']);
                        }else{
                            $estimateProduct = new EstimateProduct();
                        }
                        if($estimateProduct != NULL){
                            $estimateProductEntity = $this->updateProduct(
                                $estimateProduct,
                                $estimateEntity,
                                $row
                            );
                            $this->entityManager->persist($estimateProductEntity);
                        }
                    }
                    $this->entityManager->flush();

                }

                return array(
                    'code' => Response::HTTP_OK,
                    'message' => 'Selected estimate updated successfully'
                );

            }

            return array(
                'code' => Response::HTTP_CONFLICT,
                'message' =>'no estimate found with this @id'
            );
        }
        return array(
            'code' => Response::HTTP_CONFLICT,
            'message'  =>  'Invalid data submitted'
        );
    }


    /**
     * Remove an estimate
     */
    public function removeEstimates($request,$estimate_id,$business_id)
    {
        $estimate = $this->getEstimateById($estimate_id);
        if($estimate != NULL){
            $estimateProductIds = $this->getEstimateProductIds($estimate_id);
            if(count($estimateProductIds) > 0){
                // remove products in case of exist
                $this->removeEstimateProduct($estimateProductIds);
            }

            $this->entityManager->remove($estimate);
            $this->entityManager->flush();

            return array(
                'code' => Response::HTTP_OK,
                'message' => 'Selected estimate removed successfully'
            );
        }
        return array(
            'code' => Response::HTTP_CONFLICT,
            'message' =>'no estimate found with this @id'
        );
    }


    /**
     * Get estimate products by estimate_id
     */
    private function getEstimateProductIds($estimate_id){
        $estimateQuery = sprintf("SELECT id FROM estimate_product WHERE estimate_id=%d",$estimate_id);
        $stmt = $this->entityManager->getConnection()->executeQuery($estimateQuery);
        $dataArray = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        if ("array" != gettype($dataArray)) {
            return array();
        }
        return array_column($dataArray,'id');
    }

    private function updateProduct($estimateProduct,$estimate, $row){
        $estimateProduct->setEstimate($estimate);
        $estimateProduct->setProduct($this->getProductById($row["product"]));
        $estimateProduct->setPrice($row["price"]);
        $estimateProduct->setQuantity($row["quantity"]);
        $estimateProduct->setDescription($row["description"]);
        if(array_key_exists('taxes',$row)){
            if(count($row['taxes']) > 0){
                $estimateProduct->setTaxes($row['taxes']);
            }
        }
        return $estimateProduct;
    }

    /**
     * Remove estimate products
     */
    private function removeEstimateProduct($estimateProductIds){
        if(count($estimateProductIds) > 0){
            // In case of estimate product removed
            foreach($estimateProductIds as $id){
                $estimateProduct = $this->getEstimateProductById($id);
                if($estimateProduct != NULL){
                    $this->entityManager->remove($estimateProduct);
                }
            }
            $this->entityManager->flush();
        }
    }

    /**
     * Update Estimate Entity
     */
    private function updateEstimateEntity($estimate,$business_id ,$transformData){
        $estimate->setBusiness($this->getBusinessById($business_id));
        if($transformData["customer"] != ""){
            $estimate->setCustomer($this->getCustomerById($transformData["customer"]));
        }
        $estimate->setEstimateNo($transformData["estimate_id"]);
        $estimate->setTitle($transformData["estimate_title"]);
        if(array_key_exists('memo',$transformData)) {
            $estimate->setMemo($transformData["memo"]);
        }
        if(array_key_exists('currency',$transformData)) {
            $estimate->setCurrency($transformData["currency"]);
        }
        if(array_key_exists('footer',$transformData)) {
            $estimate->setFooter($transformData["footer"]);
        }
        if(array_key_exists('sub_heading',$transformData)) {
            $estimate->setSubHeading($transformData["sub_heading"]);
        }
        if(array_key_exists('po_so',$transformData)) {
            $estimate->setPoSo($transformData["po_so"]);
        }
        if(array_key_exists('estimate_date',$transformData)) {
            $estimate->setEstimateDate(new \DateTime($transformData["estimate_date"]));
        }
        if(array_key_exists('expires_date',$transformData)) {
            $estimate->setExpireDate(new \DateTime($transformData["expires_date"]));
        }
        $estimate->setStatus("Saved");
        if(array_key_exists('total_amount',$transformData)){
            $estimate->setTotalAmount($transformData['total_amount']);
        }
        return $estimate;
    }

}//@