<?php
declare(strict_types=1);
namespace Frame\Models\PaymentMethods;

use Frame\Models\Customers\Address;

final class PaymentMethodData implements \JsonSerializable {
    public function __construct(
        public readonly PaymentMethodType $type,
        public readonly string $cardNumber,
        public readonly string $expMonth,
        public readonly string $expYear,
        public readonly string $cvc,
        public readonly ?Address $billing = null,
    ) {
        if (strlen($this->cardNumber) < 13 || strlen($this->cardNumber) > 19) {
            throw new \InvalidArgumentException('Card number must be between 13-19 digits');
        }
        
        if (!preg_match('/^\d{1,2}$/', $this->expMonth) || (int)$this->expMonth < 1 || (int)$this->expMonth > 12) {
            throw new \InvalidArgumentException('Expiry month must be 01-12');
        }
        
        if (!preg_match('/^\d{4}$/', $this->expYear) || (int)$this->expYear < date('Y')) {
            throw new \InvalidArgumentException('Expiry year must be current year or later');
        }
        
        if (strlen($this->cvc) < 3 || strlen($this->cvc) > 4 || !ctype_digit($this->cvc)) {
            throw new \InvalidArgumentException('CVC must be 3-4 digits');
        }
    }

    public function toArray(): array
    {
        $payload = [
            'type' => $this->type->value,
            'card_number' => $this->cardNumber,
            'exp_month' => $this->expMonth,
            'exp_year' => $this->expYear,
            'cvc' => $this->cvc,
            'billing' => $this->billing?->toArray()
        ];

        $filterNulls = fn($v) => $v !== null;
        return array_filter($payload, $filterNulls);
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}