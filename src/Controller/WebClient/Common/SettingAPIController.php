<?php

namespace App\Controller\WebClient\Common;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\View\View;
use App\Base\Utils\ArrayUtil;
use App\Service\SettingManager;
use App\Base\Utils\ValidateUtil;
use App\Entity\AppUser;
use App\Entity\Setting;


/**
 * @Route("/api/user/setting")
 */
class SettingAPIController extends FOSRestController
{

    /**
     * @Route(
     *      "/get",
     *      name = "api.web_client.common.setting_get",
     *      defaults = {
     *          "_format" = "json"
     *      })
     * @Rest\View
     */
    public function getSettings(Request $request,SettingManager $settingManager)
    {

        $view = View::create();
        $handler = $this->get('fos_rest.view_handler');

        if('GET' === $request->getMethod()) {
            $user = $this->getUser();
            //$repository = $this->getDoctrine()->getRepository(Setting::class);
            //$settingObject = $repository->findSettingsByUser($user);
            $view->setData(
                array(
                    'code' => Response::HTTP_OK,
                    'data' => $settingManager->getSetting($user)
                ));
        }else{
            $view->setData(array('code' => Response::HTTP_METHOD_NOT_ALLOWED, 'message' => 'Invalid request method'));
            return $handler->handle($view);
        }

        return $handler->handle($view);
    }

    /**
     * Modify settings
     *
     * @Route(
     *      "/update",
     *      name = "api.web_client.common.setting_update",
     *      defaults = {
     *          "_format" = "json"
     *      })
     * @Rest\View
     */
    public function modify(Request $request, SettingManager $settingManager)
    {

        $view = View::create();
        $handler = $this->get('fos_rest.view_handler');


        $requiredSchema = array(
            'allow_method' => 'POST',
        );



        $isValid = ValidateUtil::restValidate($request, $requiredSchema);
        if("array" === gettype($isValid)){
            $view->setData($isValid);
        }else {
            if("json" === $request->getContentType()){
                $user = $this->getUser();
                $response = $settingManager->updateSettings($request->request,$user);
                $view->setData($response);
            }else{
                $view->setData(array(
                    'code' => Response::HTTP_METHOD_NOT_ALLOWED,
                    'message' => 'Invalid content type found'
                ));
            }
        }
        return $handler->handle($view);

    }


}//@
