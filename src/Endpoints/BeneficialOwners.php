<?php

declare(strict_types=1);

namespace Frame\Endpoints;

use Frame\Client;

final class BeneficialOwners
{
    public function list(string $accountId): array
    {
        return Client::get("/v1/accounts/{$accountId}/beneficial_owners");
    }

    public function create(string $accountId, array $params): array
    {
        return Client::post("/v1/accounts/{$accountId}/beneficial_owners", $params);
    }

    public function retrieve(string $accountId, string $id): array
    {
        return Client::get("/v1/accounts/{$accountId}/beneficial_owners/{$id}");
    }

    public function update(string $accountId, string $id, array $params): array
    {
        return Client::update("/v1/accounts/{$accountId}/beneficial_owners/{$id}", $params);
    }

    public function delete(string $accountId, string $id): array
    {
        // Endpoint returns 204 No Content; normalize the empty body to [].
        return Client::delete("/v1/accounts/{$accountId}/beneficial_owners/{$id}") ?? [];
    }

    public function confirmRoster(string $accountId): array
    {
        return Client::post("/v1/accounts/{$accountId}/beneficial_owners/confirm");
    }

    public function resendInvite(string $accountId, string $id): array
    {
        return Client::post("/v1/accounts/{$accountId}/beneficial_owners/{$id}/resend_invite");
    }
}
