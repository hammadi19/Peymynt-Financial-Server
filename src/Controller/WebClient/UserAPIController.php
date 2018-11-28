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
use App\Service\SettingManager;
use App\Entity\AppUser;


/**
 * @Route("/api/user")
 */
class UserAPIController extends FOSRestController
{

    /**
     * Sign up
     *
     * @Route(
     *      "/sign-up",
     *      name = "api.web_client.user.sign_up",
     *      defaults = {
     *          "_format" = "json"
     *      })
     * @Rest\View
     */
    public function signUp(Request $request,SettingManager $settingManager){
        $view = View::create();
        $handler = $this->get('fos_rest.view_handler');

        $requiredSchema = array(
            'allow_method' => 'POST',
            'fields' => array(
                //'first_name' => array('required'),
                //'last_name' => array('required'),
                'email' => array('required','email'),
                'password' => array('required'),
                //'dob_month' => array('required'),
                //'dob_year' => array('required'),
                //'user_type'=>array('required'),
            )
        );

        $isValid = ValidateUtil::restValidate($request, $requiredSchema);
        if("array" === gettype($isValid)){
            $view->setData($isValid);
        }else {
            $userManager = $this->get('app_user_manager');
            $searchResponse = $userManager->validateProductUserByEmail($request->request->get('email'));

            if(count($searchResponse) > 0){
                $view->setData(array( 'code' => Response::HTTP_NOT_ACCEPTABLE, 'message' => sprintf("Already  a user exist with this email")));
            }else{
                $response = $userManager->createUser($request->request,$searchResponse);
                $user = $response['user'];
                unset($response['user']);
                $settingManager->createSettings($user);
                $view->setData($response);
            }
        }
        return $handler->handle($view);
    }


    /**
     * @Route(
     *      "/reset-password",
     *      name = "api.web_client.user.reset_password",
     *      defaults = {
     *          "_format" = "json"
     *      })
     * @Rest\View
     */
    public function resetPassword(Request $request)
    {
        $view = View::create();
        $handler = $this->get('fos_rest.view_handler');
        $userManager = $this->get('app_user_manager');

        $view->setData($userManager->resetRecoveryPassword($request->request));

        return $handler->handle($view);
    }


    /**
     * @Route(
     *      "/forget-password",
     *      name = "api.web_client.user.forgot_password",
     *      defaults = {
     *          "_format" = "json"
     *      })
     * @Rest\View
     */
    public function forgetPassword(Request $request)
    {
        $view = View::create();
        $handler = $this->get('fos_rest.view_handler');
        $errors = array();
        $responseArray = array();
        $userManager = $this->get('app_user_manager');
        $email = $request->request->get('email');
        if($email ==  NULL OR $email == ""){
            array_push($errors, 'User email address required');
        }
        if($email != "" && filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            array_push($errors, "$email is not a valid email address");
        }

        if($email != "" && !$userManager->validateUserByEmailOnly($email)){
            array_push($errors, sprintf('No user found with this %s email',$email));
        }

        if($userManager->isLinkAlreadySent($email)){
            array_push($errors, sprintf("Your password reset email may take some time to arrive. If you have requested this email already and it hasn't arrived, please try again after 15 minutes.",$email));
        }

        //if(0 === count($errors) && 0 === count($caution)){
        if(0 === count($errors)){

            $view->setData(array(
                "code" => Response::HTTP_OK,
                "message" => "A password reset link sent at your email"
            ));

            $user = $this->getDoctrine()->getRepository(AppUser::class)->findOneBy(array('email' => $email));

            $userManager->storeAppUserLink($user,1);

        }else{
            $view->setData(array(
                "code" => Response::HTTP_NOT_ACCEPTABLE,
                'data' => array('errors' => $errors)
            ));
        }

        return $handler->handle($view);
    }

    /**
     * @Route(
     *      "/profile/get",
     *      name = "api.web_client.user.get_profile",
     *      defaults = {
     *          "_format" = "json"
     *      })
     * @Rest\View
     */
    public function getProfile(Request $request)
    {
        $user = $this->getUser();
        $view = View::create();
        $handler = $this->get('fos_rest.view_handler');
        $userManager = $this->get('app_user_manager');
        $view->setData(
            array(
                'code' => Response::HTTP_OK,
                'data' => $userManager->getUserProfileDetails($user)
            )
        );
        $context = new Context();
        $context->setVersion('1.0');
        $context->addGroup('profile');
        $view->setContext($context);
        return $handler->handle($view);
    }


