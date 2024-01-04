<?php

declare(strict_types=1);

namespace Soukicz\Zbozicz\Factories;

use Soukicz\Zbozicz\Entities\CartItem;
use Soukicz\Zbozicz\Entities\Order;
use Soukicz\Zbozicz\Exceptions\InvalidOrderIDException;

/**
 * Class OrderFactory
 *
 * @package Soukicz\Zbozicz\Factories
 *
 * @since 2.0
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
final class OrderFactory
{
    private ?string $id = null;

    private ?string $email = null;

    private ?string $deliveryType = null;

    private ?string $paymentType = null;

    private ?float $deliveryPrice = null;

    private ?float $otherCost = null;

    /** @var array<\Soukicz\Zbozicz\Entities\CartItem> */
    private array $items = [];

    public function create(): Order
    {
        if ($this->id === null) {
            throw new InvalidOrderIDException('Order ID must be set!');
        }

        return new Order(
            $this->id,
            $this->items,
            $this->email,
            $this->deliveryType,
            $this->paymentType,
            $this->deliveryPrice,
            $this->otherCost
        );
    }

    public function setId(string $id): self
    {
        if ($id === '') {
            throw new InvalidOrderIDException('Order ID must not be empty!');
        }

        $this->id = $id;

        return $this;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function setDeliveryType(?string $deliveryType): self
    {
        $this->deliveryType = $deliveryType;

        return $this;
    }

    public function setDeliveryPrice(?float $deliveryPrice): self
    {
        $this->deliveryPrice = $deliveryPrice;

        return $this;
    }

    public function setOtherCost(?float $otherCost): self
    {
        $this->otherCost = $otherCost;

        return $this;
    }

    public function setPaymentType(?string $paymentType): self
    {
        $this->paymentType = $paymentType;

        return $this;
    }

    public function addItem(CartItem $item): self
    {
        $this->items[] = $item;

        return $this;
    }
}
