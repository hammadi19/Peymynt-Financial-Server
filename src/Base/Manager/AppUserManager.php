<?php

namespace App\Base\Manager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\AppUser;
use App\Entity\Group;
use App\Entity\Business;
use App\Entity\UserGroup;
use App\Entity\AppUserLink;
use App\Base\Utils\TokenUtil;
use App\Base\Utils\ArrayUtil;


class AppUserManager
{
    /**
     * @var $container
     */
    protected $container;

    /**
     * @var \Symfony\Component\Security\Core\Encoder\EncoderFactory
     */
    private $encoderFactory;

    /**
     * $em
     */
    private $em;

    /**
     * @param ContainerInterface $container
     * @param EncoderFactory $encoderFactory
     */
    public function __construct(ContainerInterface $container,EncoderFactory $encoderFactory){
        $this->container = $container;
        $this->encoderFactory = $encoderFactory;
        $this->em = $this->container->get('doctrine.orm.entity_manager');
    }

    /**
     * Check for any user exist with that specific email
     *
     * @param $email
     * @return array
     */
    public function validateProductUserByEmail($email){
        $sqlString = sprintf("SELECT id FROM app_user WHERE (LOWER(email) like '%s') AND (is_archived=FALSE OR is_archived IS NULL)",$email);
        $stmt = $this->em->getConnection()->executeQuery($sqlString);
        $dataArray = $stmt->fetch(\PDO::FETCH_ASSOC);
        if("array" != gettype($dataArray)){
            return array();
        }
        return $dataArray;
    }

    public function createUser($request, $searchResponse)
    {

        $user = new AppUser();
        $user->setEmail($request->get('email'));
        $encoder = $this->encoderFactory->getEncoder($user);
        $hashedPassword = $encoder->encodePassword($request->get('password'), $user->getSalt());
        $user->setPassword($hashedPassword);
        $user->setPlainPassword($hashedPassword);
        $user->setIsActive(TRUE);
        $user->setLastLoginDate(NULL);
        $user->setIsArchived(FALSE);
        $this->em->persist($user);
        $this->em->flush();

        $group = $this->em->getRepository(Group::class)->findOneBy(['name' => "ROLE_PERSONAL"]);
        $userGroup = new UserGroup();
        $userGroup->setGroup($group);
        $userGroup->setUser($user);
        $this->em->persist($userGroup);
        $this->em->flush();

        $business = new Business();
        // needs to create new user
        $business->setName('Personal');
        $business->setUser($user);
        $business->setIsPersonal(TRUE);
        $business->setIsPrimary(TRUE);

        $this->em->persist($business);
        $this->em->flush();


        return array(
            'code' => Response::HTTP_OK,
            'message' => sprintf("A user created successfully"),
            'user'  => $user
        );

    }

    /**
     * @param $user
     * @return bool
     */
    public function storeAppUserLink($user, $status)
    {
        $this->removeObsoleteUserLink($user);
        $hashString = TokenUtil::generateToken();
        $userLink = new AppUserLink();
        $userLink->setUser($user);
        $userLink->setHashString($hashString);
        $userLink->setCreatedDate(new \DateTime('now'));
        $this->em->persist($userLink);
        $this->em->flush();
        $this->sendForgetPasswordLinkByEmail($user, $hashString, $status);
        return TRUE;
    }


    /**
     * Prepare and send user recover password email
     *
     * @param $user
     * @param $hashString
     */
    public function sendForgetPasswordLinkByEmail($user, $hashString, $status)
    {

        $linkPath = $this->container->getParameter('app_web_client') . '/set-password?hq=' . $hashString;
        $dataArray = array(
            'first_name' => $user->getFirstName(),
            'last_name' => $user->getLastName(),
            'user_email' => $user->getEmail(),
            'recovery_link' => $linkPath,
        );

        $transmitter = $this->container->get('app_email_transmitter');
        if ($status == 1) {
            $actionKey = 'recover_user_password';
            $title = 'Reset Your Password';
        } elseif ($status == 0) {
            $actionKey = 'create_user';
            $title = 'Set Your Password';
        }
        $transmitter->trigger(array(
            'action_key' => $actionKey,
            'email_schema' => array(
                'subject' => 'Task-Bee: ' . $title,
                'to' => array($user->getEmail())
            ),
            'email_data' => $dataArray
        ));

    }

