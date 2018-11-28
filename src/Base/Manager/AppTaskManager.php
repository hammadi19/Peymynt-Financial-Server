<?php

namespace App\Base\Manager;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Base\Utils\TokenUtil;
use App\Base\Utils\ArrayUtil;
use App\Entity\Task;
use App\Entity\AppUser;
use App\Entity\TaskApplication;

class AppTaskManager
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


    public function getTaskList($query,$user_id){
        $allApplyTasks = $this->getAllApplyOpenTasks($user_id);
        //print_r($allApplyTasks);
        $stmt = $this->em->getConnection()->executeQuery($this->buildQuery($query));
        $resultArray = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        if(count($allApplyTasks) > 0 && count($resultArray) > 0){
             array_walk($resultArray,array($this, 'isTaskApply'),$allApplyTasks);
        }
        return $resultArray;
    }


    public function isTaskApply(&$row,$key,$allApplyTasks){
        if(in_array($row['id'],$allApplyTasks)){
            $row['is_apply'] = true;
        }else{
            $row['is_apply'] = false;
        }
    }

    public function buildQuery($query){
        $sqlString = "SELECT t.id,t.zip_code,t.description,t.title,t.task_status,t.due_date,t.due_time,t.created_date,t.task_price,COUNT(ta.id) AS no_applicants FROM task t LEFT JOIN task_application ta ON t.id=ta.task_id WHERE";
        if($query->has('category_id')){
            $sqlString .= sprintf(" t.category_id=%d AND",$query->get('category_id'));
        }
        if($query->has('zip_code')){
            $sqlString .= sprintf(" t.zip_code LIKE '%s' AND",$query->get('zip_code'));
        }
        $sqlString .= " t.id IS NOT NULL GROUP BY t.id";
        return $sqlString;
    }

    public function getAllApplyOpenTasks($user_id){
        $stmt = $this->em->getConnection()->executeQuery(sprintf("SELECT task_id FROM task_application WHERE user_id=%d",$user_id));
        $resultArray = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        if(count($resultArray) > 0){
            return array_column($resultArray,'task_id');
        }
        return array();
    }


    public function applyForTask($request){
        $user = $this->em->getRepository(AppUser::class)->find($request->get('user_id'));
        $task = $this->em->getRepository(Task::class)->find($request->get('task_id'));
        if("object" === gettype($user) && "object" === gettype($task)){
            $taskBid = new TaskApplication();
            $taskBid->setUser($user);
            $taskBid->setTask($task);
            $this->em->persist($taskBid);
            $this->em->flush();
            return array(
                'code' => Response::HTTP_OK,
                'message' => sprintf("Apply for task successfully")
            );
        }
        return array(
            'code' => Response::HTTP_NOT_ACCEPTABLE,
            'message' => sprintf("Error in apply for task")
        );
    }


    public function acceptOfferForATask($request,$user_id){

        $user = $this->em->getRepository(AppUser::class)->find($user_id);
        $task = $this->em->getRepository(Task::class)->find($request->get('task_id'));
        if("object" == gettype($user) && "object" == gettype($task)){
            $taskApplication = $this->em->getRepository(TaskApplication::class)->findOneBy([
                'user' => $user,
                'task' => $task,
            ]);
            if("object" == gettype($taskApplication)){
                $taskApplication->setIsAccepted(TRUE);
                $this->em->persist($taskApplication);

                $task->setTaskStatus('in_progress');
                $this->em->persist($taskApplication);
                $this->em->flush();
                return true;
            }
        }
        return false;
    }



}//@