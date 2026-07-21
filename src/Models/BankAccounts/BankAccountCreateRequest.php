<?php

declare(strict_types=1);

namespace Frame\Models\BankAccounts;

final class BankAccountCreateRequest implements \JsonSerializable
{
    public function __construct(
        public readonly string $processor,
        public readonly string $processorToken,
        public readonly ?string $customerId = null,
        public readonly ?string $accountId = null,
    ) {
    }

    public function toArray(): array
    {
        $payload = [
            'processor' => $this->processor,
            'processor_token' => $this->processorToken,
            'customer_id' => $this->customerId,
            'account_id' => $this->accountId,
        ];

        $filterNulls = fn ($v) => $v !== null;

        return array_filter($payload, $filterNulls);
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
