<?php

namespace Frame\Tests\Endpoints;

use Frame\Client;
use Frame\Endpoints\Transfers;
use Frame\Tests\TestCase;
use Mockery;

class TransfersTest extends TestCase
{
    private Transfers $endpoint;
    private $mockClient;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockClient = Mockery::mock('alias:' . Client::class);
        $this->endpoint = new Transfers();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    private function sampleTransfer(): array
    {
        return [
            'id' => 'tr_123',
            'object' => 'transfer',
            'amount' => 2000,
            'currency' => 'usd',
            'status' => 'pending',
        ];
    }

    public function testList()
    {
        $sampleListData = [
            'data' => [$this->sampleTransfer()],
            'meta' => ['total_count' => 1],
        ];

        $this->mockClient
            ->shouldReceive('get')
            ->once()
            ->with('/v1/transfers', ['per_page' => 10, 'page' => 1])
            ->andReturn($sampleListData);

        $response = $this->endpoint->list();

        $this->assertIsArray($response);
        $this->assertCount(1, $response['data']);
        $this->assertEquals('tr_123', $response['data'][0]['id']);
    }

    public function testRetrieve()
    {
        $transferId = 'tr_123';

        $this->mockClient
            ->shouldReceive('get')
            ->once()
            ->with("/v1/transfers/{$transferId}")
            ->andReturn($this->sampleTransfer());

        $response = $this->endpoint->retrieve($transferId);

        $this->assertIsArray($response);
        $this->assertEquals('tr_123', $response['id']);
    }

    public function testCreate()
    {
        $params = ['amount' => 2000, 'currency' => 'usd'];

        $this->mockClient
            ->shouldReceive('post')
            ->once()
            ->with('/v1/transfers', $params)
            ->andReturn($this->sampleTransfer());

        $response = $this->endpoint->create($params);

        $this->assertIsArray($response);
        $this->assertEquals('tr_123', $response['id']);
    }

    public function testConfirm()
    {
        $transferId = 'tr_123';
        $confirmed = $this->sampleTransfer();
        $confirmed['status'] = 'confirmed';

        $this->mockClient
            ->shouldReceive('post')
            ->once()
            ->with("/v1/transfers/{$transferId}/confirm", [])
            ->andReturn($confirmed);

        $response = $this->endpoint->confirm($transferId);

        $this->assertIsArray($response);
        $this->assertEquals('confirmed', $response['status']);
    }
}
