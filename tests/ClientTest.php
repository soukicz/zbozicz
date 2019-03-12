<?php
namespace Soukicz\TestZbozicz;

use Soukicz\Zbozicz\CartItem;
use Soukicz\Zbozicz\Client;
use Soukicz\Zbozicz\Order;

class ClientTest extends \PHPUnit_Framework_TestCase {
    function testOrderItems() {
        $client = new Client('1c342b11e6f1fc2c10242127ea2cacc8', '6caf7fe67a300047c72496969f637a9c', true);
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

        $this->assertSame([
            'PRIVATE_KEY' => '6caf7fe67a300047c72496969f637a9c',
            'sandbox' => true,
            'orderId' => 1234,
            'email' => 'info@example.org',
            'deliveryType' => 'PPL',
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
        $this->assertEquals('https://sandbox.zbozi.cz/action/1c342b11e6f1fc2c10242127ea2cacc8/conversion/backend', (string)$request->getUri());


    }
}
