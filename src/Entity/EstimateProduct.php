<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EstimateProductRepository")
 */
class EstimateProduct
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=160, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quantity;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="json_array", length=160, nullable=true)
     */
    private $taxes;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Estimate", inversedBy="productEstimates",fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="estimate_id", referencedColumnName="id")
     */
    private $estimate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="productEstimates",fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;

    public function getId()
    {
        return $this->id;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    public function setEstimate(Estimate $estimate)
    {
        $this->estimate = $estimate;

        return $this;
    }

    public function getEstimate()
    {
        return $this->estimate;
    }

    public function setTaxes($taxes)
    {
        $this->taxes = $taxes;

        return $this;
    }

    public function getTaxes()
    {
        return $this->taxes;
    }

    public function setProduct(Product $product)
    {
        $this->product = $product;

        return $this;
    }

    public function getProduct()
    {
        return $this->product;
    }

}//@
