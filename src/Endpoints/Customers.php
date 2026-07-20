<?php

declare(strict_types=1);

namespace Frame\Endpoints;

use Frame\Client;
use Frame\Models\Customers\Customer;
use Frame\Models\Customers\CustomerCreateRequest;
use Frame\Models\Customers\CustomerListResponse;
use Frame\Models\Customers\CustomerSearchRequest;
use Frame\Models\Customers\CustomerUpdateRequest;
use Frame\Models\PaymentMethods\PaymentMethodListResponse;

final class Customers
{
    private const BASE_PATH = '/v1/customers';

    public function create(CustomerCreateRequest $params): Customer
    {
        $json = Client::post(self::BASE_PATH, $params->toArray());

        return Customer::fromArray($json);
    }

    public function update(string $id, CustomerUpdateRequest $params): Customer
    {
        $json = Client::update(self::BASE_PATH . "/{$id}", $params->toArray());

        return Customer::fromArray($json);
    }

    public function retrieve(string $id): Customer
    {
        $json = Client::get(self::BASE_PATH . "/{$id}");

        return Customer::fromArray($json);
    }

    /**
     * @deprecated Use {@see \Frame\Endpoints\Accounts::disable()} instead. Removed at v2.
     */
    public function delete(string $id): Customer
    {
        $json = Client::delete(self::BASE_PATH . "/{$id}");

        return Customer::fromArray($json);
    }

    public function list(int $perPage = 10, int $page = 1): CustomerListResponse
    {
        $json = Client::get(self::BASE_PATH, ['per_page' => $perPage, 'page' => $page]);

        return CustomerListResponse::fromArray($json);
    }

    public function search(CustomerSearchRequest $params): CustomerListResponse
    {
        $json = Client::get(self::BASE_PATH, $params->toArray());

        return CustomerListResponse::fromArray($json);
    }

    /**
     * @deprecated Future target: {@see \Frame\Endpoints\Accounts}::block() (pending a monolith route). Removed at v2.
     */
    public function block(string $id): Customer
    {
        $json = Client::post(self::BASE_PATH . "/{$id}/block");

        return Customer::fromArray($json);
    }

    /**
     * @deprecated Future target: {@see \Frame\Endpoints\Accounts}::unblock() (pending a monolith route). Removed at v2.
     */
    public function unblock(string $id): Customer
    {
        $json = Client::post(self::BASE_PATH . "/{$id}/unblock");

        return Customer::fromArray($json);
    }

    /**
     * @deprecated Use PaymentMethods::list(['account_id' => ...]) instead (FRA-4461). Removed at v2.
     */
    public function getPaymentMethods(string $id, int $perPage = 10, int $page = 1): PaymentMethodListResponse
    {
        $json = Client::get(self::BASE_PATH . "/{$id}/payment_methods", ['per_page' => $perPage, 'page' => $page]);

        return PaymentMethodListResponse::fromArray($json);
    }
}
