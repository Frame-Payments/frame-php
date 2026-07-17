<?php

namespace Frame\Tests\Endpoints;

use Frame\Client;
use Frame\Endpoints\Capabilities;
use Frame\Tests\TestCase;
use Mockery;

class CapabilitiesTest extends TestCase
{
    private Capabilities $endpoint;
    private $mockClient;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockClient = Mockery::mock('alias:' . Client::class);
        $this->endpoint = new Capabilities();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    private function sampleCapability(): array
    {
        return [
            'name' => 'payments',
            'status' => 'active',
            'account_id' => 'acct_123',
            'object' => 'capability',
        ];
    }

    public function testList()
    {
        $this->mockClient
            ->shouldReceive('get')
            ->once()
            ->with('/v1/accounts/acct_123/capabilities')
            ->andReturn(['data' => [$this->sampleCapability()]]);

        $response = $this->endpoint->list('acct_123');

        $this->assertCount(1, $response['data']);
        $this->assertEquals('payments', $response['data'][0]['name']);
    }

    public function testRequest()
    {
        $params = ['capabilities' => ['payments']];

        $this->mockClient
            ->shouldReceive('post')
            ->once()
            ->with('/v1/accounts/acct_123/capabilities', $params)
            ->andReturn($this->sampleCapability());

        $response = $this->endpoint->request('acct_123', $params);

        $this->assertEquals('payments', $response['name']);
    }

    public function testRetrieve()
    {
        $this->mockClient
            ->shouldReceive('get')
            ->once()
            ->with('/v1/accounts/acct_123/capabilities/payments')
            ->andReturn($this->sampleCapability());

        $response = $this->endpoint->retrieve('acct_123', 'payments');

        $this->assertEquals('payments', $response['name']);
    }

    public function testDisable()
    {
        $this->mockClient
            ->shouldReceive('delete')
            ->once()
            ->with('/v1/accounts/acct_123/capabilities/payments')
            ->andReturn($this->sampleCapability());

        $response = $this->endpoint->disable('acct_123', 'payments');

        $this->assertEquals('capability', $response['object']);
    }
}
