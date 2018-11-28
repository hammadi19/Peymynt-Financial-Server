<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\AppUser;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BusinessRepository")
 */
class Business
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=40,nullable=true)
     */
    private $business_type;

    /**
     * @ORM\Column(type="string", length=30 , nullable=true)
     */
    private $business_sub_type;

    /**
     * @ORM\Column(type="string", length=50,nullable=true)
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $currency;

    /**
     * @ORM\Column(type="string", length=40, nullable=true)
     */
    private $organization_type;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_primary;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_personal;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\AppUser", inversedBy="userTasks",fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BusinessDetail", mappedBy="business", fetch="EXTRA_LAZY")
     */
    private $businessDetails;
    
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Product", mappedBy="business", fetch="EXTRA_LAZY")
     */
    private $products;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Tax", mappedBy="business", fetch="EXTRA_LAZY")
     */
    private $taxes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Customer", mappedBy="business", fetch="EXTRA_LAZY")
     */
    private $customers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Estimate", mappedBy="business", fetch="EXTRA_LAZY")
     */
    private $estimates;

    /**
     * set start ups
     */
    public function __construct(){
        $this->created_date             = new \DateTime('now');
        $this->businessDetails          = new ArrayCollection();
        $this->products                 = new ArrayCollection();
        $this->taxes                    = new ArrayCollection();
        $this->customers                = new ArrayCollection();
        $this->estimates                = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    public function getBusinessType()
    {
        return $this->business_type;
    }

    public function setBusinessType(string $business_type)
    {
        $this->business_type = $business_type;

        return $this;
    }

    public function getBusinessSubType()
    {
        return $this->business_sub_type;
    }

    public function setBusinessSubType(string $business_sub_type)
    {
        $this->business_sub_type = $business_sub_type;

        return $this;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function setCountry(string $country)
    {
        $this->country = $country;

        return $this;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function setCurrency(string $currency)
    {
        $this->currency = $currency;

        return $this;
    }

    public function getOrganizationType()
    {
        return $this->organization_type;
    }

    public function setOrganizationType(string $organization_type)
    {
        $this->organization_type = $organization_type;

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

    public function getIsPersonal()
    {
        return $this->is_personal;
    }

    public function setIsPersonal(bool $is_personal)
    {
        $this->is_personal = $is_personal;

        return $this;
    }

    public function getCreatedDate()
    {
        return $this->created_date;
    }

    public function setCreatedDate($created_date)
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

}//@
