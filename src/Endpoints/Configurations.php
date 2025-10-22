<?php

declare(strict_types=1);

namespace Frame\Endpoints;

use Frame\Client;
use Frame\Models\Configurations\ConfigurationEvervaultResponse;
use Frame\Models\Configurations\ConfigurationSiftResponse;

final class Configurations
{
    private const BASE_PATH = '/v1/config';

    public function retrieveSiftConfiguration(string $id): ConfigurationSiftResponse
    {
        $json = Client::get(self::BASE_PATH . "/sift");

        return ConfigurationSiftResponse::fromArray($json);
    }

    public function retrieveEvervaultConfiguration(string $id): ConfigurationEvervaultResponse
    {
        $json = Client::get(self::BASE_PATH . "/evervault");

        return ConfigurationEvervaultResponse::fromArray($json);
    }
}
