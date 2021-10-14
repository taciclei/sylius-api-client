<?php

declare(strict_types=1);

namespace FAPI\Sylius\V2\Api;

use FAPI\Sylius\Exception;
use FAPI\Sylius\Exception\InvalidArgumentException;
use FAPI\Sylius\Model\Checkout\PaymentCollection;
use FAPI\Sylius\Model\Checkout\ShipmentCollection;
use Psr\Http\Message\ResponseInterface;

final class Checkout extends HttpApi
{
    public const SHIPPING_ADDRESS_FIELDS = [
        'firstName',
        'lastName',
        'city',
        'postcode',
        'street',
        'countryCode',
    ];

    /**
     * @throws Exception
     *
     * @return ResponseInterface|void
     */
    public function updateAddress(int $cartId, array $shippingAddress, bool $differentBillingAddress = false, array $billingAddress = [])
    {
        if (empty($cartId)) {
            throw new InvalidArgumentException('Cart id cannot be empty');
        }

        if (empty($shippingAddress)) {
            throw new InvalidArgumentException('Shipping address cannot be empty');
        }

        foreach (self::SHIPPING_ADDRESS_FIELDS as $field) {
            if (empty($shippingAddress[$field])) {
                throw new InvalidArgumentException(\sprintf('Field "%s" missing in shipping address', $field));
            }
        }

        $params = [
            'shippingAddress' => $shippingAddress,
            'differentBillingAddress' => $differentBillingAddress,
            'billingAddress' => $billingAddress,
        ];

        $response = $this->httpPut('/api/v1/checkouts/addressing/' . $cartId, $params);
        if (!$this->hydrator) {
            return $response;
        }

        // Use any valid status code here
        if (204 !== $response->getStatusCode()) {
            $this->handleErrors($response);
        }
    }

    /**
     * @throws Exception
     *
     * @return ResponseInterface|void
     */
    public function updatePaymentMethod(int $cartId, array $params = [])
    {
        if (empty($cartId)) {
            throw new InvalidArgumentException('Cart id cannot be empty');
        }

        $response = $this->httpPut('/api/v1/checkouts/select-payment/' . $cartId, $params);
        if (!$this->hydrator) {
            return $response;
        }

        // Use any valid status code here
        if (204 !== $response->getStatusCode()) {
            $this->handleErrors($response);
        }
    }

    /**
     * @throws Exception
     *
     * @return ResponseInterface|void
     */
    public function complete(int $cartId)
    {
        if (empty($cartId)) {
            throw new InvalidArgumentException('Cart id cannot be empty');
        }

        $response = $this->httpPut('/api/v1/checkouts/complete/' . $cartId);
        if (!$this->hydrator) {
            return $response;
        }

        // Use any valid status code here
        if (204 !== $response->getStatusCode()) {
            $this->handleErrors($response);
        }
    }

    /**
     * @throws Exception
     *
     * @return ResponseInterface|ShipmentCollection
     */
    public function getShippingMethods(int $cartId)
    {
        if (empty($cartId)) {
            throw new InvalidArgumentException('Cart id cannot be empty');
        }

        $response = $this->httpGet('/api/v1/checkouts/select-shipping/' . $cartId);
        if (!$this->hydrator) {
            return $response;
        }

        // Use any valid status code here
        if (200 !== $response->getStatusCode()) {
            $this->handleErrors($response);
        }

        return $this->hydrator->hydrate($response, ShipmentCollection::class);
    }

    /**
     * @throws Exception
     *
     * @return PaymentCollection|ResponseInterface
     */
    public function getPaymentMethods(int $cartId)
    {
        if (empty($cartId)) {
            throw new InvalidArgumentException('Cart id cannot be empty');
        }

        $response = $this->httpGet('/api/v1/checkouts/select-payment/' . $cartId);
        if (!$this->hydrator) {
            return $response;
        }

        // Use any valid status code here
        if (200 !== $response->getStatusCode()) {
            $this->handleErrors($response);
        }

        return $this->hydrator->hydrate($response, PaymentCollection::class);
    }
}
