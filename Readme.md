[![Build Status](https://travis-ci.org/soukicz/zbozicz.svg?branch=master)](https://travis-ci.org/soukicz/zbozicz)

# Pokročilé měření konverzí Zboží.cz

Vychází z https://github.com/seznam/zbozi-konverze, ale přidává lepší možnost integrace do větších systémů. Namespace, asynchronní odesílání objednávek atd.


## Odeslání objednávky
```php
<?php

use Soukicz\Zbozicz\Client;
use Soukicz\Zbozicz\Order;
use Soukicz\Zbozicz\CartItem;

$client = new Client(1234567890, "fedcba9876543210123456789abcdef", true);

$order = new Order('OBJ21101');
$order
    ->setEmail('info@example.org')
    ->setDeliveryType('PPL')
    ->addCartItem((new CartItem)
        ->setId('ABC1')
        ->setName('NAZEV PRODUKTU')
        ->setUnitPrice(1000)
        ->setQuantity(2)
    )
    ->addCartItem((new CartItem)
        ->setId('ABC2')
        ->setName('NAZEV PRODUKTU')
        ->setUnitPrice(2000)
    );

$client->sendOrder($order);
```

## Paralelní odeslání objednávek
Je možné vytvořit si jen PSR-7 request a data následně odeslat například přes Guzzle. Lze tak jednoduše odesílat objednávky hromadně paralelně.

```php
<?php
use Soukicz\Zbozicz\Client;
use Soukicz\Zbozicz\Order;
use Soukicz\Zbozicz\CartItem;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Client\Pool;

$client = new Client(1234567890, "fedcba9876543210123456789abcdef", true);
$requests = [];
foreach($orders as $order){
    $requests[$order->geId()] = $client->createRequest($order);
}

$httpClient = new \GuzzleHttp\Client();
$pool = new Pool($httpClient, $requests, [
    'concurrency' => 5,
    'fulfilled' => function (Response $response, $index) {
        echo "Order '$index' accepted\n";
    },
    'rejected' => function ($reason, $index) {
        echo "Order '$index' not accepted: " . $reason . "\n";
    },
]);
```
