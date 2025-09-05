<?php
namespace Frame\Models\Customers;

final class CustomerSearchRequest implements \JsonSerializable
{
    public function __construct(
        public readonly ?string $name,
        public readonly ?string $email,
        public readonly ?string $phone,
        public readonly ?int $createdBefore,
        public readonly ?int $createdAfter
    ){}

    public function jsonSerialize(): array
    {
        if ($this->name !== null) $out['name'] = $this->name;
        if ($this->email !== null) $out['email'] = $this->email;
        if ($this->phone !== null) $out['phone'] = $this->phone;
        if ($this->createdBefore !== null) $out['created_before'] = $this->createdBefore;
        if ($this->createdAfter !== null) $out['created_after'] = $this->createdAfter;

        return $out;
    }
}