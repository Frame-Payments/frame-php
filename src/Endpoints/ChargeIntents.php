<?php
declare(strict_types=1);
namespace Frame\Endpoints;


use Frame\Client;
use Frame\Models\ChargeIntents\ChargeIntent;
use Frame\Models\ChargeIntents\ChargeIntentCreateRequest;
use Frame\Models\ChargeIntents\ChargeIntentListResponse;
use Frame\Models\ChargeIntents\ChargeIntentUpdateRequest;

final class ChargeIntents {
    private string $basePath = '/v1/charge_intents';

    public function create(ChargeIntentCreateRequest $params): ChargeIntent
    {
        $json = Client::create($this->basePath, $params);
        return ChargeIntent::fromArray($json);
    }

    public function update(string $id, ChargeIntentUpdateRequest $params): ChargeIntent {
        $json = Client::update("{$this->basePath}/{$id}", $params);
        return ChargeIntent::fromArray($json);
    }

    public function retrieve(string $id): ChargeIntent {
        $json = Client::get("{$this->basePath}/{$id}");
        return ChargeIntent::fromArray($json);
    }

    // TODO: Fix this to be a ChargeIntentListResponse
    public function list(int $perPage = 10, int $page = 1): array {
        $json  = Client::get($this->basePath, ['per_page' => $perPage, 'page' => $page]);
        $data  = isset($json['data']) && is_array($json['data']) ? $json['data'] : [];
        $items = array_map(
            fn(array $i) => ChargeIntentListResponse::fromArray($i),
            $data
        );
        return $items;
    }

    public function confirm(string $id, array $params = []): ChargeIntent {
        $json = Client::create("{$this->basePath}/{$id}/confirm", $params);
        return ChargeIntent::fromArray($json);
    }

    public function capture(string $id, array $params = []): ChargeIntent {
        $json = Client::create("{$this->basePath}/{$id}/capture", $params);
        return ChargeIntent::fromArray($json);
    }

    public function cancel(string $id, array $params = []): ChargeIntent {
        $json = Client::create("{$this->basePath}/{$id}/cancel", $params);
        return ChargeIntent::fromArray($json);
    }
}