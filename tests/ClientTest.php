<?php

declare(strict_types=1);

namespace Soukicz\TestZbozicz;

use Closure;
use PHPUnit\Framework\TestCase;
use Soukicz\Zbozicz\Client;
use Soukicz\Zbozicz\Entities\Order;
use Soukicz\Zbozicz\Factories\CartItemFactory;
use Soukicz\Zbozicz\Factories\OrderFactory;

final class ClientTest extends TestCase {
    /**
     * @small
     *
     * @coversNothing
     *
     * @param array $expectedRequestData
     * @param \Closure $createClient
     * @param \Closure $createOrder
     *
     * @return void
     *
     * @dataProvider dataToTest
     */
    public function testOrderItems(array $expectedRequestData, Closure $createClient, Closure $createOrder): void
    {
        $client  = $createClient();
        $order   = $createOrder();
        $request = $client->createRequest($order);

        $this->assertEquals('POST', $request->getMethod());

        $data = json_decode((string) $request->getBody(), true);

        ksort($data);
        ksort($expectedRequestData);

        $this->assertSame($expectedRequestData, $data);
        $this->assertSame([
            'Host'         => ['sandbox.zbozi.cz'],
            'Content-type' => ['application/json'],
        ], $request->getHeaders());

        $this->assertEquals(
            'https://sandbox.zbozi.cz/action/1c342b11e6f1fc2c10242127ea2cacc8/conversion/backend',
            (string) $request->getUri()
        );
    }

    public static function dataToTest(): array
    {
        return [
            'default' => [
                [
                    'PRIVATE_KEY'   => '6caf7fe67a300047c72496969f637a9c',
                    'sandbox'       => true,
                    'orderId'       => '1234',
                    'email'         => 'info@example.org',
                    'deliveryType'  => 'PPL',
                    'deliveryPrice' => 30,
                    'otherCosts'    => 10,
                    'cart'          => [
                        [
                            'itemId'      => 'ABC1',
                            'productName' => 'Product ABC',
                            'unitPrice'   => 1000,
                            'quantity'    => 2,
                        ],
                        [
                            'itemId'      => 'ABC2',
                            'productName' => '',
                            'unitPrice'   => 2000,
                        ],
                        [
                            'itemId'      => 'ABC3',
                            'productName' => '',
                            'unitPrice'   => 0,
                        ],
                    ],
                ],
                static fn (): Client => new Client(
                    '1c342b11e6f1fc2c10242127ea2cacc8',
                    '6caf7fe67a300047c72496969f637a9c',
                    true
                ),
                static function(): Order {
                    $factory     = (new OrderFactory())
                        ->setId('1234')
                        ->setDeliveryType('PPL')
                        ->setDeliveryPrice(30)
                        ->setOtherCost(10)
                        ->setEmail('info@example.org');
                    $itemFactory = new CartItemFactory();
                    $items       = [
                        $itemFactory->setId('ABC1')
                            ->setName('Product ABC')
                            ->setUnitPrice(1000)
                            ->setQuantity(2)
                            ->create(),
                        $itemFactory->setId('ABC2')
                            ->setUnitPrice(2000)
                            ->create(),
                        $itemFactory->setId('ABC3')
                            ->setUnitPrice(0)
                            ->create(),
                    ];

                    foreach ($items as $item) {
                        $factory->addItem($item);
                    }

                    return $factory->create();
                },
            ],
        ];
    }
}
