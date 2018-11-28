<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EstimateRepository")
 */
class Estimate
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $estimate_no;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $estimate_date;

    /**
     * @ORM\Column(type="datetime")
     */
    private $expire_date;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $sub_heading;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $footer;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $memo;

    /**
     * @ORM\Column(type="string", length=70, nullable=true)
     */
    private $currency;

    /**
     * @ORM\Column(type="string", length=70, nullable=true)
     */
    private $po_so;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $total_amount;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Business", inversedBy="products",fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="business_id", referencedColumnName="id")
     */
    private $business;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Customer", inversedBy="estimates",fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
    private $customer;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\EstimateProduct", mappedBy="estimate", fetch="EXTRA_LAZY")
     */
    private $productEstimates;

    /**
     * set start ups
     */
    public function __construct(){
        //$this->created_date             = new \DateTime('now');
        $this->productEstimates          = new ArrayCollection();
    }


    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;

        return $this;
    }

    public function getEstimateNo()
    {
        return $this->estimate_no;
    }

    public function setEstimateNo(string $estimate_no)
    {
        $this->estimate_no = $estimate_no;

        return $this;
    }

    public function getEstimateDate()
    {
        return $this->estimate_date;
    }

    public function setEstimateDate($estimate_date)
    {
        $this->estimate_date = $estimate_date;

        return $this;
    }

    public function getExpireDate()
    {
        return $this->expire_date;
    }

    public function setExpireDate($expire_date)
    {
        $this->expire_date = $expire_date;

        return $this;
    }

    public function getSubHeading()
    {
        return $this->sub_heading;
    }

    public function setSubHeading($sub_heading)
    {
        $this->sub_heading = $sub_heading;

        return $this;
    }

    public function getFooter()
    {
        return $this->footer;
    }

    public function setFooter($footer)
    {
        $this->footer = $footer;

        return $this;
    }

    public function getMemo()
    {
        return $this->memo;
    }

    public function setMemo($memo)
    {
        $this->memo = $memo;

        return $this;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    public function getPoSo()
    {
        return $this->po_so;
    }

    public function setPoSo($po_so)
    {
        $this->po_so = $po_so;

        return $this;
    }


    public function getTotalAmount()
    {
        return $this->total_amount;
    }

    public function setTotalAmount($total_amount)
    {
        $this->total_amount = $total_amount;

        return $this;
    }



    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;

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

    public function setCustomer(Customer $customer)
    {
        $this->customer = $customer;

        return $this;
    }

    public function getCustomer()
    {
        return $this->customer;
    }

}//@
