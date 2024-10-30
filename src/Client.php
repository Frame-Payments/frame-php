<?php

namespace Frame; // Updated namespace

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;
use Frame\Auth;        // Updated namespace
use Frame\Response;    // Updated namespace
use Frame\Exception;   // Updated namespace

class Client
{
    private static $client;

    private static function getClient()
    {
        if (!self::$client) {
            self::$client = new GuzzleClient([
                'base_uri' => 'https://api.framepayments.com', // Updated Base URI

                'headers' => [
                    'User-Agent' => 'Frame PHP SDK 1.0.0',        // Updated User-Agent
                    'Authorization' => 'Bearer ' . Auth::getApiKey(),
                    'Accept' => 'application/json',               // Updated if required by Frame Payments API

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
                    'Content-Type' => 'application/json'   // Updated Content-Type
                ]

            ]);
            return (new Response($response))->toObject();
        } catch (RequestException $e) {
            throw new Exception($e->getResponse()->getBody()->getContents()); // Uses updated Exception class
        }
    }

    public static function get($endpoint, $body = [])
    {
        try {
            $response = self::getClient()->get($endpoint, ['query' => $body]);
            return (new Response($response))->toObject();
        } catch (RequestException $e) {
            throw new Exception($e->getResponse()->getBody()->getContents());
        }
    }

    public static function update($endpoint, $body = [])
    {
        try {
            $response = self::getClient()->patch($endpoint, [    // Changed 'put' to 'patch'
                'json' => $body,
                'headers' => [
                    'Content-Type' => 'application/json'   // Updated Content-Type
                ]
            ]);
            return (new Response($response))->toObject();
        } catch (RequestException $e) {
            throw new Exception($e->getResponse()->getBody()->getContents());
        }
    }

    public static function delete($endpoint, $body = [])
    {
        try {
            $response = self::getClient()->delete($endpoint, ['json' => $body]);
            return (new Response($response))->toObject();
        } catch (RequestException $e) {
            throw new Exception($e->getResponse()->getBody()->getContents());
        }
    }
}
