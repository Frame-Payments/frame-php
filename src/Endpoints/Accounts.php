<?php

declare(strict_types=1);

namespace Frame\Endpoints;

use Frame\Client;

final class Accounts
{
    private const BASE_PATH = '/v1/accounts';

    public function list(int $perPage = 10, int $page = 1): array
    {
        return Client::get(self::BASE_PATH, ['per_page' => $perPage, 'page' => $page]);
    }

    public function create(array $params): array
    {
        return Client::post(self::BASE_PATH, $params);
    }

    public function retrieve(string $id): array
    {
        return Client::get(self::BASE_PATH . "/{$id}");
    }

    public function update(string $id, array $params): array
    {
        return Client::update(self::BASE_PATH . "/{$id}", $params);
    }

    public function disable(string $id): array
    {
        return Client::delete(self::BASE_PATH . "/{$id}");
    }

    public function geoCompliance(string $id): array
    {
        return Client::get(self::BASE_PATH . "/{$id}/geo_compliance");
    }
}
