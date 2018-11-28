<?php

namespace App\Controller\WebClient\Purchases;

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
use App\Entity\AppUser;
use App\Entity\Business;


/**
 * @Route("/api/purchases/vendor")
 */
class VendorAPIController extends FOSRestController
{


}//@
