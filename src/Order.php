<?php
namespace Soukicz\Zbozicz;

class Order {
    /**
     * @var string
     */
    protected $id, $email;

    /**
     * @var string
     */
    protected $deliveryType, $paymentType;

    /**
     * @var \DateTime|null
     */
    protected $deliveryDate;
    /**
     * @var float
     */
    protected $deliveryPrice, $otherCosts, $totalPrice;

    /**
     * @var CartItem[]
     */
    protected $cartItems = [];

    function __construct($id) {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId() {
        return $this->id;
    }


    /**
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @param string $email
     * @return Order
     */
    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getDeliveryType() {
        return $this->deliveryType;
    }

    /**
     * @param string $deliveryType
     * @return Order
     */
    public function setDeliveryType($deliveryType) {
        $this->deliveryType = $deliveryType;
        return $this;
    }

    /**
     * @return string
     */
    public function getPaymentType() {
        return $this->paymentType;
    }

    /**
     * @param string $paymentType
     * @return Order
     */
    public function setPaymentType($paymentType) {
        $this->paymentType = $paymentType;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getDeliveryDate() {
        return $this->deliveryDate;
    }

    /**
     * @param \DateTime $deliveryDate
     * @return Order
     */
    public function setDeliveryDate(\DateTime $deliveryDate) {
        $this->deliveryDate = $deliveryDate;
        return $this;
    }

    /**
     * @return float
     */
    public function getDeliveryPrice() {
        return $this->deliveryPrice;
    }

    /**
     * @param float $deliveryPrice
     * @return Order
     */
    public function setDeliveryPrice($deliveryPrice) {
        $this->deliveryPrice = $deliveryPrice;
        return $this;
    }

    /**
     * @return float
     */
    public function getOtherCosts() {
        return $this->otherCosts;
    }

    /**
     * @param float $otherCosts
     * @return Order
     */
    public function setOtherCosts($otherCosts) {
        $this->otherCosts = $otherCosts;
        return $this;
    }

    /**
     * @return float
     */
    public function getTotalPrice() {
        return $this->totalPrice;
    }

    /**
     * @param float $totalPrice
     * @return Order
     */
    public function setTotalPrice($totalPrice) {
        $this->totalPrice = $totalPrice;
        return $this;
    }

    /**
     * @return CartItem[]
     */
    public function getCartItems() {
        return $this->cartItems;
    }

    /**
     * @param CartItem[] $cartItems
     * @return Order
     */
    public function setCartItems(array $cartItems) {
        $this->cartItems = [];
        foreach ($cartItems as $cartItem) {
            $this->addCartItem($cartItem);
        }
        return $this;
    }

    public function addCartItem(CartItem $cartItem) {
        $this->cartItems[] = $cartItem;
        return $this;
    }
}
