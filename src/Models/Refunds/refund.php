<?php
declare(strict_types=1);
namespace Frame\Models\Refunds;

use Frame\Models\Refunds\RefundReason;
use Frame\Models\Refunds\RefundStatus;

final class Refund implements \JsonSerializable {
    public function __construct(
        public readonly string $id,
        public readonly string $currency,
        public readonly ?RefundStatus $status,
        public readonly int $amount,
        public readonly ?RefundReason $reason,
        public readonly ?string $chargeIntent,
        public readonly bool $livemode,
        public readonly int $created,
        public readonly ?int $updated,
        public readonly string $object
    ) {}

    public static function fromArray(array $p): self {
        return new self(
            id: $p['id'],
            currency: $p['currency'],
            status: isset($p['status']) ? RefundStatus::from($p['status']) : null,
            amount: (int)$p['amount'],
            reason: isset($p['reason']) ? RefundReason::from($p['reason']) : null,
            chargeIntent: isset($p['charge_intent']) ? $p['charge_intent'] : null,
            livemode: (bool)$p['livemode'],
            created: (int)$p['created'],
            updated: isset($p['updated']) ? (int)$p['updated'] : null,
            object: $p['object']
        );
    }

    public function jsonSerialize(): array {
        return [
            'id' => $this->id,
            'currency' => $this->currency,
            'status' => $this->status?->value,
            'amount' => $this->amount,
            'reason' => $this->reason?->value,
            'charge_intent' => $this->chargeIntent,
            'livemode' => $this->livemode,
            'created' => $this->created,
            'updated' => $this->updated,
            'object' => $this->object,
        ];
    }
}