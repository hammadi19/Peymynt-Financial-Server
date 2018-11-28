<?php

namespace App\Controller\WebClient\Misc;

use App\Entity\Tax;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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
use App\Service\TaxManager;
use App\Base\Dictionary\Currency;
use App\Base\Dictionary\BusinessType;
use App\Base\Dictionary\Country;
use App\Base\Dictionary\BusinessSubType;
use App\Entity\AppUser;
use App\Entity\Business;


/**
 * @Route("/api/tax")
 */
class TaxAPIController extends FOSRestController
{

    /**
     * Create Tax
     *
     * @Route(
     *      "/create",
     *      name = "api.web_client.misc.tax_create",
     *      defaults = {
     *          "_format" = "json"
     *      })
     * @Rest\View
     */
    public function create(Request $request, TaxManager $taxManager)
    {

        $view = View::create();
        $handler = $this->get('fos_rest.view_handler');

        $requiredSchema = array(
            'allow_method' => 'POST',
            'fields' => array(
                'name' => array('required'),
                'abbreviation' => array('required'),
                'business_id' => array('required'),
                'tax_rate' => array('required'),
            )
        );

        $isValid = ValidateUtil::restValidate($request, $requiredSchema);
        if("array" === gettype($isValid)){
            $view->setData($isValid);
        }else {
            $user = $this->getUser();
            $response = $taxManager->createTax($request->request,$user);
            $view->setData($response);
        }
        return $handler->handle($view);
    }


    /**
     * @Route(path="/{business_id}/list", requirements={"business_id":"\d+"},
     *     name="api.web_client.misc.tax_list",
     *      defaults = {
     *          "_format" = "json"
     *      })
     * @Method({"GET"})
     * @ParamConverter("business", class="App\Entity\Business", options={"id": "business_id"})
     * @param Business $business
     * @param TaxManager $taxManager
     * @return
     * @Rest\View()
     */
    public function list(Business $business, TaxManager $taxManager)
    {
        $view = View::create();
        $handler = $this->get('fos_rest.view_handler');

        $user = $this->getUser();
        $dataArray = $taxManager->listTaxes($business, $user);
        $view->setData([
            'code' => Response::HTTP_OK,
            'data' => $dataArray
        ]);

        return $handler->handle($view);
    }

}
