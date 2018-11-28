<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserEmailAccountRepository")
 */
class UserEmailAccount
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=160)
     */
    private $email;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_primary;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_confirmed;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $account_hash;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\AppUser", inversedBy="userEmailAccounts",fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * set start ups
     */
    public function __construct(){
        $this->created_date = new \DateTime('now');
    }

    public function getId()
    {
        return $this->id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;

        return $this;
    }

    public function getIsPrimary()
    {
        return $this->is_primary;
    }

    public function setIsPrimary(bool $is_primary)
    {
        $this->is_primary = $is_primary;

        return $this;
    }

    public function getIsConfirmed()
    {
        return $this->is_confirmed;
    }

    public function setIsConfirmed(bool $is_confirmed)
    {
        $this->is_confirmed = $is_confirmed;

        return $this;
    }

    public function getAccountHash()
    {
        return $this->account_hash;
    }

    public function setAccountHash(string $account_hash)
    {
        $this->account_hash = $account_hash;

        return $this;
    }

    public function getCreatedDate()
    {
        return $this->created_date;
    }

    public function setCreatedDate(\DateTimeInterface $created_date)
    {
        $this->created_date = $created_date;

        return $this;
    }

    /**
     * Set user
     *
     * @param AppUser $user
     *
     * @return this
     */
    public function setUser(AppUser $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return TaskCategory
     */
    public function getUser()
    {
        return $this->user;
    }


}
