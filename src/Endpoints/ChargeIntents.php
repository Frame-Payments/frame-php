<?php

declare(strict_types=1);

namespace Frame\Endpoints;

use Frame\Client;
use Frame\Models\ChargeIntents\ChargeIntent;
use Frame\Models\ChargeIntents\ChargeIntentCreateRequest;
use Frame\Models\ChargeIntents\ChargeIntentListResponse;
use Frame\Models\ChargeIntents\ChargeIntentUpdateRequest;

final class ChargeIntents
{
    private const BASE_PATH = '/v1/charge_intents';

    /**
     * @deprecated Use {@see \Frame\Endpoints\Transfers::create()} instead. Removed at v2.
     */
    public function create(ChargeIntentCreateRequest $params): ChargeIntent
    {
        $json = Client::post(self::BASE_PATH, $params->toArray());

        return ChargeIntent::fromArray($json);
    }

    /**
     * @deprecated Removed at v2. No canonical transfer equivalent yet (FRA-4463).
     */
    public function update(string $id, ChargeIntentUpdateRequest $params): ChargeIntent
    {
        $json = Client::update(self::BASE_PATH . "/{$id}", $params->toArray());

        return ChargeIntent::fromArray($json);
    }

    /**
     * @deprecated Use {@see \Frame\Endpoints\Transfers::retrieve()} instead. Removed at v2.
     */
    public function retrieve(string $id): ChargeIntent
    {
        $json = Client::get(self::BASE_PATH . "/{$id}");

        return ChargeIntent::fromArray($json);
    }

    /**
     * @deprecated Use {@see \Frame\Endpoints\Transfers::list()} instead. Removed at v2.
     */
    public function list(int $perPage = 10, int $page = 1): ChargeIntentListResponse
    {
        $json = Client::get(self::BASE_PATH, ['per_page' => $perPage, 'page' => $page]);

        return ChargeIntentListResponse::fromArray($json);
    }

    /**
     * @deprecated Use {@see \Frame\Endpoints\Transfers::confirm()} instead. Removed at v2.
     */
    public function confirm(string $id): ChargeIntent
    {
        $json = Client::post(self::BASE_PATH . "/{$id}/confirm", []);

        return ChargeIntent::fromArray($json);
    }

    /**
     * @deprecated Removed at v2. No canonical transfer equivalent yet (FRA-4463).
     */
    public function capture(string $id): ChargeIntent
    {
        $json = Client::post(self::BASE_PATH . "/{$id}/capture", []);

        return ChargeIntent::fromArray($json);
    }

    /**
     * @deprecated Removed at v2. No canonical transfer equivalent yet (FRA-4463).
     */
    public function cancel(string $id): ChargeIntent
    {
        $json = Client::post(self::BASE_PATH . "/{$id}/cancel", []);

        return ChargeIntent::fromArray($json);
    }

    /**
     * @deprecated Removed at v2. No canonical transfer equivalent yet (FRA-4463).
     */
    public function voidRemaining(string $id): ChargeIntent
    {
        $json = Client::post(self::BASE_PATH . "/{$id}/void_remaining", []);

        return ChargeIntent::fromArray($json);
    }
}
