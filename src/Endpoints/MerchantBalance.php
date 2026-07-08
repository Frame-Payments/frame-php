<?php

declare(strict_types=1);

namespace Frame\Endpoints;

use Frame\Client;

/**
 * Top-level singleton: the authenticated merchant's balance (available funds,
 * reserved amounts, pending payouts). GET /v1/merchant_balance — no id.
 */
final class MerchantBalance
{
    private const BASE_PATH = '/v1/merchant_balance';

    public function retrieve(): array
    {
        return Client::get(self::BASE_PATH);
    }
}
