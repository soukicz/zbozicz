<?php

declare(strict_types=1);

namespace Soukicz\Zbozicz\Entities;

/**
 * Class CartItem
 *
 * @package Soukicz\Zbozicz\Entities
 *
 * @since 0.1
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
final class CartItem
{
    protected readonly string $id;

    protected readonly string $name;

    protected readonly ?float $unitPrice;

    protected readonly ?int $quantity;

    public function __construct(string $id, string $name, ?float $unitPrice = null, ?int $quantity = null)
    {
        $this->id        = $id;
        $this->name      = $name;
        $this->unitPrice = $unitPrice;
        $this->quantity  = $quantity;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUnitPrice(): ?float
    {
        return $this->unitPrice;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    /**
     * @return array<string, int|string|float>
     */
    public function toArray(): array
    {
        return array_filter([
            'itemId'      => $this->id,
            'productName' => $this->name,
            'unitPrice'   => $this->unitPrice,
            'quantity'    => $this->quantity,
        ], static fn (string|int|float|null $value) => $value !== null);
    }
}
