<?php

declare(strict_types=1);

namespace FAPI\Sylius\V2\Api;

use FAPI\Sylius\Exception;
use FAPI\Sylius\Exception\InvalidArgumentException;
use FAPI\Sylius\Model\Customer\Customer as Model;
use Psr\Http\Message\ResponseInterface;

final class Customer extends HttpApi
{
    /**
     * @throws Exception
     *
     * @return Model|ResponseInterface
     */
    public function create(string $email, string $firstName, string $lastName, string $gender, array $optionalParams = [])
    {
        $params = $this->validateAndGetParams($email, $firstName, $lastName, $gender, $optionalParams);

        $response = $this->httpPost('/api/v1/customers/', $params);
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
     * @throws Exception
     *
     * @return ResponseInterface
     */
    public function update(int $id, string $email, string $firstName, string $lastName, string $gender, array $optionalParams = [])
    {
        $params = $this->validateAndGetParams($email, $firstName, $lastName, $gender, $optionalParams);

        $response = $this->httpPut('/api/v1/customers/' . $id, $params);

        // Use any valid status code here
        if (204 !== $response->getStatusCode()) {
            $this->handleErrors($response);
        }

        return $response;
    }

    private function validateAndGetParams(string $email, string $firstName, string $lastName, string $gender, array $optionalParams): array
    {
        if (empty($email)) {
            throw new InvalidArgumentException('Email cannot be empty');
        }

        if (empty($firstName)) {
            throw new InvalidArgumentException('First name cannot be empty');
        }

        if (empty($lastName)) {
            throw new InvalidArgumentException('Last name cannot be empty');
        }

        if (empty($gender)) {
            throw new InvalidArgumentException('Gender cannot be empty');
        }

        $params = \array_merge([
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $email,
            'gender' => $gender,
        ], $optionalParams);

        return $params;
    }
}
