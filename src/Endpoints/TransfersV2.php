<?php

declare(strict_types=1);

namespace Frame\Endpoints;

use Frame\Client;

final class TransfersV2
{
    private const BASE_PATH = '/v2/transfers';

    public function list(int $perPage = 10, int $page = 1): array
    {
        return Client::get(self::BASE_PATH, ['per_page' => $perPage, 'page' => $page]);
    }

    public function retrieve(string $id): array
    {
        return Client::get(self::BASE_PATH . "/{$id}");
    }

    /**
     * Create a V2 transfer. Sends an `Idempotency-Key` header (UUID when omitted).
     *
     * @param array<string, mixed> $params
     */
    public function create(array $params, ?string $idempotencyKey = null): array
    {
        return Client::post(self::BASE_PATH, $params, self::idempotencyHeaders($idempotencyKey));
    }

    /**
     * @param array<string, mixed> $params
     */
    public function update(string $id, array $params): array
    {
        return Client::update(self::BASE_PATH . "/{$id}", $params);
    }

    /**
     * Confirm a transfer. Secret-key confirms send `Idempotency-Key` (UUID when omitted).
     * Pass a `client_secret` in `$params` for publishable confirm (no idempotency header).
     *
     * @param array<string, mixed> $params
     */
    public function confirm(string $id, array $params = [], ?string $idempotencyKey = null): array
    {
        $headers = isset($params['client_secret'])
            ? []
            : self::idempotencyHeaders($idempotencyKey);

        return Client::post(self::BASE_PATH . "/{$id}/confirm", $params, $headers);
    }

    /**
     * @param array<string, mixed> $params
     */
    public function capture(string $id, array $params = [], ?string $idempotencyKey = null): array
    {
        return Client::post(
            self::BASE_PATH . "/{$id}/capture",
            $params,
            self::idempotencyHeaders($idempotencyKey)
        );
    }

    public function void(string $id, ?string $idempotencyKey = null): array
    {
        return Client::post(
            self::BASE_PATH . "/{$id}/void",
            [],
            self::idempotencyHeaders($idempotencyKey)
        );
    }

    /**
     * @param array<string, mixed> $params
     */
    public function refund(string $id, array $params = [], ?string $idempotencyKey = null): array
    {
        return Client::post(
            self::BASE_PATH . "/{$id}/refund",
            $params,
            self::idempotencyHeaders($idempotencyKey)
        );
    }

    /**
     * @return array<string, string>
     */
    private static function idempotencyHeaders(?string $idempotencyKey): array
    {
        $key = ($idempotencyKey !== null && $idempotencyKey !== '')
            ? $idempotencyKey
            : self::generateUuid();

        return ['Idempotency-Key' => $key];
    }

    private static function generateUuid(): string
    {
        $data = random_bytes(16);
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}
