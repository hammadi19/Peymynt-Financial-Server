<?php

namespace App\Controller\WebClient;

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


/**
 * @Route("/app/content")
 */
class ContentController extends FOSRestController
{

    /**
     *  App Contact Us
     *
     * @Route(
     *      "/contact-us",
     *      name = "api.web_client.content.contact_us",
     *      defaults = {
     *          "_format" = "json"
     *      })
     * @Rest\View
     */
    public function contactUs(Request $request){
        $view = View::create();
        $handler = $this->get('fos_rest.view_handler');

        $requiredSchema = array(
            'allow_method' => 'POST',
            'fields' => array(
                'name' => array('required'),
                'email' => array('required','email'),
                'contact_no' => array('required')
            )
        );

        $isValid = ValidateUtil::restValidate($request, $requiredSchema);
        if("array" === gettype($isValid)){
            $view->setData($isValid);
        }else {
            $contactManager = $this->get('app_content_manager');
            $contactManager->storeContactUs($request->request);
            $view->setData(
                array(
                    'code' => Response::HTTP_OK,
                    'message' => 'Contact us form successfully submitted'
                )
            );
        }
        return $handler->handle($view);
    }

}//@
