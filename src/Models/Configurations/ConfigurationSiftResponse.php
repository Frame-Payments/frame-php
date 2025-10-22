<?php

declare(strict_types=1);

final class ConfigurationSiftResponse implements \JsonSerializable
{
    public function __construct(
        public readonly string $accountId,
        public readonly string $beaconKey,
    ) {
    }

    public static function fromArray(array $p): self
    {
        return new self(
            accountId: $p['account_id'],
            beaconKey: $p['beacon_key'],
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'account_id' => $this->accountId,
            'beacon_key' => $this->beaconKey,
        ];
    }
}
