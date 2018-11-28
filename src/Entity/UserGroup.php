<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Group;
use App\Entity\AppUser;


/**
 * @ORM\Entity(repositoryClass="App\Repository\UserGroupRepository")
 */
class UserGroup
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var AppUser $user
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\AppUser", inversedBy="userGroups",fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;

    /**
     * @var Group $group
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Group", inversedBy="userGroups",fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id", nullable=false)
     */
    private $group;


    public function getId()
    {
        return $this->id;
    }

    /**
     * Set User $user
     *
     * @param AppUser $user
     *
     * @return UserGroup
     */
    public function setUser(AppUser $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return AppUser
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set group
     *
     * @param Group $group
     *
     * @return UserGroup
     */
    public function setGroup(Group $group)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * Get group
     *
     * @return Groups
     */
    public function getGroup()
    {
        return $this->group;
    }

}//@
