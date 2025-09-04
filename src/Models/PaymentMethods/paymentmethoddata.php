<?php
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
    ){}

    public static function fromArray(array $p): self {
        return new self(
            type: $p['type'],
            cardNumber: $p['card_number'],
            expMonth: $p['exp_month'],
            expYear: $p['exp_year'],
            cvc: $p['cvc'],
            billing: isset($p['billing']) && is_array($p['billing']) ? Address::fromArray($p['billing']) : null
        );
    }

     public function jsonSerialize(): array {
        return [
            'type' => $this->type->value,
            'card_number' => $this->cardNumber,
            'exp_month' => $this->expMonth,
            'exp_year' => $this->expYear,
            'cvc' => $this->cvc,
            'billing' => $this->billing
        ];
    }
}