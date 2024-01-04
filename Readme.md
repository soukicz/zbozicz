[![Build Status](https://travis-ci.org/soukicz/zbozicz.svg?branch=master)](https://travis-ci.org/soukicz/zbozicz)

# Pokročilé měření konverzí Zboží.cz

Vychází z https://github.com/seznam/zbozi-konverze, ale přidává lepší možnost integrace do větších systémů. Namespace, asynchronní odesílání objednávek atd.


## Odeslání objednávky
```php
use Soukicz\Zbozicz\Client;
use Soukicz\Zbozicz\Factories\CartItemFactory;
use Soukicz\Zbozicz\Factories\OrderFactory;

$client          = new \Soukicz\Zbozicz\Client(1234567890, "fedcba9876543210123456789abcdef", true);
$cartItemFactory = new \Soukicz\Zbozicz\Factories\CartItemFactory();
$orderFactory    = new \Soukicz\Zbozicz\Factories\OrderFactory();

$order = $orderFactory->setId('OBJ21101')
    ->setDeliveryType('PPL')
    ->setEmail('info@example.org')
    ->addItem(
        $cartItemFactory->setId('ABC1')
            ->setName('NAZEV PRODUKTU')
            ->setUnitPrice(1000)
            ->setQuantity(2)
            ->create()
    )
    ->addItem(
        $cartItemFactory->setId('ABC2')
            ->setName('NAZEV PRODUKTU')
            ->setUnitPrice(2000)
            ->create()
    )
    ->create();

$client->sendOrder($order);
```

## Paralelní odeslání objednávek
Je možné vytvořit si jen PSR-7 request a data následně odeslat například přes Guzzle. Lze tak jednoduše odesílat objednávky hromadně paralelně.

```php
/** @var \Soukicz\Zbozicz\Entities\Order[] $orders */
$orders      = [...];
$zboziClient = new \Soukicz\Zbozicz\Client(1234567890, "fedcba9876543210123456789abcdef", true);
$httpClient  = new \GuzzleHttp\Client();
$requests    = [];

foreach($orders as $order) {
    $requests[$order->geId()] = $client->createRequest($order);
}

new \GuzzleHttp\Client\Pool($httpClient, $requests, [
    'concurrency' => 5,
    'fulfilled'   => static function (\GuzzleHttp\Psr7\Response $response, string $index): void {
        echo "Order '$index' accepted\n";
    },
    'rejected'    => static function (string $reason, static $index): void {
        echo "Order '$index' not accepted: " . $reason . "\n";
    },
]);
```