    /**
     * Remove old link first if found
     *
     * @param $user
     */
    public function removeObsoleteUserLink($user)
    {
        $query = $this->em->createQuery("SELECT l FROM \App\Entity\AppUserLink l WHERE (l.user = :USER)");
        $query->setParameter(':USER', $user);
        $result = $query->getResult();
        if ($result) {
            foreach ($result as $linkObject) {
                $this->em->remove($linkObject);
                $this->em->flush();
            }
        }
    }


    /**
     * @param $email
     * @return bool
     */
    public function isLinkAlreadySent($email){
        $query = $this->em->createQuery("SELECT u FROM \App\Entity\AppUser u WHERE (LOWER(u.email) like :email)");
        $query->setParameter('email', strtolower($email));
        $result = $query->getResult();
        if("array" == gettype($result) && count($result) > 0){
            $linkObject = $this->em->getRepository(AppUserLink::class)->findOneBy(array('user'=>$result[0]));
            if($linkObject){
                $nowDate = new \DateTime('now');
                $to_time = strtotime($linkObject->getCreatedDate()->format('Y-m-d H:i:s'));
                $from_time = strtotime($nowDate->format('Y-m-d H:i:s'));
                $diffMinutes = round(abs($to_time - $from_time) / 60,2);
                if($diffMinutes < 15){
                    return TRUE;
                }
            }
            return FALSE;
        }
        return FALSE;
    }



    /**
     * Reset password method
     *
     * @param $user
     * @param $password
     */
    public function resetUserPassword($user, $password){
        $encoder = $this->encoderFactory->getEncoder($user);
        $hashedPassword = $encoder->encodePassword($password, $user->getSalt());
        $user->setPassword($hashedPassword);
        $user->setPlainPassword($hashedPassword);
        $this->em->persist($user);
        $this->em->flush();
    }

    public function getUserById($userId)
    {
        return $this->em->getRepository(AppUser::class)->find($userId);
    }

    /**
     * Get user list
     *
     * @return array
     */
    public function getUsersList($query)
    {
        $sqlQuery = $this->buildQuery($query);
        $stmt = $this->em->getConnection()->executeQuery($sqlQuery);
        $dataArray = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        if ("array" != gettype($dataArray)) {
            return array();
        }
        return $dataArray;
    }

