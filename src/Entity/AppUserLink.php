<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\AppUser;


/**
 * @ORM\Entity(repositoryClass="App\Repository\AppUserLinkRepository")
 */
class AppUserLink
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\AppUser", inversedBy="userLinks")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="hash_string", type="text")
     */
    private $hashString;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_date", type="datetime")
     */
    private $createdDate;


    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user
     *
     * @param AppUser $user
     *
     * @return UserBatchInfo
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
     * Set hashString
     *
     * @param string $hashString
     *
     * @return UserLinks
     */
    public function setHashString($hashString)
    {
        $this->hashString = $hashString;

        return $this;
    }

    /**
     * Get hashString
     *
     * @return string
     */
    public function getHashString()
    {
        return $this->hashString;
    }

    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     *
     * @return UserLinks
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    /**
     * Get createdDate
     *
     * @return \DateTime
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

}//@
