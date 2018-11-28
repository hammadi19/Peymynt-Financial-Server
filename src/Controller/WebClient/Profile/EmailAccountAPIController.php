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
use App\Service\EmailAccountManager;
use App\Base\Dictionary\Currency;
use App\Base\Dictionary\BusinessType;
use App\Base\Dictionary\Country;
use App\Base\Dictionary\BusinessSubType;
use App\Entity\AppUser;
use App\Entity\Business;
use App\Entity\UserEmailAccount;


/**
 * @Route("/api/email-accounts")
 */
class EmailAccountAPIController extends FOSRestController
{

    /**
     * @Route(
     *      "/load",
     *      name = "api.web_client.email_account.load",
     *      defaults = {
     *          "_format" = "json"
     *      })
     * @Rest\View
     */
    public function loadDefaults(Request $request,EmailAccountManager $emailAccountManager)
    {
        $view = View::create();
        $handler = $this->get('fos_rest.view_handler');
        $user = $this->getUser();
        $view->setData(array(
                'code' => Response::HTTP_OK,
                'data' => $emailAccountManager->loadUserEmailAccounts($user)
            ));

        return $handler->handle($view);
    }

    /**
     * Create create email account
     *
     * @Route(
     *      "/create",
     *      name = "api.web_client.email_account.create",
     *      defaults = {
     *          "_format" = "json"
     *      })
     * @Rest\View
     */
    public function create(Request $request, EmailAccountManager $emailAccountManager)
    {

        $view = View::create();
        $handler = $this->get('fos_rest.view_handler');

        $requiredSchema = array(
            'allow_method' => 'POST',
            'fields' => array(
                'email' => array('required'),
            )
        );

        $isValid = ValidateUtil::restValidate($request, $requiredSchema);
        if("array" === gettype($isValid)){
            $view->setData($isValid);
        }else {
            $user = $this->getUser();
            $response = $emailAccountManager->createUserEmailAccount($request->request,$user);
            $view->setData($response);
        }
        return $handler->handle($view);
    }

    /**
     *
     * @Route(
     *      "/make-primary",
     *      name = "api.web_client.email_account.make_primary",
     *      defaults = {
     *          "_format" = "json"
     *      })
     * @Rest\View
     */
    public function makePrimaryAccount(Request $request,EmailAccountManager $emailAccountManager){
        $view = View::create();
        $handler = $this->get('fos_rest.view_handler');

        $requiredSchema = array(
            'allow_method' => 'POST',
            'fields' => array(
                'email' => array('required'),
            )
        );

        $isValid = ValidateUtil::restValidate($request, $requiredSchema);
        if("array" === gettype($isValid)){
            $view->setData($isValid);
        }else {
            $user = $this->getUser();
            $response = $emailAccountManager->makePrimaryEmailAccount($request->request,$user);
            if($response){
                $jwt = $this->get('lexik_jwt_authentication.jwt_manager');
                $refreshUser = $this->getDoctrine()->getRepository(AppUser::class)->find($user->getId());
                $view->setData(array(
                    'code' => Response::HTTP_OK,
                    'message' => sprintf("Selected user email account is primary account successfully"),
                    'token' => $jwt->create($refreshUser)
                ));
            }else{
                $view->setData(array(
                    'code' => Response::HTTP_FORBIDDEN,
                    'message' => sprintf("No email address found")
                ));
            }
        }
        return $handler->handle($view);
    }


    /**
     *
     * @Route(
     *      "/remove",
     *      name = "api.web_client.email_account.remove",
     *      defaults = {
     *          "_format" = "json"
     *      })
     * @Rest\View
     */
    public function removeEmailAccount(Request $request,EmailAccountManager $emailAccountManager){
        $view = View::create();
        $handler = $this->get('fos_rest.view_handler');

        $requiredSchema = array(
            'allow_method' => 'POST',
            'fields' => array(
                'email' => array('required'),
            )
        );

        $isValid = ValidateUtil::restValidate($request, $requiredSchema);
        if("array" === gettype($isValid)){
            $view->setData($isValid);
        }else {
            $user = $this->getUser();
            $view->setData($emailAccountManager->removeEmailAccount($request->request,$user));
        }
        return $handler->handle($view);
    }

}//@
