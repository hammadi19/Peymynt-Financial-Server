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
use App\Service\ProductManager;
use App\Service\BusinessManager;
use App\Entity\Business;

/**
 * @Route("/api/sales/product")
 */
class ProductAPIController extends FOSRestController
{
	/**
     * Create Product
     *
     * @Route(
     *      "/{business_id}/create",
     *      name = "api.web_client.product_create",
     *      defaults = {
     *          "_format" = "json"
     *      })
     * @Rest\View
     */
    public function createAction(Request $request, ProductManager $productManager,$business_id)
    {
        $view = View::create();
        $handler = $this->get('fos_rest.view_handler');
       	//business will add
        $requiredSchema = array(
            'allow_method' => 'POST',
            'fields' => array(
                'name' => array('required'),
                'price' => array('required'),
            )
        );
        $isValid = ValidateUtil::restValidate($request, $requiredSchema);
        if("array" === gettype($isValid)){
            $view->setData($isValid);
        }else {
            $user = $this->getUser();
            $business = $this->getDoctrine()->getRepository(Business::class)->find($business_id);
            if($business){
                $response = $productManager->createProduct($request->request,$business,$user);
                $view->setData($response);
            }else{
                $view->setData(array('code' => Response::HTTP_BAD_REQUEST, 'message' => 'Invalid @business_id'));
            }
        }
        return $handler->handle($view);
    }

    /**
     * Update Product
     *
     * @Route(
     *      "/{business_id}/{product_id}/update",
     *      name = "api.web_client.product_update",
     *      defaults = {
     *          "_format" = "json"
     *      })
     * @Rest\View
     */
    public function updateAction(Request $request, ProductManager $productManager,$business_id,$product_id)
    {
        $view = View::create();
        $handler = $this->get('fos_rest.view_handler');
        //business will add
        $requiredSchema = array(
            'allow_method' => 'PUT',
            'fields' => array(
                'name' => array('required'),
                'price' => array('required'),
            )
        );
        $isValid = ValidateUtil::restValidate($request, $requiredSchema);
        if("array" === gettype($isValid)){
            $view->setData($isValid);
        }else {
            $user = $this->getUser();
            $business = $this->getDoctrine()->getRepository(Business::class)->find($business_id);
            if($business) {
                $response = $productManager->updateProduct($request->request, $business, $user,$product_id);
                $view->setData($response);
            }else{
                $view->setData(array('code' => Response::HTTP_BAD_REQUEST, 'message' => 'Invalid @business_id'));
            }
        }
        return $handler->handle($view);
    }


    /**
     * Remove Product
     *
     * @Route(
     *      "/{business_id}/{product_id}/delete",
     *      name = "api.web_client.product_delete",
     *      defaults = {
     *          "_format" = "json"
     *      })
     * @Rest\View
     */
    public function deleteAction(Request $request, ProductManager $productManager,$business_id,$product_id)
    {
        $view = View::create();
        $handler = $this->get('fos_rest.view_handler');
        if('DELETE' === $request->getMethod()) {
            $user = $this->getUser();
            $business = $this->getDoctrine()->getRepository(Business::class)->find($business_id);
            if($business) {
                $response = $productManager->deleteProduct($business,$product_id);
                $view->setData($response);
            }else{
                $view->setData(array('code' => Response::HTTP_BAD_REQUEST, 'message' => 'Invalid @business_id'));
            }
        }else{
            $view->setData(array( 'code' => Response::HTTP_METHOD_NOT_ALLOWED, 'message' => 'Invalid request method'));
        }
        return $handler->handle($view);

    }


    /**
     * get Product
     *
     * @Route(
     *      "/{business_id}/{product_id}/view",
     *      name = "api.web_client.sales.product_view",
     *      defaults = {
     *          "_format" = "json"
     *      })
     * @Rest\View
     */
    public function viewProduct(Request $request, ProductManager $productManager, $business_id,$product_id)
    {
          $view = View::create();
        $handler = $this->get('fos_rest.view_handler');
        if('GET' === $request->getMethod()) {
            $user = $this->getUser();
            $business = $this->getDoctrine()->getRepository(Business::class)->find($business_id);
            if($business) {
                $response = $productManager->getProduct($business,$product_id);
                $view->setData(
                    array(
                        'code' => Response::HTTP_OK,
                        'data' => $response
                    )
                );
            }else{
                $view->setData(array('code' => Response::HTTP_BAD_REQUEST, 'message' => 'Invalid @business_id'));
            }
        }else{
            $view->setData(array('code' => Response::HTTP_METHOD_NOT_ALLOWED, 'message' => 'Invalid request method'));
        }    
        return $handler->handle($view);
    }


     /**
     * @Route(
     *      "/{business_id}/list",
     *      name = "api.web_client.product_list",
     *      defaults = {
     *          "_format" = "json"
     *      })
     * @Rest\View
     */
    public function listProduct(Request $request,ProductManager $productManager,$business_id)
    {
        $view = View::create();
        $handler = $this->get('fos_rest.view_handler');
		if('GET' === $request->getMethod()) {
            $user = $this->getUser();
            $business = $this->getDoctrine()->getRepository(Business::class)->find($business_id);
            if($business) {
                $response = $productManager->listProduct($business);
                $view->setData(
                    array(
                    'code' => Response::HTTP_OK,
                    'data' => $response
                    )
                );
            }else{
                $view->setData(array('code' => Response::HTTP_BAD_REQUEST, 'message' => 'Invalid @business_id'));
            }
        }else{
	    	$view->setData(array('code' => Response::HTTP_METHOD_NOT_ALLOWED, 'message' => 'Invalid request method'));
	    }    
		return $handler->handle($view);
    }



}//@