<?php

namespace App\Controller\WebClient\Sales;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\View\View;
use App\Base\Utils\ArrayUtil;
use App\Base\Utils\ValidateUtil;
use App\Service\EstimateManager;


/**
 * @Route("/api/sales/estimate")
 */
class EstimateAPIController extends FOSRestController
{

    /**
     * @Route(
     *      "/{business_id}/default",
     *      name = "api.web_client.sales.estimate_defaults",
     *      defaults = {
     *          "_format" = "json"
     *      })
     * @Rest\View
     */
    public function loadDefaults(Request $request,EstimateManager $estimateManager,$business_id)
    {
        $view = View::create();
        $handler = $this->get('fos_rest.view_handler');
        $user = $this->getUser();

        $view->setData(array(
            'code' => Response::HTTP_OK,
            'data' => array(
                'customer_list' => $estimateManager->loadCustomerByBusiness($business_id),
                'total' => $estimateManager->loadEstimateCount($business_id),
                'tax_list' => $estimateManager->loadDefaultTax($business_id),
                'default_products' => $estimateManager->loadDefault($request->request,$user,$business_id)
            )
        ));


        return $handler->handle($view);
    }

    /**
     * @Route(
     *      "/{business_id}/list",
     *      name = "api.web_client.sales.list_estimate",
     *      defaults = {
     *          "_format" = "json"
     *      })
     * @Rest\View
     */
    public function loadEstimates(Request $request,EstimateManager $estimateManager,$business_id)
    {
        $view = View::create();
        $handler = $this->get('fos_rest.view_handler');
        $user = $this->getUser();

        $view->setData(array(
            'code' => Response::HTTP_OK,
            'data' => $estimateManager->loadEstimates($request,$business_id)
        ));


        return $handler->handle($view);
    }

    /**
     * Create Estimate
     *
     * @Route(
     *      "/{business_id}/create",
     *      name = "api.web_client.sales.estimate_create",
     *      defaults = {
     *          "_format" = "json"
     *      })
     * @Rest\View
     */
    public function create(Request $request,EstimateManager $estimateManager, $business_id)
    {


        /*
        $data = array(
            'form_data' => array(
                array('name' => 'estimate_id','value' => 2),
                array('name' => 'estimate_title', 'value' => 'Test Estimate'),
                array('name' => 'customer', 'value' => 2),
                array('name' => 'memo' , 'value' => 'Here is memo'),
                array('name' => 'footer', 'value' => 'Here is footer'),
                array('name' => 'sub_heading', 'value' => 'Here is Sub Heading'),
                array('name' => 'po_so' , 'value' => 'Black Jack'),
                array('name' => 'estimate_date', 'value' => '10-12-2018'),
                array('name' => 'expires_date' , 'value' => '30-11-2018'),
                array('name' => 'total_amount' , 'value' => 376)
            ),
            'product_data' => array(
                array('product' => 1, 'price' => 230, 'quantity' => 1, 'description' => 'X-Ray Machine is on right price', 'taxes' => array(1,2)),
                array('product' => 3, 'price' => 156, 'quantity' => 1, 'description' => 'This is description work', 'taxes' => array()),
            )
        );

        echo json_encode($data);
        die();
        */

        $view = View::create();
        $handler = $this->get('fos_rest.view_handler');

        $requiredSchema = array(
            'allow_method' => 'POST',
            'fields' => array()
        );

        $isValid = ValidateUtil::restValidate($request, $requiredSchema);
        if("array" === gettype($isValid)){
            $view->setData($isValid);
        }else {
            $user = $this->getUser();
            $response = $estimateManager->createEstimates($request->request,$business_id,$user);
            $view->setData($response);
        }
        return $handler->handle($view);
    }

    /**
     * Create Estimate
     *
     * @Route(
     *      "/{business_id}/{estimate_id}/view",
     *      name = "api.web_client.sales.estimate_view",
     *      defaults = {
     *          "_format" = "json"
     *      })
     * @Rest\View
     */
    public function viewEstimate(Request $request,EstimateManager $estimateManager, $business_id, $estimate_id)
    {
        $view = View::create();
        $handler = $this->get('fos_rest.view_handler');

        $user = $this->getUser();
        $response = $estimateManager->getEstimateView($request->request,$business_id,$estimate_id,$user);
        $view->setData($response);
        return $handler->handle($view);
    }

    /**
     * Modify Estimate
     *
     * @Route(
     *      "/{business_id}/{estimate_id}/modify",
     *      name = "api.web_client.sales.estimate_modify",
     *      defaults = {
     *          "_format" = "json"
     *      })
     * @Rest\View
     */
    public function modifyEstimate(Request $request,EstimateManager $estimateManager, $business_id, $estimate_id)
    {
        $view = View::create();
        $handler = $this->get('fos_rest.view_handler');

        $requiredSchema = array(
            'allow_method' => 'PUT',
            'fields' => array()
        );

        $isValid = ValidateUtil::restValidate($request, $requiredSchema);
        if("array" === gettype($isValid)){
            $view->setData($isValid);
        }else {
            $user = $this->getUser();
            $response = $estimateManager->modifyEstimates($request->request,$estimate_id,$business_id,$user);
            $view->setData($response);
        }
        return $handler->handle($view);
    }

    /**
     * Remove Estimate
     *
     * @Route(
     *      "/{business_id}/{estimate_id}/remove",
     *      name = "api.web_client.sales.estimate_remove",
     *      defaults = {
     *          "_format" = "json"
     *      })
     * @Rest\View
     */
    public function removeEstimate(Request $request,EstimateManager $estimateManager, $business_id, $estimate_id)
    {
        $view = View::create();
        $handler = $this->get('fos_rest.view_handler');

        if('DELETE' === $request->getMethod()) {
            $user = $this->getUser();
            $response = $estimateManager->removeEstimates($request->request,$estimate_id,$business_id);
            $view->setData($response);
        }else{
            $view->setData(array( 'code' => Response::HTTP_METHOD_NOT_ALLOWED, 'message' => 'Invalid request method'));
        }

        return $handler->handle($view);
    }

}//@
