<?php

namespace Frame\Tests\Endpoints;

use Frame\Client;
use Frame\Endpoints\Wallets;
use Frame\Tests\TestCase;
use Mockery;

class WalletsTest extends TestCase
{
    private Wallets $endpoint;
    private $mockClient;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockClient = Mockery::mock('alias:' . Client::class);
        $this->endpoint = new Wallets();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    private function sampleWallet(): array
    {
        return [
            'id' => 'wal_123',
            'object' => 'wallet',
            'account_id' => 'acct_123',
            'default' => true,
            'name' => 'Default',
            'status' => 'active',
            'rejected_reason' => null,
            'available' => ['value' => 0, 'currency' => 'USD'],
            'metadata' => [],
            'livemode' => false,
            'created' => 1745107200,
            'updated' => 1745107200,
        ];
    }

    public function testList()
    {
        $sampleListData = [
            'data' => [$this->sampleWallet()],
            'meta' => ['page' => 1, 'has_more' => false],
        ];

        $this->mockClient
            ->shouldReceive('get')
            ->once()
            ->with('/v2/wallets', ['per_page' => 10, 'page' => 1, 'account_id' => 'acct_123'])
            ->andReturn($sampleListData);

        $response = $this->endpoint->list(10, 1, 'acct_123');

        $this->assertCount(1, $response['data']);
        $this->assertEquals('wal_123', $response['data'][0]['id']);
    }

    public function testRetrieve()
    {
        $this->mockClient
            ->shouldReceive('get')
            ->once()
            ->with('/v2/wallets/wal_123')
            ->andReturn($this->sampleWallet());

        $response = $this->endpoint->retrieve('wal_123');

        $this->assertEquals('wallet', $response['object']);
        $this->assertEquals('wal_123', $response['id']);
    }

    public function testRetrieveDefault()
    {
        $this->mockClient
            ->shouldReceive('get')
            ->once()
            ->with('/v2/wallets/default', ['account_id' => 'acct_123'])
            ->andReturn($this->sampleWallet());

        $response = $this->endpoint->retrieve_default('acct_123');

        $this->assertEquals('wal_123', $response['id']);
        $this->assertTrue($response['default']);
    }

    public function testUpdateSendsIdempotencyKeyHeader()
    {
        $params = ['name' => 'Payouts'];

        $this->mockClient
            ->shouldReceive('update')
            ->once()
            ->with(
                '/v2/wallets/wal_123',
                $params,
                Mockery::on(function (array $headers): bool {
                    return isset($headers['Idempotency-Key'])
                        && is_string($headers['Idempotency-Key'])
                        && $headers['Idempotency-Key'] !== '';
                })
            )
            ->andReturn($this->sampleWallet());

        $response = $this->endpoint->update('wal_123', $params);

        $this->assertEquals('wal_123', $response['id']);
    }

    public function testUpdateUsesProvidedIdempotencyKey()
    {
        $params = ['default' => true];

        $this->mockClient
            ->shouldReceive('update')
            ->once()
            ->with('/v2/wallets/wal_123', $params, ['Idempotency-Key' => 'my-key-123'])
            ->andReturn($this->sampleWallet());

        $response = $this->endpoint->update('wal_123', $params, 'my-key-123');

        $this->assertEquals('wal_123', $response['id']);
    }
}
