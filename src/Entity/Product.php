<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Business;
use App\Entity\AppUser;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_sell;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $income_account;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_buy;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $expense_account;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $sales_tax;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Business", inversedBy="products",fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="business_id", referencedColumnName="id",nullable=true)
     */
    private $business;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\EstimateProduct", mappedBy="product", fetch="EXTRA_LAZY")
     */
    private $productEstimates;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tax", inversedBy="products",fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="tax_id", referencedColumnName="id", nullable=true)
     */
    private $tax;

    /**
     * set start ups
     */
    public function __construct(){
        $this->productEstimates          = new ArrayCollection();
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

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice(float $price)
    {
        $this->price = $price;

        return $this;
    }

    public function getIsSell()
    {
        return $this->is_sell;
    }

    public function setIsSell(bool $is_sell)
    {
        $this->is_sell = $is_sell;

        return $this;
    }

    public function getIsBuy()
    {
        return $this->is_buy;
    }

    public function setIsBuy(bool $is_buy)
    {
        $this->is_buy = $is_buy;

        return $this;
    }

    public function setBusiness($business)
    {
        $this->business = $business;

        return $this;
    }

    public function getBusiness()
    {
        return $this->business;
    }

    public function setIncomeAccount(string $income_account)
    {
        $this->income_account = $income_account;

        return $this;
    }

    public function getIncomeAccount()
    {
        return $this->income_account;
    }

    public function setExpenseAccount(string $expense_account)
    {
        $this->expense_account = $expense_account;

        return $this;
    }

    public function getExpenseAccount()
    {
        return $this->expense_account;
    }

     public function setSalesTax(?float $sales_tax)
    {
        $this->sales_tax = $sales_tax;

        return $this;
    }

    public function getSalesTax()
    {
        return $this->sales_tax;
    }

    public function setTax(Tax $tax)
    {
        $this->tax = $tax;

        return $this;
    }

    public function getTax()
    {
        return $this->tax;
    }
}
