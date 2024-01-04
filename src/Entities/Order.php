<?php

declare(strict_types=1);

namespace Soukicz\Zbozicz\Entities;

use ValueError;

/**
 * Class Order
 *
 * @package Soukicz\Zbozicz\Entities
 *
 * @since 0.1
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
final class Order
{
    /** @var non-empty-string */
    private readonly string $id;

    private readonly ?string $email;

    private readonly ?string $deliveryType;

    private readonly ?string $paymentType;

    private readonly ?float $deliveryPrice;

    private readonly ?float $otherCost;

    /** @var array<\Soukicz\Zbozicz\Entities\CartItem> */
    private readonly array $items;

    /**
     * @param array<\Soukicz\Zbozicz\Entities\CartItem> $items
     */
    public function __construct(
        string $id,
        array $items,
        ?string $email,
        ?string $deliveryType,
        ?string $paymentType,
        ?float $deliveryPrice,
        ?float $otherCost
    )
    {
        if ($id === '') {
            throw new ValueError('Argument #1 ($id) must be of type non-empty-string, empty string given.');
        }

        $this->id            = $id;
        $this->items         = $items;
        $this->email         = $email;
        $this->deliveryType  = $deliveryType;
        $this->paymentType   = $paymentType;
        $this->deliveryPrice = $deliveryPrice;
        $this->otherCost     = $otherCost;
    }

    /**
     * @return non-empty-string
     */
    public function getId(): string
    {
        return $this->id;
    }

    public function getDeliveryPrice(): ?float
    {
        return $this->deliveryPrice;
    }

    public function getDeliveryType(): ?string
    {
        return $this->deliveryType;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return array<\Soukicz\Zbozicz\Entities\CartItem>
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function getOtherCost(): ?float
    {
        return $this->otherCost;
    }

    public function getPaymentType(): ?string
    {
        return $this->paymentType;
    }

    /**
     * @return array<string, string|int|float|array<int|string|float>>
     */
    public function toArray(): array
    {
        // @phpstan-ignore-next-line
        return array_filter([
            'orderId'       => $this->id,
            'email'         => $this->email,
            'deliveryType'  => $this->deliveryType,
            'paymentType'   => $this->paymentType,
            'deliveryPrice' => $this->deliveryPrice,
            'otherCosts'    => $this->otherCost,
            'cart'          => array_map(static fn (CartItem $item): array => $item->toArray(), $this->items),
        ], static fn (array|string|float|null $value): bool => $value !== null);
    }
}
