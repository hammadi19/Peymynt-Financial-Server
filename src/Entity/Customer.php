<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CustomerRepository")
 */
class Customer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=70)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $first_name;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $last_name;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $currency;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     */
    private $account_no;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=70, nullable=true)
     */
    private $fax;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $mobile;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $toll_free;

    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     */
    private $website;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $state;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $address_1;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $address_2;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $zip_code;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Business", inversedBy="customers",fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="business_id", referencedColumnName="id")
     */
    private $business;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Estimate", mappedBy="customer", fetch="EXTRA_LAZY")
     */
    private $estimates;

    /**
     * set start ups
     */
    public function __construct(){
        //$this->created_date             = new \DateTime('now');
        $this->estimates          = new ArrayCollection();
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

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;

        return $this;
    }

    public function getFirstName()
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name)
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName()
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name)
    {
        $this->last_name = $last_name;

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

    public function getAccountNo()
    {
        return $this->account_no;
    }

    public function setAccountNo(string $account_no)
    {
        $this->account_no = $account_no;

        return $this;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function setPhone(string $phone)
    {
        $this->phone = $phone;

        return $this;
    }

    public function getFax()
    {
        return $this->fax;
    }

    public function setFax(string $fax)
    {
        $this->fax = $fax;

        return $this;
    }

    public function getMobile()
    {
        return $this->mobile;
    }

    public function setMobile(string $mobile)
    {
        $this->mobile = $mobile;

        return $this;
    }

    public function getTollFree()
    {
        return $this->toll_free;
    }

    public function setTollFree(string $toll_free)
    {
        $this->toll_free = $toll_free;

        return $this;
    }

    public function getWebsite()
    {
        return $this->website;
    }

    public function setWebsite(string $website)
    {
        $this->website = $website;

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

    public function getState()
    {
        return $this->state;
    }

    public function setState(string $state)
    {
        $this->state = $state;

        return $this;
    }

    public function getAddress1()
    {
        return $this->address_1;
    }

    public function setAddress1(string $address_1)
    {
        $this->address_1 = $address_1;

        return $this;
    }

    public function getAddress2()
    {
        return $this->address_2;
    }

    public function setAddress2(string $address_2)
    {
        $this->address_2 = $address_2;

        return $this;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setCity(string $city)
    {
        $this->city = $city;

        return $this;
    }

    public function getZipCode()
    {
        return $this->zip_code;
    }

    public function setZipCode(string $zip_code)
    {
        $this->zip_code = $zip_code;

        return $this;
    }

    public function setBusiness(Business $business)
    {
        $this->business = $business;

        return $this;
    }

    public function getBusiness()
    {
        return $this->business;
    }
}
