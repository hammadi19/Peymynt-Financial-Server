<?php

namespace App\Controller\WebClient\Sales;

use App\Entity\Business;
use App\Entity\Customer;
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
use App\Service\CustomerManager;


/**
 * @Route("/api/sales/customer")
 */
class CustomerAPIController extends FOSRestController
{

    /**
     * Create Customer
     *
     * @Route(
     *      "/create",
     *      name = "api.web_client.sales.customer_create",
     *      defaults = {
     *          "_format" = "json"
     *      })
     * @Rest\View
     */
    public function create(Request $request, CustomerManager $customerManager)
    {

        $view = View::create();
        $handler = $this->get('fos_rest.view_handler');

        $requiredSchema = array(
            'allow_method' => 'POST',
            'fields' => array(
                'company_name' => array('required'),
                'business_id' => array('required'),
            )
        );

        $isValid = ValidateUtil::restValidate($request, $requiredSchema);
        if("array" === gettype($isValid)){
            $view->setData($isValid);
        }else {
            $user = $this->getUser();
            $response = $customerManager->createCustomer($request->request,$user);
            $view->setData($response);
        }
        return $handler->handle($view);
    }


    /**
     * @Route(
     *      "/{business_id}/list",
     *      name = "api.web_client.sales.customer_list",
     *      defaults = {
     *          "_format" = "json"
     *      })
     * @Rest\View
     */
    public function listCustomer(Request $request,CustomerManager $customerManager,$business_id)
    {
        $view = View::create();
        $handler = $this->get('fos_rest.view_handler');

        if('GET' === $request->getMethod()) {
            $user = $this->getUser();
            $dataArray = $customerManager->listCustomers($business_id,$user);
            $view->setData(
                array(
                    'code' => Response::HTTP_OK,
                    'data' => $dataArray
                ));


        }else{
            $view->setData(array('code' => Response::HTTP_METHOD_NOT_ALLOWED, 'message' => 'Invalid request method'));
            return $handler->handle($view);
        }

        return $handler->handle($view);
    }


    /**
     * @Route(
     *      "/{business_id}/{id}/remove",
     *      name = "api.web_client.sales.customer_remove",
     *      defaults = {
     *          "_format" = "json"
     *      })
     * @Rest\View
     */
    public function removeCustomer(Request $request,CustomerManager $customerManager, $business_id,$id)
    {
        $view = View::create();
        $handler = $this->get('fos_rest.view_handler');
        if('DELETE' === $request->getMethod()) {
            $business = $this->getDoctrine()->getRepository(Business::class)->find($business_id);
            if("object" === gettype($business)){
                $repository = $this->getDoctrine()->getRepository(Customer::class);
                $targetCustomer = $repository->findOneBy(array('id'=>$id, 'business' => $business));
                if($targetCustomer){
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->remove($targetCustomer);
                    $entityManager->flush();
                    $view->setData(array( 'code' => Response::HTTP_OK, 'message' => sprintf("Selected user removed successfully")));
                }else{
                    $view->setData(array( 'code' => Response::HTTP_NOT_ACCEPTABLE, 'message' => sprintf("No user found with this id")));
                }
            }

        }else{
            $view->setData(array( 'code' => Response::HTTP_METHOD_NOT_ALLOWED, 'message' => 'Invalid request method'));
        }
        return $handler->handle($view);
    }

    /**
     * @Route(
     *      "/{business_id}/{id}/view",
     *      name = "api.web_client.sales.customer_view",
     *      defaults = {
     *          "_format" = "json"
     *      })
     * @Rest\View
     */
    public function viewCustomer(Request $request,CustomerManager $customerManager,$business_id,$id)
    {
        $view = View::create();
        $handler = $this->get('fos_rest.view_handler');

        if('GET' === $request->getMethod()) {
            $user = $this->getUser();
            $dataArray = $customerManager->viewCustomer($business_id,$id);
            $view->setData(
                array(
                    'code' => Response::HTTP_OK,
                    'data' => $dataArray
                ));
        }else{
            $view->setData(array('code' => Response::HTTP_METHOD_NOT_ALLOWED, 'message' => 'Invalid request method'));
            return $handler->handle($view);
        }

        return $handler->handle($view);
    }

    /**
     * @Route(
     *      "/{business_id}/{id}/modify",
     *      name = "api.web_client.sales.customer_modify",
     *      defaults = {
     *          "_format" = "json"
     *      })
     * @Rest\View
     */
    public function modifyCustomer(Request $request, CustomerManager $customerManager,$business_id,$id)
    {
        $view = View::create();
        $handler = $this->get('fos_rest.view_handler');

        $requiredSchema = array(
            'allow_method' => 'PUT',
            'fields' => array(
                'company_name' => array('required'),
            )
        );

        $isValid = ValidateUtil::restValidate($request, $requiredSchema);
        if("array" === gettype($isValid)){
            $view->setData($isValid);
        }else {
            $user = $this->getUser();
            $response = $customerManager->modifyCustomer($request->request,$business_id,$id);
            $view->setData($response);
        }
        return $handler->handle($view);
    }

}//@