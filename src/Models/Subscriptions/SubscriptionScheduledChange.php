<?php

declare(strict_types=1);

namespace Frame\Models\Subscriptions;

final class SubscriptionScheduledChange implements \JsonSerializable
{
    public function __construct(
        public readonly string $id,
        public readonly string $object,
        public readonly string $product,
        public readonly bool $intervalSwitch,
        public readonly int $effectiveDate,
        public readonly int $created
    ) {
    }

    public static function fromArray(array $p): self
    {
        return new self(
            id: $p['id'],
            object: $p['object'],
            product: $p['product'],
            intervalSwitch: (bool)$p['interval_switch'],
            effectiveDate: (int)$p['effective_date'],
            created: (int)$p['created']
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'object' => $this->object,
            'product' => $this->product,
            'interval_switch' => $this->intervalSwitch,
            'effective_date' => $this->effectiveDate,
            'created' => $this->created,
        ];
    }
}
