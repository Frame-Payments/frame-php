<?php

declare(strict_types=1);

namespace Frame\Endpoints;

use Frame\Client;

final class Wallets
{
    private const BASE_PATH = '/v2/wallets';

    public function list(int $perPage = 10, int $page = 1, ?string $accountId = null): array
    {
        $query = ['per_page' => $perPage, 'page' => $page];
        if ($accountId !== null && $accountId !== '') {
            $query['account_id'] = $accountId;
        }

        return Client::get(self::BASE_PATH, $query);
    }

    public function retrieve(string $id): array
    {
        return Client::get(self::BASE_PATH . "/{$id}");
    }

    public function retrieve_default(?string $accountId = null): array
    {
        $query = [];
        if ($accountId !== null && $accountId !== '') {
            $query['account_id'] = $accountId;
        }

        return Client::get(self::BASE_PATH . '/default', $query);
    }

    /**
     * Renames a wallet, replaces its metadata, or makes it the default.
     * Sends an `Idempotency-Key` header (UUID when omitted).
     *
     * @param array<string, mixed> $params
     */
    public function update(string $id, array $params, ?string $idempotencyKey = null): array
    {
        return Client::update(
            self::BASE_PATH . "/{$id}",
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
