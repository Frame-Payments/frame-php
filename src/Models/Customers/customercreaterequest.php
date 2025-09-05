<?php
namespace Frame\Models\Customers;

final class CustomerCreateRequest implements \JsonSerializable
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $description = null,
        public readonly string $email,
        public readonly ?string $phone,
        public readonly ?string $ssn,
        public readonly ?string $dateOfBirth,
        /** @var array<string,string>|null */
        public readonly ?array $metadata = null,
        public readonly ?Address $billingAddress,
        public readonly ?Address $shippingAddress,
    ){}

    public function jsonSerialize(): array
    {
        $out = [
            'name'   => $this->name,
            'email' => $this->email,
        ];
        if ($this->description !== null) $out['description'] = $this->description;
        if ($this->phone !== null) $out['phone'] = $this->phone;
        if ($this->ssn !== null) $out['ssn'] = $this->ssn;
        if ($this->dateOfBirth !== null) $out['date_of_birth'] = $this->dateOfBirth;
        if ($this->metadata !== null) $out['metadata'] = $this->metadata;
        if ($this->billingAddress !== null) $out['billing_address'] = $this->billingAddress;
        if ($this->shippingAddress !== null) $out['shipping_address'] = $this->shippingAddress;

        return $out;
    }
}