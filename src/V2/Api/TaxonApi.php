<?php

declare(strict_types=1);

namespace FAPI\Sylius\V2\Api;

use FAPI\Sylius\Api\HttpApi;
use FAPI\Sylius\Exception;
use FAPI\Sylius\V2\Model\TaxonModel;
use FAPI\Sylius\v2\Model\TaxonModelCollection;
use Psr\Http\Message\ResponseInterface;

final class TaxonApi extends HttpApi
{
    /**
     * {@link https://docs.sylius.com/en/1.9/api/admin_api/taxons.html}.
     *
     * @param string $access
     *
     * @return ResponseInterface
     */
    public function getAll(array $params = [], $access = 'shop')
    {
        $path = sprintf('/api/v2/%s/taxons', $access);
        $response = $this->httpGet($path, $params);

        if (!$this->hydrator) {
            return $response;
        }

        return $this->hydrator->hydrate($response, TaxonModelCollection::class);
    }

    /**
     * @return ResponseInterface
     *
     * @throws Exception\DomainException
     */
    public function get(string $code)
    {
        $response = $this->httpGet(\sprintf('/api/v2/taxons/%s', $code));
        if (!$this->hydrator) {
            return $response;
        }

        // Use any valid status code here
        if (200 !== $response->getStatusCode()) {
            $this->handleErrors($response);
        }

        return $this->hydrator->hydrate($response, TaxonModel::class);
    }

    /**
     * @return ResponseInterface
     *
     * @throws Exception\DomainException
     */
    public function create(string $code, array $params = [])
    {
        $params = $this->validateAndGetParams($code, $params);
        $response = $this->httpPost('/api/v1/taxons/', $params);
        if (!$this->hydrator) {
            return $response;
        }

        // Use any valid status code here
        if (201 !== $response->getStatusCode()) {
            $this->handleErrors($response);
        }

        return $this->hydrator->hydrate($response, TaxonModel::class);
    }

    /**
     * Update a taxon partially.
     *
     * {@link https://docs.sylius.com/en/1.4/api/taxons.html#updating-taxon}
     *
     * @throws Exception
     *
     * @return ResponseInterface|void
     */
    public function update(string $code, array $params = [])
    {
        $params = $this->validateAndGetParams($code, $params);
        $response = $this->httpPatch(\sprintf('/api/v1/taxons/%s', $code), $params);
        if (!$this->hydrator) {
            return $response;
        }

        // Use any valid status code here
        if (204 !== $response->getStatusCode()) {
            $this->handleErrors($response);
        }
    }

    /**
     * @return ResponseInterface|void
     *
     * @throws Exception\DomainException
     */
    public function delete(string $code)
    {
        $response = $this->httpDelete(\sprintf('/api/v1/taxons/%s', $code));
        if (!$this->hydrator) {
            return $response;
        }

        // Use any valid status code here
        if (204 !== $response->getStatusCode()) {
            $this->handleErrors($response);
        }
    }

    private function validateAndGetParams(string $code, array $optionalParams): array
    {
        if (empty($code)) {
            throw new Exception\InvalidArgumentException('Code cannot be empty');
        }

        $params = \array_merge([
            'code' => $code,
        ], $optionalParams);

        return $params;
    }
}
