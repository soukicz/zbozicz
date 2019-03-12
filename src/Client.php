<?php
namespace Soukicz\Zbozicz;

use GuzzleHttp\Psr7\Request;

class Client {
    protected $shopId;
    protected $privateKey;
    protected $sandbox;

    function __construct($shopId, $privateKey, $sandbox = false) {
        if(empty($shopId)) {
            throw new ArgumentException('Missing "shopId"');
        }
        if(empty($privateKey)) {
            throw new ArgumentException('Missing "privateKey"');
        }
        $this->shopId = $shopId;
        $this->privateKey = $privateKey;
        $this->sandbox = $sandbox;
    }

    public function isSandbox() {
        return (bool)$this->sandbox;
    }

    /**
     * @param Order $order
     * @return Request
     */
    public function createRequest(Order $order) {
        $errors = $this->validateOrder($order);
        if(!empty($errors)) {
            throw new InputException($errors[0]);
        }
        $data = [
            'PRIVATE_KEY' => $this->privateKey,
            'sandbox' => $this->sandbox,
            'orderId' => $order->getId(),
            'email' => $order->getEmail(),
        ];

        if($order->getDeliveryType()) {
            $data['deliveryType'] = $order->getDeliveryType();
        }

        if($order->getDeliveryPrice()) {
            $data['deliveryPrice'] = $order->getDeliveryPrice();
        }

        if($order->getPaymentType()) {
            $data['paymentType'] = $order->getPaymentType();
        }

        if($order->getOtherCosts()) {
            $data['otherCosts'] = $order->getOtherCosts();
        }

        if(!empty($order->getCartItems())) {
            $data['cart'] = [];
            foreach ($order->getCartItems() as $cartItem) {
                $item = [];
                if(!empty($cartItem->getId())) {
                    $item['itemId'] = $cartItem->getId();
                }
                if(!empty($cartItem->getName())) {
                    $item['productName'] = $cartItem->getName();
                }
                if(!empty($cartItem->getUnitPrice())) {
                    $item['unitPrice'] = $cartItem->getUnitPrice();
                }
                if(!empty($cartItem->getQuantity())) {
                    $item['quantity'] = $cartItem->getQuantity();
                }
                $data['cart'][] = $item;
            }
        }

        return new Request(
            'POST',
            $this->getUrl(),
            ['Content-type' => 'application/json'],
            json_encode($data)
        );
    }

    public function sendOrder(Order $order) {
        $request = $this->createRequest($order);
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->getUrl());
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        $headers = [];
        foreach ($request->getHeaders() as $name => $lines) {
            $headers[$name] = $request->getHeaderLine($name);
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, (string)$request->getBody());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if(class_exists('Composer\CaBundle\CaBundle')) {
            $caPathOrFile = \Composer\CaBundle\CaBundle::getSystemCaRootBundlePath();
            if(is_dir($caPathOrFile) || (is_link($caPathOrFile) && is_dir(readlink($caPathOrFile)))) {
                curl_setopt($ch, CURLOPT_CAPATH, $caPathOrFile);
            } else {
                curl_setopt($ch, CURLOPT_CAINFO, $caPathOrFile);
            }
        }

        $result = curl_exec($ch);

        if($result === false) {
            throw new IOException('Unable to establish connection to ZboziKonverze service: curl error (' . curl_errno($ch) . ') - ' . curl_error($ch));
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if($httpCode !== 200) {
            $data = json_decode($result, true);
            if($data && !empty($data['statusMessage'])) {
                throw new IOException('Request was not accepted HTTP ' . $httpCode . ': ' . $data['statusMessage']);
            }
            throw new IOException('Request was not accepted (HTTP ' . $httpCode . ')');
        }
    }

    protected function getUrl() {
        $url = 'https://' . ($this->sandbox ? 'sandbox.zbozi.cz' : 'www.zbozi.cz');

        return $url . '/action/' . $this->shopId . '/conversion/backend';
    }

    /**
     * @param Order $order
     * @return array
     */
    public function validateOrder(Order $order) {
        $errors = [];
        if(empty($order->getId())) {
            $errors[] = 'Missing order code';
        }
        return $errors;
    }
}
