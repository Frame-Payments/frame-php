<?php

declare(strict_types=1);

namespace Frame\Endpoints;

use Frame\Client;

final class OnboardingSessions
{
    private const BASE_PATH = '/v1/onboarding_sessions';

    public function create(array $params): array
    {
        return Client::post(self::BASE_PATH, $params);
    }

    public function list(string $accountId): array
    {
        return Client::get(self::BASE_PATH, ['account_id' => $accountId]);
    }
}
