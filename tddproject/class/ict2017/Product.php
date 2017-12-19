<?php

class Product
{

    private $id;
    private $name;
    private $type; // default, standard etc
    private $unit; // kg, kpl, min, pcs
    private $price; // without tax
    private $amount;
    private $tax; // usually 24%
    private $timestamp;
    private $description;

    public function __construct($id, $name, $type, $unit, $price, $amount, $tax, $timestamp, $description = null)
    {
        if ($id == null || $id <= 0) {
            throw new Exception('Product ID should be greater than 0.');
        }

        $this->id          = $id;
        $this->name        = $name;
        $this->type        = $type;
        $this->unit        = $unit;
        $this->price       = $price;
        $this->amount      = $amount;
        $this->tax         = $tax;
        $this->timestamp   = $timestamp;
        $this->description = $description;
    }

    public function getProductId()
    {
        return $this->id;
    }

    public function getProductName()
    {
        return $this->name;
    }

    public function getProductType()
    {
        return $this->type;
    }

    public function getProductUnit()
    {
        return $this->unit;
    }

    public function getProductPrice()
    {
        return $this->price;
    }

    public function getProductAmount()
    {
        return $this->amount;
    }

    public function getProductTax()
    {
        return $this->tax;
    }

    public function getProductTimestamp()
    {
        return $this->timestamp;
    }

    public function getProductDescription()
    {
        return $this->description;
    }
}