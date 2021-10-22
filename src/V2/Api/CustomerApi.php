<?php

declare(strict_types=1);

namespace FAPI\Sylius\V2\Api;

use FAPI\Sylius\Exception;
use FAPI\Sylius\Exception\InvalidArgumentException;
use FAPI\Sylius\Model\Customer\Customer as Model;
use Psr\Http\Message\ResponseInterface;

final class CustomerApi extends HttpApi
{
    /**
     * @throws Exception
     *
     * @return Model|ResponseInterface
     */
    public function create(string $email, string $firstName, string $lastName, string $gender, $password, bool $subscribedToNewsletter= false, array $optionalParams = [])
    {
        $params = $this->validateAndGetParams($email, $firstName, $lastName, $gender, $password, $subscribedToNewsletter, $optionalParams);

        $response = $this->httpPost('/api/v2/shop/customers', $params);

        if (!$this->hydrator) {
            return $response;
        }

        // Use any valid status code here
        if (204 !== $response->getStatusCode()) {
            $this->handleErrors($response);
        }

        return $response;
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

    private function validateAndGetParams(string $email, string $firstName, string $lastName, string $gender, string $password, bool $subscribedToNewsletter, array $optionalParams): array
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

        if (empty($password)) {
            throw new InvalidArgumentException('password cannot be empty');
        }


        $params = \array_merge([
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $email,

            'password' => $password,
            'subscribedToNewsletter' => $subscribedToNewsletter
        ], $optionalParams);

        return $params;
    }
}
