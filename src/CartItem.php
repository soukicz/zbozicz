<?php
namespace Soukicz\Zbozicz;

class CartItem {
    /**
     * @var string
     */
    protected $id, $name;

    /**
     * @var float|NULL
     */
    protected $unitPrice;
    /**
     * @var int
     */
    protected $quantity = 1;

    /**
     * @return string
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param string $id
     * @return CartItem
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param string $name
     * @return CartItem
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * @return float|NULL
     */
    public function getUnitPrice() {
        return $this->unitPrice;
    }

    /**
     * @param float $unitPrice
     * @return CartItem
     */
    public function setUnitPrice($unitPrice) {
        $this->unitPrice = $unitPrice;
        return $this;
    }

    /**
     * @return int
     */
    public function getQuantity() {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     * @return CartItem
     */
    public function setQuantity($quantity) {
        $this->quantity = $quantity;
        return $this;
    }
}
