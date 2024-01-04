<?php

declare(strict_types=1);

namespace Soukicz\Zbozicz\Factories;

use Soukicz\Zbozicz\Entities\CartItem;

/**
 * Class CartItemFactory
 *
 * @package Soukicz\Zbozicz\Factories
 *
 * @since 2.0
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
final class CartItemFactory
{
    private ?int $quantity = null;
    private string $id = '';
    private string $name = '';
    private ?float $price = null;

    public function create(): CartItem
    {
        $item           = new CartItem($this->id, $this->name, $this->price, $this->quantity);
        $this->quantity = null;
        $this->id       = '';
        $this->name     = '';
        $this->price    = null;

        return $item;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function setUnitPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }
}
