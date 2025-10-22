<?php

namespace Frame\Integrations;

use Sift\SiftClient;
use Frame\Config;

final class SiftProvider
{
    private ?SiftClient $instance = null;

    public function __construct(private Config $config) {}

    public function get(): SiftClient
    {
        if ($this->instance === null) {
            $this->instance = new SiftClient([
                'beacon_key' => $this->config->siftApiKey,
                'account_id' => $this->config->siftAccountId,
            ]);
        }
        return $this->instance;
    }

    public function getClientIp(): string
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';

        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
        }

        return trim($ip);
    }
}