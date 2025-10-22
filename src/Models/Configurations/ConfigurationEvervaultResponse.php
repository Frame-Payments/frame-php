<?php

declare(strict_types=1);

final class ConfigurationEvervaultResponse implements \JsonSerializable
{
    public function __construct(
        public readonly string $appId,
        public readonly string $teamId,
    ) {
    }

    public static function fromArray(array $p): self
    {
        return new self(
            appId: $p['app_id'],
            teamId: $p['team_id'],
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'app_id' => $this->appId,
            'team_id' => $this->teamId,
        ];
    }
}
