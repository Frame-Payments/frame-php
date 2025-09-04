<?php
namespace Frame\Models\ChargeIntents;

final class ChargeIntentUpdateRequest implements \JsonSerializable
{
    public function __construct(
        public readonly int $amount,
        public readonly ?string $customer = null,
        public readonly ?string $description = null,
        public readonly ?string $paymentMethod = null,
        /** @var array<string,string>|null */
        public readonly ?array $metadata = null
    ) {}

    public function jsonSerialize(): array
    {
        if ($this->amount !== null) $out['amount'] = $this->amount;
        if ($this->customer !== null) $out['customer'] = $this->customer;
        if ($this->metadata !== null) $out['metadata'] = $this->metadata;
        if ($this->description !== null) $out['description'] = $this->description;
        if ($this->paymentMethod !== null) $out['payment_method'] = $this->paymentMethod;

        return $out;
    }
}