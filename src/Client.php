<?php

declare(strict_types=1);

namespace Soukicz\Zbozicz;

use Composer\CaBundle\CaBundle;
use GuzzleHttp\Psr7\Request;
use Soukicz\Zbozicz\Entities\Order;
use Soukicz\Zbozicz\Exceptions\InvalidClientConfigException;
use Soukicz\Zbozicz\Exceptions\IOException;
use Soukicz\Zbozicz\Exceptions\UnknownException;
use function assert;
use function is_string;
use const CURLINFO_HTTP_CODE;
use const CURLOPT_CAINFO;
use const CURLOPT_CAPATH;
use const CURLOPT_HEADER;
use const CURLOPT_HTTPHEADER;
use const CURLOPT_POST;
use const CURLOPT_POSTFIELDS;
use const CURLOPT_RETURNTRANSFER;
use const CURLOPT_SSL_VERIFYPEER;
use const CURLOPT_URL;

final class Client
{
    private string $shopId;

    private string $privateKey;

    private bool $sandbox;

    /**
     * @param string $shopId
     * @param string $privateKey
     * @param bool $sandbox
     *
     * @phpstan-return ($shopId is non-empty-string ? ($privateKey is non-empty-string ? void : never) : never)
     */
    public function __construct(string $shopId, string $privateKey, bool $sandbox = false)
    {
        if ($shopId === '') {
            throw new InvalidClientConfigException('Shop ID must not be empty');
        }

        if ($privateKey === '') {
            throw new InvalidClientConfigException('Private key must not be empty');
        }

        $this->shopId     = $shopId;
        $this->privateKey = $privateKey;
        $this->sandbox    = $sandbox;
    }

    public function isSandbox(): bool
    {
        return $this->sandbox;
    }

    public function createRequest(Order $order): Request
    {
        $data                = $order->toArray();
        $data['PRIVATE_KEY'] = $this->privateKey;
        $data['sandbox']     = $this->isSandbox();
        $data                = json_encode($data);

        if ($data === false) {
            throw new UnknownException('Something went wrong');
        }

        return new Request(
            'POST',
            $this->getUrl(),
            ['Content-type' => 'application/json'],
            $data
        );
    }

    // @phpcs:ignore SlevomatCodingStandard.Complexity.Cognitive.ComplexityTooHigh
    public function sendOrder(Order $order): void
    {
        $request = $this->createRequest($order);
        $ch      = curl_init();
        $headers = [];

        foreach (array_keys($request->getHeaders()) as $name) {
            $headers[$name] = $request->getHeaderLine($name);
        }

        curl_setopt($ch, CURLOPT_URL, $this->getUrl());
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, (string) $request->getBody());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if (class_exists('Composer\CaBundle\CaBundle')) {
            $caPathOrFile = CaBundle::getSystemCaRootBundlePath();

            curl_setopt(
                $ch,
                // @phpstan-ignore-next-line
                is_dir($caPathOrFile) || (is_link($caPathOrFile) && is_dir(readlink($caPathOrFile)))
                    ? CURLOPT_CAPATH
                    : CURLOPT_CAINFO,
                $caPathOrFile
            );
        }

        $result = curl_exec($ch);
        assert(is_string($result) || $result === false);

        if ($result === false) {
            throw new IOException(
                'Unable to establish connection to ZboziKonverze service: curl error ('
                . curl_errno($ch) . ') - ' . curl_error($ch)
            );
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($httpCode === 200) {
            return;
        }

        $data = json_decode($result, true);

        if (is_array($data) && key_exists('statusMessage', $data)) {
            throw new IOException('Request was not accepted HTTP ' . $httpCode . ': ' . $data['statusMessage']);
        }

        throw new IOException('Request was not accepted (HTTP ' . $httpCode . ')');
    }

    protected function getUrl(): string
    {
        return strtr(
            'https://{domain}/action/{shopId}/conversion/backend',
            [
                '{domain}' => $this->isSandbox() ? 'sandbox.zbozi.cz' : 'www.zbozi.cz',
                '{shopId}' => $this->shopId,
            ]
        );
    }
}
