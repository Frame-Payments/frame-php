<?php

namespace Frame;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;
use Frame\Auth;
use Frame\Response;
use Frame\Exception;

final class Client {
    private static $client;

    private static function getClient()
    {
        if (!self::$client) {
            self::$client = new GuzzleClient([
                'base_uri' => 'https://api.framepayments.com',
                'headers' => [
                    'User-Agent' => 'Frame PHP SDK 1.0.0',
                    'Authorization' => 'Bearer ' . Auth::getApiKey(),
                    'Accept' => 'application/json',

                ],
            ]);
        }

        return self::$client;
    }

    public static function create($endpoint, $body = [])
    {
        try {
            $response = self::getClient()->post($endpoint, [
                'json' => $body,
                'headers' => [
                    'Content-Type' => 'application/json'
                ]
            ]);
            return (new Response($response))->toObject();
        } catch (RequestException $e) {
            $resp = $e->getResponse();
            $msg  = $resp ? (string)$resp->getBody() : $e->getMessage();
            throw new Exception($msg, previous: $e);
        }
    }

    public static function get($endpoint, $body = [])
    {
        try {
            $response = self::getClient()->get($endpoint, ['query' => $body]);
            return (new Response($response))->toObject();
        } catch (RequestException $e) {
            $resp = $e->getResponse();
            $msg  = $resp ? (string)$resp->getBody() : $e->getMessage();
            throw new Exception($msg, previous: $e);
        }
    }

    public static function update($endpoint, $body = [])
    {
        try {
            $response = self::getClient()->patch($endpoint, [ 
                'json' => $body,
                'headers' => [
                    'Content-Type' => 'application/json' 
                ]
            ]);
            return (new Response($response))->toObject();
        } catch (RequestException $e) {
            $resp = $e->getResponse();
            $msg  = $resp ? (string)$resp->getBody() : $e->getMessage();
            throw new Exception($msg, previous: $e);
        }
    }

    public static function delete($endpoint, $body = [])
    {
        try {
            $response = self::getClient()->delete($endpoint, ['json' => $body]);
            return (new Response($response))->toObject();
        } catch (RequestException $e) {
            $resp = $e->getResponse();
            $msg  = $resp ? (string)$resp->getBody() : $e->getMessage();
            throw new Exception($msg, previous: $e);
        }
    }
}
