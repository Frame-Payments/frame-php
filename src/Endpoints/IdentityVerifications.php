<?php

declare(strict_types=1);

namespace Frame\Endpoints;

use Frame\Models\IdentityVerifications\CustomerIdentity;
use Frame\Models\IdentityVerifications\IdentityCreateRequest;

/**
 * @deprecated Use {@see CustomerIdentityVerifications} (canonical resource
 *   `customerIdentityVerifications`). Retained as a thin alias for backward
 *   compatibility; removed at v2. Methods forward to the canonical class; they
 *   are re-declared (rather than purely inherited) so the surface manifest
 *   reflects the same operation set under either class name.
 */
final class IdentityVerifications extends CustomerIdentityVerifications
{
    public function create(IdentityCreateRequest $params): CustomerIdentity
    {
        return parent::create($params);
    }

    public function createForCustomer(string $customerId, array $params = []): CustomerIdentity
    {
        return parent::createForCustomer($customerId, $params);
    }

    public function retrieve(string $id): CustomerIdentity
    {
        return parent::retrieve($id);
    }

    public function uploadDocuments(string $id, array $params): array
    {
        return parent::uploadDocuments($id, $params);
    }
}