    /**
     * Build a query based on parameters
     *
     * @param $query
     * @return string
     */
    public function buildQuery($query)
    {

        $sqlQuery = sprintf("SELECT DISTINCT  u.id AS user_id, u.first_name, u.last_name, u.email, u.contact_no, u.date_of_birth, u.gender, u.about
                                    FROM app_user u
                                    INNER JOIN user_group ug ON u.id = ug.user_id
                                    INNER JOIN groups g ON ug.group_id = g.id
                                    WHERE (is_archived IS NULL OR is_archived = FALSE)");
        if ($query->has('g')) {
            $sqlQuery .= sprintf(" AND g.name='%s'", $query->get('g'));
        }
        $sqlQuery .= " ORDER BY u.id ASC";
        return $sqlQuery;
    }

    /**
     * Approves a user
     *
     * @param $user
     * @param $approveeId
     * @return array
     */
    public function approveUser($user, $approveeId)
    {
        $userId = $user->getId(); //specifically taking ID from complete object
        $userEntity = $this->em->getRepository(User::class)->find($approveeId);
        if ("object" === gettype($userEntity)) {
            $userActionEntity = new UserActions();
            $userActionEntity->setApprover($user);
            $userActionEntity->setApprovee($userEntity);
            $userActionEntity->setActionDate(new \DateTime('now'));
            $userEntity->setIsApproved(true);

            $this->em->persist($userActionEntity);
            $this->em->persist($user);
            $this->em->flush();

            $notifyManager = $this->container->get('dr_iq.notification_manager');
            $title = 'Your account has been approved.';
            $body = 'Account Approval';
            $type = 'dr-iq-patient-approval';
            $tag = 'dr-iq-patient';
            $notifyManager->storeNotification($approveeId, $userId, $title, $body, $type, $referenceId = null, $tag);
            $this->sendEmail($userEntity->getEmail(), $userEntity->getFirstName(), $userEntity->getLastName());
            return array(

                'code' => Response::HTTP_OK,
                'message' => 'The user has been approved successfully'

            );

        }
        return array(
            'code' => Response::HTTP_NOT_ACCEPTABLE,
            'message' => sprintf('No user found with this id'),

        );


    }

    /**
     * Check for email exists exclude target user
     *
     * @param $email
     * @param $uid
     * @return bool
     */
    public function validateUserByEmail($email , $uid){
        $query = $this->em->createQuery("SELECT u FROM \App\Entity\AppUser u WHERE u.id != :uid AND (LOWER(u.email) like :email) AND u.isArchived=FALSE");
        $query->setParameter('email', strtolower($email));
        $query->setParameter('uid', $uid);
        $result = $query->getResult();
        if($result){
            return FALSE;
        }
        return TRUE;
    }


    /**
     * Check for any user exist with that specific email
     *
     * @param $email
     * @return bool
     */
    public function validateUserByEmailOnly($email){
        $query = $this->em->createQuery("SELECT u FROM \App\Entity\AppUser u WHERE (LOWER(u.email) like :email) AND (u.isArchived=FALSE OR u.isArchived IS NULL)");
        $query->setParameter('email', strtolower($email));
        $result = $query->getResult();
        if($result){
            return TRUE;
        }
        return FALSE;
    }

    public function getUserByHashLink($hashString){
        $userLink = $this->em->getRepository(AppUserLink::class)->findOneBy(array('hashString'=> $hashString ));
        if($userLink){
            return $userLink;
        }
        return NULL;
    }

    public function deleteHashLink($hashString,$user){
        $userLink = $this->em->getRepository(AppUserLink::class)->findOneBy(array('hashString'=> $hashString,'user'=>$user ));
        if($userLink){
            $this->em->remove($userLink);
            $this->em->flush();
        }
        return NULL;
    }

    /**
     * Verify link
     *
     * @param $linkString
     * @return bool
     */
    public function verifyIsLinkValid($linkString){
        if(!empty($linkString)) {
            $userLink = $this->getUserByHashLink($linkString);
            if($userLink){
                $nowDate = new \DateTime('now');
                $expireDate     = $userLink->getCreatedDate();
                $datetime1      = strtotime($expireDate->format("Y-m-d h:i:s"));
                $datetime2      = strtotime($nowDate->format("Y-m-d h:i:s"));
                $interval  = abs($datetime2 - $datetime1 );
                $minuteInterval   = round($interval / 60);
                if($minuteInterval <= 2880){
                    return TRUE;
                }
                return FALSE;
            }
            return FALSE;
        }
        return FALSE;
    }

    public function resetRecoveryPassword($request){
        $postedData = $request->all();
        $isParamFound = ArrayUtil::isParamExist(array('hq', 'password_first','password_second'), $postedData);
        if ("string" === gettype($isParamFound)) {
            return array('code' => Response::HTTP_NOT_ACCEPTABLE, 'message' => $isParamFound);
        } else {
            if(!$this->verifyIsLinkValid($request->get('hq'))){
                return array('code' => Response::HTTP_NOT_ACCEPTABLE, 'message' => "Forget password link expired");
            }
            $firstPassword  = $request->get('password_first');
            $secondPassword = $request->get('password_second');
            $errors = array();
            if($firstPassword != $secondPassword){
                array_push($errors, 'Please enter same password' );
            }
            if(!empty($firstPassword) AND strlen($firstPassword) < 8){
                array_push($errors, 'Password must be minimum 8 character' );
            }
            if(0 === count($errors)){
                // make it work
                $userLink = $this->getUserByHashLink($request->get('hq'));
                $this->resetUserPassword($userLink->getUser(),$firstPassword);
                //$this->deleteHashLink($userLink->getHashString(),$userLink->getUser());
                $this->removeObsoleteUserLink($userLink->getUser());
                return array('code' => Response::HTTP_OK, 'message' => 'User password successfully reset');
            }
            return array(
                'code' => Response::HTTP_NOT_ACCEPTABLE,
                'data' => array(
                    'errors' => $errors
                )
            );
        }}

    /**
     * Change password
     *
     * @param $user
     * @param $oldPassword
     * @param $newPassword
     * @return bool
     */
    public function changePassword($user, $oldPassword, $newPassword){

        $encoder = $this->encoderFactory->getEncoder($user);
        $validPassword = $encoder->isPasswordValid( $user->getPlainPassword() , $oldPassword , $user->getSalt() );
        if($validPassword){
            $this->resetUserPassword($user,$newPassword);
            return TRUE;
        }
        return FALSE;
    }




    /**
     * Send Email with attachment
     * @param $email
     * @param $firstname
     * @param $lastname
     */
    public function sendEmail($email, $firstname , $lastname){

        $dataArray = array(
            'first_name' => $firstname,
            'last_name' => $lastname,
        );

        $transmitter = $this->container->get('attech.email_transmitter');

        $actionKey = 'approval_template';
        $title = 'Task-Bee: Account approved';

        $transmitter->trigger(array(
            'action_key' => $actionKey,
            'email_schema' => array(
                'subject' => $title,
                'to' => $email
            ),
            'email_data' => $dataArray
        ));

    }

    /**
     * Get user profile details
     *
     * @param $user
     * @return array
     */
    public function getUserProfileDetails($user)
    {

        $path = $this->container->get('app_system_path');
        $userImage = (NULL == $user->getProfileImage()) ? '' : $path->basePath . '/uploads/app/profiles/' . $user->getProfileImage();

        return array(
            "email" => $user->getEmail(),
            "first_name" => $user->getFirstName(),
            "last_name" => $user->getLastName(),
            "date_of_birth" => $user->getDateOfBirth(),
            "gender" => $user->getGender(),
            "is_active" => $user->getIsActive(),
            "profile_image" => $userImage,
            "city" => $user->getCity(),
            "country" => $user->getCountry(),
            "province" => $user->getProvince(),
            "post_code" => $user->getPostCode()

        );
    }

    /**
     * Update user profile
     *
     *
     * @param $user
     * @param $dataArray
     * @param $filesBag
     * @return bool
     */
    public function updateProfile($user, $dataArray, $filesBag)
    {
        $flag = FALSE;
        if ($dataArray['email'] != $user->getEmail()) {
            $flag = TRUE;
        }
        $user->setEmail($dataArray['email']);
        $user->setFirstName($dataArray['first_name']);
        $user->setLastName($dataArray['last_name']);
        $user->setProvince($dataArray['province']);
        $user->setCity($dataArray['city']);
        $user->setCountry($dataArray['country']);
        $user->setPostCode($dataArray['post_code']);
        $user->setDateOfBirth(new \DateTime($dataArray['date_of_birth']));

        if (array_key_exists('profile_image', $filesBag)) {
            $path = $this->container->get('app_system_path');
            $file = $filesBag['profile_image'];
            $filename = $file->getClientOriginalName();
            $src = $path->webDir1 . '/uploads/app/profiles/';
            $extension = end(explode(".", $filename));
            $newFileName = md5(uniqid()) . "." . $extension;
            $file->move($src, $newFileName);
            $user->setProfileImage($newFileName);
        }

        $this->em->persist($user);
        $this->em->flush();
        return $flag;
    }



    public function setAboutUser($request, $user)
    {
        $user->setAbout($request->get('about'));
        $user->setWorkRate($request->get('work_rate'));
        $this->em->persist($user);
        $this->em->flush();
    }


}//@