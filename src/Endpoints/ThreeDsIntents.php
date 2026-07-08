<?php

declare(strict_types=1);

namespace Frame\Endpoints;

use Frame\Client;

/**
 * Canonical 3DS intents endpoint (resource `threeDsIntents`, per
 * CROSS_SDK_NAMING.md — `DS` is in the acronym registry). The legacy `ThreeDS`
 * class is retained as a deprecated subclass alias for backward compatibility.
 */
class ThreeDsIntents
{
    private const BASE_PATH = '/v1/3ds/intents';

    public function create(array $params): array
    {
        return Client::post(self::BASE_PATH, $params);
    }

    public function retrieve(string $id): array
    {
        return Client::get(self::BASE_PATH . "/{$id}");
    }

    public function resend(string $id): array
    {
        return Client::post(self::BASE_PATH . "/{$id}/resend", []);
    }
}
