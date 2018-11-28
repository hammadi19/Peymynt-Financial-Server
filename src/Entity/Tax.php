<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * @ORM\Entity(repositoryClass="App\Repository\TaxRepository")
 */
class Tax
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
     * @ORM\Column(type="string", length=40)
     */
    private $abbreviation;

    /**
     * @ORM\Column(type="float")
     */
    private $tax_rate;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $tax_number;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_tax_recoverable;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_compound_tax;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_tax_no_show;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Business", inversedBy="products",fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="business_id", referencedColumnName="id")
     */
    private $business;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Product", mappedBy="tax", fetch="EXTRA_LAZY")
     */
    private $products;

    /**
     * set start ups
     */
    public function __construct(){
        $this->products                  = new ArrayCollection();
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

    public function getAbbreviation()
    {
        return $this->abbreviation;
    }

    public function setAbbreviation(string $abbreviation)
    {
        $this->abbreviation = $abbreviation;

        return $this;
    }

    public function getTaxRate()
    {
        return $this->tax_rate;
    }

    public function setTaxRate(float $tax_rate)
    {
        $this->tax_rate = $tax_rate;

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

    public function getTaxNumber()
    {
        return $this->tax_number;
    }

    public function setTaxNumber(string $tax_number)
    {
        $this->tax_number = $tax_number;

        return $this;
    }

    public function getIsTaxRecoverable()
    {
        return $this->is_tax_recoverable;
    }

    public function setIsTaxRecoverable(bool $is_tax_recoverable)
    {
        $this->is_tax_recoverable = $is_tax_recoverable;

        return $this;
    }

    public function getIsCompoundTax()
    {
        return $this->is_compound_tax;
    }

    public function setIsCompoundTax(bool $is_compound_tax)
    {
        $this->is_compound_tax = $is_compound_tax;

        return $this;
    }

    public function getIsTaxNoShow()
    {
        return $this->is_tax_no_show;
    }

    public function setIsTaxNoShow(bool $is_tax_no_show)
    {
        $this->is_tax_no_show = $is_tax_no_show;

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
