<?php

declare(strict_types=1);

namespace Frame\Endpoints;

use Frame\Client;
use Frame\Models\BankAccounts\BankAccount;
use Frame\Models\BankAccounts\BankAccountCreateRequest;

final class BankAccounts
{
    private const BASE_PATH = '/v1/bank_accounts';

    public function create(BankAccountCreateRequest $params): BankAccount
    {
        $json = Client::post(self::BASE_PATH, $params->toArray());

        return BankAccount::fromArray($json);
    }

    public function retrieve(string $id): BankAccount
    {
        $json = Client::get(self::BASE_PATH . "/{$id}");

        return BankAccount::fromArray($json);
    }
}
