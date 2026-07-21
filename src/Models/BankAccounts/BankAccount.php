<?php

declare(strict_types=1);

namespace Frame\Models\BankAccounts;

final class BankAccount implements \JsonSerializable
{
    public function __construct(
        public readonly string $id,
        public readonly string $object,
        public readonly ?string $routingNumber,
        public readonly ?string $accountNumberLast4,
        public readonly ?string $accountType,
        public readonly ?string $bankName,
        public readonly ?string $processorName,
        public readonly ?string $status,
        public readonly ?string $customerId,
        public readonly ?string $accountId,
        public readonly int $created,
        public readonly ?int $updated,
        public readonly bool $livemode,
    ) {
    }

    public static function fromArray(array $p): self
    {
        return new self(
            id: $p['id'],
            object: $p['object'],
            routingNumber: $p['routing_number'] ?? null,
            accountNumberLast4: $p['account_number_last_4'] ?? null,
            accountType: $p['account_type'] ?? null,
            bankName: $p['bank_name'] ?? null,
            processorName: $p['processor_name'] ?? null,
            status: $p['status'] ?? null,
            customerId: $p['customer_id'] ?? null,
            accountId: $p['account_id'] ?? null,
            created: (int)$p['created'],
            updated: isset($p['updated']) ? (int)$p['updated'] : null,
            livemode: (bool)$p['livemode'],
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'object' => $this->object,
            'routing_number' => $this->routingNumber,
            'account_number_last_4' => $this->accountNumberLast4,
            'account_type' => $this->accountType,
            'bank_name' => $this->bankName,
            'processor_name' => $this->processorName,
            'status' => $this->status,
            'customer_id' => $this->customerId,
            'account_id' => $this->accountId,
            'created' => $this->created,
            'updated' => $this->updated,
            'livemode' => $this->livemode,
        ];
    }
}
