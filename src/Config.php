<?php

namespace Frame;

final class Config
{
    public function __construct(
        public readonly string $siftBeaconKey,
        public readonly ?string $siftAccountId,
    ) {}
}