<?php
declare(strict_types=1);
namespace Frame\Models\Subscriptions;

final class SubscriptionSearchRequest implements \JsonSerializable
{
    public function __construct(
        public readonly SubscriptionStatus $status,
        public readonly int $createdAfter,
        public readonly int $createdBefore
    ){}

    public function toArray(): array
    {
        $payload = [
            'status' => $this->status->value,
            'created_after' => $this->createdAfter,
            'created_before' => $this->createdBefore
        ];

        $filterNulls = fn($v) => $v !== null;
        return array_filter($payload, $filterNulls);
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}