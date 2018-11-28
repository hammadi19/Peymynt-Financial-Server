<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VendorRepository")
 */
class Vendor
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
     * @ORM\Column(type="string", length=150)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $first_name;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $last_name;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $currency;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $account_no;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=70)
     */
    private $fax;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $mobile;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $toll_free;

    /**
     * @ORM\Column(type="string", length=120)
     */
    private $website;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $state;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $address_1;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $address_2;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $zip_code;

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
}
