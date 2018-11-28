<?php

namespace App\Base\Manager;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Base\Utils\TokenUtil;
use App\Base\Utils\ArrayUtil;
use App\Entity\TaskCategory;

class AppTaskCategoryManager
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


    public function getTaskCategoryList($query){
        //return $this->em->getRepository(TaskCategory::class)->findAll();
        $conn = $this->em->getConnection();
        $sql = 'SELECT * FROM task_category tc';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

}//@