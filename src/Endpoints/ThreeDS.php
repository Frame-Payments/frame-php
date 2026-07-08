<?php

declare(strict_types=1);

namespace Frame\Endpoints;

/**
 * @deprecated Use {@see ThreeDsIntents} (canonical resource `threeDsIntents`).
 *   Retained as a thin alias for backward compatibility; removed at v2. Methods
 *   forward to the canonical class; they are re-declared (rather than purely
 *   inherited) so the surface manifest reflects the same operation set under
 *   either class name.
 */
final class ThreeDS extends ThreeDsIntents
{
    public function create(array $params): array
    {
        return parent::create($params);
    }

    public function retrieve(string $id): array
    {
        return parent::retrieve($id);
    }

    public function resend(string $id): array
    {
        return parent::resend($id);
    }
}
