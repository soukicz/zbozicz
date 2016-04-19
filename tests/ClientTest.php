<?php
namespace Soukicz\TestZbozicz;

use Soukicz\Zbozicz\CartItem;
use Soukicz\Zbozicz\Client;
use Soukicz\Zbozicz\Order;

class ClientTest extends \PHPUnit_Framework_TestCase {
    function testOrderItems() {
        $client = new Client(1, 2, true);
        $order = new Order(1234);
        $order
            ->setEmail('info@example.org')
            ->setDeliveryType('PPL')
            ->addCartItem((new CartItem())
                ->setId('ABC1')
                ->setUnitPrice(1000)
                ->setQuantity(2)
            )
            ->addCartItem((new CartItem)
                ->setId('ABC2')
                ->setUnitPrice(2000)
            );

        $request = $client->createRequest($order);
        $this->assertEquals('POST', $request->getMethod());
        $data = json_decode($request->getBody(), true);

        $this->assertEquals([
            'PRIVATE_KEY' => 2,
            'sandbox' => 1,
            'orderId' => 1234,
            'email' => 'info@example.org',
            'deliveryType' => 'PPL',
            'totalPrice' => 4000,
            'cart' => [
                [
                    'itemId' => 'ABC1',
                    'unitPrice' => 1000,
                    'quantity' => 2,
                ],
                [
                    'itemId' => 'ABC2',
                    'unitPrice' => 2000,
                    'quantity' => 1,
                ]
            ]
        ], $data);
        $this->assertEquals('application/json', $request->getHeader('content-type')[0]);
        $this->assertEquals('https://sandbox.zbozi.cz/action/1/conversion/backend', (string)$request->getUri());
    }
}
