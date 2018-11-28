<?php

namespace App\Service;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\AppUser;
use App\Entity\UserTask;
use App\Entity\Task;

class VendorManager
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager    = $entityManager;
    }

    public function assign($request){

        $assignee = $this->entityManager->getReference('App\Entity\AppUser', $request->get('assignee_id'));
        $task     = $this->entityManager->getRepository(Task::class)->find($request->get('task_id'));
        $userTask = new UserTask();
        $userTask->setAssignedDate(new \DateTime('now'));
        $userTask->setAssignee($assignee);
        $userTask->setStatus('assigned');
        $userTask->setTask($task);
        $this->entityManager->persist($userTask);

        $task->setTaskStatus('assigned');
        $this->entityManager->persist($task);
        $this->entityManager->flush();
    }

}