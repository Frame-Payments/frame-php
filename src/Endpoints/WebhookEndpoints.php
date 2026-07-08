<?php

declare(strict_types=1);

namespace Frame\Endpoints;

use Frame\Client;

final class WebhookEndpoints
{
    private const BASE_PATH = '/v1/webhook_endpoints';

    public function list(int $perPage = 10, int $page = 1): array
    {
        return Client::get(self::BASE_PATH, ['per_page' => $perPage, 'page' => $page]);
    }

    /**
     * Create a webhook endpoint. The response includes the signing `secret` —
     * surface it to the merchant once; it is not returned again on
     * retrieve/list. Rotate to obtain a new one.
     *
     * @param array $params url (required), event_codes (required), description (optional)
     */
    public function create(array $params): array
    {
        return Client::post(self::BASE_PATH, $params);
    }

    public function retrieve(string $id): array
    {
        return Client::get(self::BASE_PATH . "/{$id}");
    }

    /**
     * @param array $params url, description, event_codes (all optional)
     */
    public function update(string $id, array $params): array
    {
        return Client::update(self::BASE_PATH . "/{$id}", $params);
    }

    public function delete(string $id): array
    {
        return Client::delete(self::BASE_PATH . "/{$id}");
    }

    /**
     * Rotate the signing secret. The new secret is returned once in the
     * response; the previous secret is immediately invalidated.
     */
    public function rotateSecret(string $id): array
    {
        return Client::post(self::BASE_PATH . "/{$id}/rotate_secret");
    }
}