    /**
     * @Route(
     *      "/profile/update",
     *      name = "api.web_client.user.update_profile",
     *      defaults = {
     *          "_format" = "json"
     *      })
     * @Rest\View
     */
    public function updateProfile(Request $request)
    {
        $user = $this->getUser();
        $view = View::create();
        $handler = $this->get('fos_rest.view_handler');

        $requiredSchema = array(
            'allow_method' => 'POST',
            'fields' => array(
                //'first_name' => array('required'),
                //'last_name' => array('required'),
                //'contact_no' => array('required'),
                //'post_code' => array('required'),
                'email' => array('required','email'),
                //'date_of_birth' => array('required'),
                //'gender' => array('required')
            )
        );
        $requestParams = $request->request->all();

        $isValid = ValidateUtil::restValidate($request, $requiredSchema);
        if("array" === gettype($isValid)){
            $view->setData($isValid);
        }else {
            // do your work
            $userManager = $this->get('app_user_manager');
            if($userManager->validateUserByEmail($requestParams['email'] , $user->getId())){
                $response = $userManager->updateProfile($user, $requestParams, $request->files->all());
                if($response){
                    $jwtManager = $this->container->get('lexik_jwt_authentication.jwt_manager');
                    $responseArray = array(
                        'code' => Response::HTTP_ACCEPTED,
                        'data' => array(
                            'message' => 'User profile updated successfully',
                            'token' => $jwtManager->create($user)
                        )
                    );
                }else{
                    $responseArray = array(
                        'code' => Response::HTTP_OK,
                        'message' => 'User profile updated successfully',
                    );
                }
            }
            else{
                $responseArray = array(
                    'code' => Response::HTTP_NOT_ACCEPTABLE,
                    'data' => array(
                        'errors' => array(
                            'A user exist with this Email'
                        )
                    )
                );
            }
            $view->setData($responseArray);
        }
        return $handler->handle($view);
    }

    /**
     * @Route(
     *      "/password",
     *      name = "api.web_client.user.password",
     *      defaults = {
     *          "_format" = "json"
     *      })
     * @Rest\View
     */
    public function password(Request $request)
    {
        $view = View::create();
        $handler = $this->get('fos_rest.view_handler');

        $requiredSchema = array(
            'allow_method' => 'PUT',
            'fields' => array(
                'old_password' => array('required'),
                'password' => array('required')
            )
        );

        $isValid = ValidateUtil::restValidate($request, $requiredSchema);
        if("array" === gettype($isValid)){
            $view->setData($isValid);
        }else {
            $user = $this->getUser();
            $userManager = $this->get('app_user_manager');
            $isPasswordChanged = $userManager->changePassword($user, $request->request->get('old_password'), $request->request->get('password'));
            if ($isPasswordChanged) {
                $jwtManager = $this->container->get('lexik_jwt_authentication.jwt_manager');
                $view->setData(array(
                    'code' => Response::HTTP_OK,
                    'data' => array(
                        'message' => 'Password changed successfully',
                        'token' => $jwtManager->create($user)
                    )
                ));
            } else {
                $view->setData(array(
                    'code' => Response::HTTP_NOT_ACCEPTABLE,
                    'message' => 'Invalid old password'
                ));
            }
        }

        return $handler->handle($view);
    }

    /**
     * @Route(
     *      "/list",
     *      name = "api.web_client.user.list",
     *      defaults = {
     *          "_format" = "json"
     *      })
     * @Rest\View
     */
    public function listUsers(Request $request)
    {
        $view = View::create();
        $handler = $this->get('fos_rest.view_handler');

        if('GET' === $request->getMethod()) {
            $userManager = $this->get('app_user_manager');
            $dataArray = $userManager->getUsersList($request->query);
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
     *      "/ping",
     *      name = "app.web_client.ping",
     *      defaults = {
     *          "_format" = "json"
     *      })
     * @Rest\View
     */
    public function ping()
    {
        // Test action
        $mailer = $this->get('mailer');
        $message = (new \Swift_Message("Welcome Mr."))
            ->setFrom('info@taskbee.ca')
            ->setTo('shahidzamironline@gmail.com')
            ->setBody(
                '<h3>Welcome to task bee',
                'text/html'
            );

        try {
            $response = $mailer->send($message);
        }catch (\Exception $ex) {
            echo $ex->getMessage();
            //return $ex->getMessage();
        }

        return new JsonResponse(array('black jack'));
    }

    /**
     * Set user about
     *
     * @Route(
     *      "/set-about",
     *      name = "api.web_client.user.set_about",
     *      defaults = {
     *          "_format" = "json"
     *      })
     * @Rest\View
     */
    public function setAbout(Request $request){
        $view = View::create();
        $handler = $this->get('fos_rest.view_handler');

        $requiredSchema = array(
            'allow_method' => 'POST',
            'fields' => array(
                'about' => array('required'),
                'work_rate' => array('required'),
            )
        );

        $isValid = ValidateUtil::restValidate($request, $requiredSchema);
        if("array" === gettype($isValid)){
            $view->setData($isValid);
        }else {
            $userManager = $this->get('app_user_manager');
            $user = $this->getUser();
            $userManager->setAboutUser($request,$user);
            $view->setData(
                array(
                    'code' => Response::HTTP_OK,
                    'message' => sprintf("A user about set successfully")
                )
            );
        }
        return $handler->handle($view);
    }

}//@
