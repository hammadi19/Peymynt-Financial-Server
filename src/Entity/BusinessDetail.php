<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BusinessDetailRepository")
 */
class BusinessDetail
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $address_line1;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $address_line2;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $province;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $zip_code;

    /**
     * @ORM\Column(type="string", length=40, nullable=true)
     */
    private $time_zone;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $mobile;

    /**
     * @ORM\Column(type="string", length=40, nullable=true)
     */
    private $toll_free;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $website;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Business", inversedBy="businessDetails",fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="business_id", referencedColumnName="id")
     */
    private $business;

    /**
     * set start ups
     */
    public function __construct(){
        $this->updated_date             = new \DateTime('now');
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAddressLine1()
    {
        return $this->address_line1;
    }

    public function setAddressLine1(string $address_line1)
    {
        $this->address_line1 = $address_line1;

        return $this;
    }

    public function getAddressLine2()
    {
        return $this->address_line2;
    }

    public function setAddressLine2(string $address_line2)
    {
        $this->address_line2 = $address_line2;

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

    public function getProvince()
    {
        return $this->province;
    }

    public function setProvince(string $province)
    {
        $this->province = $province;

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

    public function getTimeZone()
    {
        return $this->time_zone;
    }

    public function setTimeZone(string $time_zone)
    {
        $this->time_zone = $time_zone;

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

    public function getUpdatedDate()
    {
        return $this->updated_date;
    }

    public function setUpdatedDate(\DateTimeInterface $updated_date)
    {
        $this->updated_date = $updated_date;

        return $this;
    }

    /**
     * Set business
     *
     * @param Business $business
     *
     * @return this
     */
    public function setBusiness(Business $business)
    {
        $this->business = $business;

        return $this;
    }

    /**
     * Get business
     *
     * @return Business
     */
    public function getBusiness()
    {
        return $this->Business;
    }
}
