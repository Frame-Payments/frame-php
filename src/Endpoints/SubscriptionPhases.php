<?php
declare(strict_types=1);
namespace Frame\Endpoints;

use Frame\Client;
use Frame\Models\SubscriptionPhases\SubscriptionPhase;
use Frame\Models\SubscriptionPhases\PhaseCreateRequest;
use Frame\Models\SubscriptionPhases\PhaseUpdateRequest;
use Frame\Models\SubscriptionPhases\PhaseBulkUpdateRequest;
use Frame\Models\SubscriptionPhases\PhaseListResponse;
use frame\Models\Invoices\InvoiceDeletedResponse;

final class SubscriptionPhases {
    private const BASE_PATH = '/v1/subscriptions';

    public function list(string $subscriptionId): PhaseListResponse {
        $json  = Client::get(self::BASE_PATH . "/{$subscriptionId}/phases/");
        return PhaseListResponse::fromArray($json);
    }

    public function retrieve(string $subscriptionId, string $phaseId): SubscriptionPhase {
        $json = Client::get(self::BASE_PATH . "/{$subscriptionId}/phases/{$phaseId}");
        return SubscriptionPhase::fromArray($json);
    }

    public function create(string $subscriptionId, PhaseCreateRequest $params): SubscriptionPhase {
        $json = Client::create(self::BASE_PATH  . "/{$subscriptionId}/phases/", $params->toArray());
        return SubscriptionPhase::fromArray($json);
    }

    public function update(string $subscriptionId, string $phaseId, PhaseUpdateRequest $params): SubscriptionPhase {
        $json = Client::update(self::BASE_PATH . "/{$subscriptionId}/phases/{$phaseId}", $params->toArray());
        return SubscriptionPhase::fromArray($json);
    }

    public function delete(string $subscriptionId, string $phaseId): InvoiceDeletedResponse {
        $json = Client::delete(self::BASE_PATH . "/{$subscriptionId}/phases/{$phaseId}");
        return InvoiceDeletedResponse::fromArray($json);
    }

    public function bulkUpdate(string $subscriptionId, array $phases): PhaseListResponse {
        foreach ($phases as $i => $p) {
            if (!$p instanceof PhaseBulkUpdateRequest) {
                throw new \InvalidArgumentException("phases[$i] must be PhaseBulkUpdateRequest");
            }
        }

        $payload = array_map(static fn(PhaseUpdateRequest $p) => $p->toArray(), $phases);

        $json = Client::update(self::BASE_PATH . "/{$subscriptionId}/phases/bulk_update", ['phases' => $payload]);
        return PhaseListResponse::fromArray($json);
    }
}