<?php

namespace App\Controller\WebClient;

use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\View\View;


/**
 * @Route("/api")
 */
class UserLoginAPIController extends FOSRestController
{

    /**
     * @Route(
     *      "/login_check",
     *      name = "app.web_client.login_check",
     *      defaults = {
     *          "_format" = "json"
     *      })
     * @Rest\View
     */
    public function loginCheck()
    {
        $view = View::create();
        $handler = $this->get('fos_rest.view_handler');

        return $handler->handle($view);
    }

}//@
