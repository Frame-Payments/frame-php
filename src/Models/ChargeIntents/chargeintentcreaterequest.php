<?php
declare(strict_types=1);
namespace Frame\Models\ChargeIntents;

use Frame\Models\PaymentMethods\PaymentMethodData;

final class ChargeIntentCreateRequest implements \JsonSerializable
{
    public function __construct(
        public readonly int $amount,
        public readonly string $currency,
        public readonly ?string $customer = null,
        public readonly ?bool $confirm = null,
        public readonly ?PaymentMethodData $paymentMethodData = null,
        /** @var array<string,string>|null */
        public readonly ?array $metadata = null,
        public readonly ?string $description = null,
        public readonly ?AuthorizationMode $authorizationMode = null,
    ) {
        if ($this->amount <= 0) {
            throw new \InvalidArgumentException('amount must be > 0');
        }
    }

    public function jsonSerialize(): array
    {
        $out = [
            'amount'   => $this->amount,
            'currency' => $this->currency,
        ];
        if ($this->customer !== null) $out['customer'] = $this->customer;
        if ($this->confirm !== null) $out['confirm'] = $this->confirm;
        if ($this->paymentMethodData !== null) $out['payment_method_data'] = $this->paymentMethodData;
        if ($this->metadata !== null) $out['metadata'] = $this->metadata;
        if ($this->description !== null) $out['description'] = $this->description;
        if ($this->authorizationMode !== null) $out['authorization_mode'] = $this->authorizationMode->value;

        return $out;
    }
}