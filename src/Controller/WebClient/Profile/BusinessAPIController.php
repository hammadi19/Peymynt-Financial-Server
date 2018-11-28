<?php

namespace App\Controller\WebClient\Profile;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\Annotations\View as ViewAnnotation;
use FOS\RestBundle\View\View;
use App\Base\Utils\ArrayUtil;
use App\Base\Utils\ValidateUtil;
use App\Service\BusinessManager;
use App\Base\Dictionary\Currency;
use App\Base\Dictionary\BusinessType;
use App\Base\Dictionary\Country;
use App\Base\Dictionary\BusinessSubType;
use App\Entity\AppUser;
use App\Entity\Business;


/**
 * @Route("/api/business")
 */
class BusinessAPIController extends FOSRestController
{

    /**
     * @Route(
     *      "/default",
     *      name = "api.web_client.business_defaults",
     *      defaults = {
     *          "_format" = "json"
     *      })
     * @Rest\View
     */
    public function loadDefaults(Request $request,BusinessManager $businessManager)
    {
        $view = View::create();
        $handler = $this->get('fos_rest.view_handler');
        $view->setData(array(
                'code' => Response::HTTP_OK,
                'data' => array(
                    'currency' => Currency::$data,
                    'business_type' => BusinessType::$data,
                    'business_sub_type' => BusinessSubType::$data,
                    'country' => Country::$data
                )
            ));

        return $handler->handle($view);
    }

    /**
     * Create Business
     *
     * @Route(
     *      "/create",
     *      name = "api.web_client.business_create",
     *      defaults = {
     *          "_format" = "json"
     *      })
     * @Rest\View
     */
    public function create(Request $request, BusinessManager $businessManager)
    {

        $view = View::create();
        $handler = $this->get('fos_rest.view_handler');

        $requiredSchema = array(
            'allow_method' => 'POST',
            'fields' => array(
                'name' => array('required'),
                'business_type' => array('required'),
                'country' => array('required'),
                'currency' => array('required'),
                'organization_type' => array('required'),
            )
        );

        $isValid = ValidateUtil::restValidate($request, $requiredSchema);
        if("array" === gettype($isValid)){
            $view->setData($isValid);
        }else {
            $user = $this->getUser();
            $response = $businessManager->createBusiness($request->request,$user);
            $view->setData($response);
        }
        return $handler->handle($view);
    }

    /**
     * Modify Business
     * @Route(
     *      "/{business_id}/modify",
     *      name = "api.web_client.business_modify",
     *      defaults = {
     *          "_format" = "json"
     *      })
     * @Rest\View
     */
    public function modifyBusiness(Request $request, BusinessManager $businessManager, $business_id)
    {
        $view = View::create();
        $handler = $this->get('fos_rest.view_handler');

        $requiredSchema = array(
            'allow_method' => 'PUT',
            'fields' => array(
                'name' => array('required'),
                'business_type' => array('required'),
                'country' => array('required'),
                'currency' => array('required'),
                'organization_type' => array('required'),
            )
        );

        $isValid = ValidateUtil::restValidate($request, $requiredSchema);
        if("array" === gettype($isValid)){
            $view->setData($isValid);
        }else {
            $user = $this->getUser();
            $response = $businessManager->modifyBusiness($request->request,$business_id);
            $view->setData($response);
        }
        return $handler->handle($view);
    }

    /**
     * @Route(
     *      "/list",
     *      name = "api.web_client.business_list",
     *      defaults = {
     *          "_format" = "json"
     *      })
     * @Rest\View
     */
    public function listBusiness(Request $request,BusinessManager $businessManager)
    {
        $view = View::create();
        $handler = $this->get('fos_rest.view_handler');

        if('GET' === $request->getMethod()) {
            $user = $this->getUser();
            $dataArray = $businessManager->listBusiness($request->query,$user);
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
     *
     * @Route(
     *      "/make-primary",
     *      name = "api.web_client.business_make_primary",
     *      defaults = {
     *          "_format" = "json"
     *      })
     * @Rest\View
     */
    public function makePrimaryBusiness(Request $request,BusinessManager $businessManager){
        $view = View::create();
        $handler = $this->get('fos_rest.view_handler');

        $requiredSchema = array(
            'allow_method' => 'POST',
            'fields' => array(
                'business_id' => array('required'),
            )
        );

        $isValid = ValidateUtil::restValidate($request, $requiredSchema);
        if("array" === gettype($isValid)){
            $view->setData($isValid);
        }else {
            $user = $this->getUser();
            $response = $businessManager->makeBusinessPrimary($request->request,$user);
            $jwtManager = $this->container->get('lexik_jwt_authentication.jwt_manager');
            $responseArray = array(
                'code' => Response::HTTP_ACCEPTED,
                'data' => array(
                    'message' => 'Selected business successfully set primary',
                    'token' => $jwtManager->create($user)
                )
            );
            $view->setData($responseArray);
        }
        return $handler->handle($view);
    }

    /**
     * @Route(
     *      "/{business_id}/load",
     *      name = "api.web_client.business_load",
     *      defaults = {
     *          "_format" = "json"
     *      })
     * @Rest\View
     */
    public function loadBusiness(Request $request,$business_id,BusinessManager $businessManager)
    {

        $view = View::create();
        $handler = $this->get('fos_rest.view_handler');

        if('GET' === $request->getMethod()) {
            $user = $this->getUser();
            $dataArray = $businessManager->loadUserBusiness($business_id,$user);
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
     *      "/{business_id}/view",
     *      name = "api.web_client.business_view",
     *      defaults = {
     *          "_format" = "json"
     *      })
     * @Rest\View
     */
    public function viewBusiness(Request $request, BusinessManager $businessManager, $business_id)
    {
        $view = View::create();
        $handler = $this->get('fos_rest.view_handler');

        if('GET' === $request->getMethod()) {
            $user = $this->getUser();
            $dataArray = $businessManager->viewBusiness($business_id);
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

}//@
