<?php

declare(strict_types=1);

namespace FAPI\Sylius\V2\Api;

use FAPI\Sylius\Api\HttpApi;
use FAPI\Sylius\Api\Product\Variant;
use FAPI\Sylius\Exception;
use FAPI\Sylius\V2\Model\Product\ProductModelCollection;
use Psr\Http\Message\ResponseInterface;

final class ProductApi extends HttpApi
{
    public function variant(): Variant
    {
        return new Variant($this->httpClient, $this->hydrator, $this->requestBuilder);
    }

    /**
     * @return Model|ResponseInterface
     *
     * @throws Exception
     */
    public function get(string $productCode)
    {
        $response = $this->httpGet('/api/v2/products/' . $productCode);
        if (!$this->hydrator) {
            return $response;
        }

        // Use any valid status code here
        if (200 !== $response->getStatusCode()) {
            $this->handleErrors($response);
        }

        return $this->hydrator->hydrate($response, Model::class);
    }

    /**
     * {@link https://docs.sylius.com/en/1.3/api/products.html#creating-a-product}.
     *
     * @return Model|ResponseInterface
     *
     * @throws Exception
     */
    public function create(string $productCode, array $params = [])
    {
        $params['code'] = $productCode;
        $response = $this->httpPost('/api/v1/products/', $params);
        if (!$this->hydrator) {
            return $response;
        }

        // Use any valid status code here
        if (201 !== $response->getStatusCode()) {
            $this->handleErrors($response);
        }

        return $this->hydrator->hydrate($response, Model::class);
    }

    /**
     * Update a product partially.
     *
     * {@link https://docs.sylius.com/en/1.3/api/products.html#id14}
     *
     * @return ResponseInterface|void
     *
     * @throws Exception
     */
    public function update(string $productCode, array $params = [])
    {
        $response = $this->httpPatch('/api/v1/products/' . $productCode, $params);
        if (!$this->hydrator) {
            return $response;
        }

        // Use any valid status code here
        if (204 !== $response->getStatusCode()) {
            $this->handleErrors($response);
        }
    }

    public function getAll(array $params = [], $access = 'shop')
    {
        $path = sprintf('/api/v2/%s/products', $access);
        $response = $this->httpGet($path, $params);

        if (!$this->hydrator) {
            return $response;
        }

        if (200 !== $response->getStatusCode()) {
            $this->handleErrors($response);
        }

        return $this->hydrator->hydrate($response, ProductModelCollection::class);
    }
}
