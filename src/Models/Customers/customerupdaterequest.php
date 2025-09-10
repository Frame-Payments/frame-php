<?php
declare(strict_types=1);
namespace Frame\Models\Customers;

final class CustomerUpdateRequest implements \JsonSerializable
{
    public function __construct(
        public readonly ?string $name = null,
        public readonly ?string $description = null,
        public readonly ?string $email = null,
        public readonly ?string $phone = null,
        public readonly ?string $ssn = null,
        public readonly ?string $dateOfBirth = null,
        /** @var array<string,string>|null */
        public readonly ?array $metadata = null,
        public readonly ?Address $billingAddress = null,
        public readonly ?Address $shippingAddress = null,
    ){}

    public function jsonSerialize(): array
    {
        if ($this->name !== null) $out['name'] = $this->name;
        if ($this->email !== null) $out['email'] = $this->email;
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