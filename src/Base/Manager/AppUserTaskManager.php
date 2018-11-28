<?php

namespace App\Base\Manager;

use App\Entity\AppUser;
use App\Entity\TaskApplication;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Base\Utils\TokenUtil;
use App\Base\Utils\ArrayUtil;
use App\Entity\TaskCategory;
use App\Entity\Task;


class AppUserTaskManager
{

    /**
     * @var $container
     */
    protected $container;

    /**
     * $em
     */
    private $em;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container){
        $this->container = $container;
        $this->em = $this->container->get('doctrine.orm.entity_manager');
    }


    public function createTask($request,$user)
    {

        $task = new Task();
        // needs to create new user
        $task->setTitle($request->get('title'));
        if($request->has('location')){
            $task->setLocation($request->get('location'));
        }
        $task->setTaskPrice($request->get('task_price'));
        $task->setTaskStatus('open');
        if($request->has('due_date')){
            $task->setDueDate(new \DateTime($request->get('due_date')));
        }
        if($request->has('due_time')){
            $task->setDueTime(new \DateTime($request->get('due_time')));
        }
        if($request->has('zip_date')){
            $task->setZipCode($request->get('zip_date'));
        }
        $task->setUser($user);
        $task->setCreatedDate(new \DateTime('now'));

        if($request->has('category_id')){
            $category = $this->em->getRepository(TaskCategory::class)->find($request->get('category_id'));
            $task->setCategory($category);
        }
        $this->em->persist($task);
        $this->em->flush();

        return array(
            'code' => Response::HTTP_OK,
            'message' => sprintf("A user task created successfully")
        );
    }

    private function getTaskById($task_id){
        $sqlString = sprintf("SELECT * FROM task WHERE id=%d",$task_id);
        $stmt = $this->em->getConnection()->executeQuery($sqlString);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


    public function getTaskByApplicantList($task_id){
        $sqlQuery = sprintf("SELECT u.id,u.first_name,u.last_name,u.profile_image,u.about,u.post_code FROM task_application ta INNER JOIN app_user u ON ta.user_id=u.id WHERE ta.task_id=%d",$task_id);
        $stmt = $this->em->getConnection()->executeQuery($sqlQuery);
        $resultArray = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return array(
            'task_detail' => $this->getTaskById($task_id),
            'applicants'  => $resultArray
        );
    }

    public function getPosterTaskList($query,$user_id){
        $stmt = $this->em->getConnection()->executeQuery($this->buildQuery($query,$user_id));
        $resultArray = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $resultArray;
    }


    public function buildQuery($query,$user_id){
        $sqlString = "SELECT t.id,t.zip_code,t.description,t.title,t.task_status,t.due_date,t.due_time,t.task_price,COUNT(ta.id) AS no_applicants FROM task t LEFT JOIN task_application ta ON t.id=ta.task_id WHERE";
        if($query->has('type')){
            $sqlString .= sprintf(" (%s) AND",$this->getTaskStatusFlag($query->get('type')));
        }
        if($query->has('category_id')){
            $sqlString .= sprintf(" t.category_id=%d AND",$query->get('category_id'));
        }
        if($query->has('zip_code')){
            $sqlString .= sprintf(" t.zip_code LIKE '%s' AND",$query->get('zip_code'));
        }
        $sqlString .= sprintf(" t.user_id=%d AND t.id IS NOT NULL GROUP BY t.id",$user_id);
        return $sqlString;
    }

    private function getTaskStatusFlag($type){
        if($type == "active"){
            return "t.task_status='open' OR t.task_status='awaiting' OR t.task_status='in_progress'";
        }
        return "t.task_status='done' OR t.task_status='un_active'";
    }


    public function makeOfferForATask($request){

        $user = $this->em->getRepository(AppUser::class)->find($request->get('assignee_id'));
        $task = $this->em->getRepository(Task::class)->find($request->get('task_id'));
        if("object" == gettype($user) && "object" == gettype($task)){
            $taskApplication = $this->em->getRepository(TaskApplication::class)->findOneBy([
                'user' => $user,
                'task' => $task,
            ]);
            if("object" == gettype($taskApplication)){
                $taskApplication->setIsOffered(TRUE);
                $this->em->persist($taskApplication);

                $task->setTaskStatus('awaiting');
                $this->em->persist($taskApplication);
                $this->em->flush();
                return true;
            }
        }
        return false;
    }







}//@