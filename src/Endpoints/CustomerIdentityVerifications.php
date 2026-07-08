<?php

declare(strict_types=1);

namespace Frame\Endpoints;

use Frame\Client;
use Frame\Models\IdentityVerifications\CustomerIdentity;
use Frame\Models\IdentityVerifications\IdentityCreateRequest;

/**
 * Canonical customer identity verifications endpoint (resource
 * `customerIdentityVerifications`, per CROSS_SDK_NAMING.md). The legacy
 * `IdentityVerifications` class is retained as a deprecated subclass alias.
 */
class CustomerIdentityVerifications
{
    private const BASE_PATH = '/v1/customer_identity_verifications';

    public function create(IdentityCreateRequest $params): CustomerIdentity
    {
        $json = Client::post(self::BASE_PATH, $params->toArray());

        return CustomerIdentity::fromArray($json);
    }

    /**
     * Create an identity verification for an existing customer.
     * POST /v1/customer_identity_verifications/{customer_id}
     * (monolith customer_identity_verifications#create_from_customer).
     */
    public function createForCustomer(string $customerId, array $params = []): CustomerIdentity
    {
        $json = Client::post(self::BASE_PATH . "/{$customerId}", $params);

        return CustomerIdentity::fromArray($json);
    }

    public function retrieve(string $id): CustomerIdentity
    {
        $json = Client::get(self::BASE_PATH . "/{$id}");

        return CustomerIdentity::fromArray($json);
    }

    public function uploadDocuments(string $id, array $params): array
    {
        return Client::post(self::BASE_PATH . "/{$id}/upload_documents", $params);
    }
}
