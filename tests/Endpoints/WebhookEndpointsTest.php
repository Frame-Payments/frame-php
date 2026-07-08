<?php

namespace Frame\Tests\Endpoints;

use Frame\Client;
use Frame\Endpoints\WebhookEndpoints;
use Frame\Tests\TestCase;
use Mockery;

class WebhookEndpointsTest extends TestCase
{
    private $webhookEndpoints;
    private $mockClient;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockClient = Mockery::mock('alias:' . Client::class);
        $this->webhookEndpoints = new WebhookEndpoints();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    private function getSampleWebhookEndpointData(): array
    {
        return [
            'id' => 'we_123',
            'object' => 'webhook_endpoint',
            'url' => 'https://example.com/hook',
            'status' => 'active',
            'description' => 'Test',
            'event_codes' => ['charge.captured'],
            'livemode' => false,
            'created' => 1745107200,
            'updated' => 1745107200,
        ];
    }

    public function testList()
    {
        $sampleListData = [
            'data' => [$this->getSampleWebhookEndpointData()],
            'meta' => ['page' => 1, 'has_more' => false],
        ];

        $this->mockClient
            ->shouldReceive('get')
            ->once()
            ->with('/v1/webhook_endpoints', ['per_page' => 10, 'page' => 1])
            ->andReturn($sampleListData);

        $response = $this->webhookEndpoints->list();

        $this->assertEquals($sampleListData, $response);
    }

    public function testCreateReturnsSigningSecret()
    {
        $params = [
            'url' => 'https://example.com/hook',
            'event_codes' => ['charge.captured'],
            'description' => 'Test',
        ];
        $created = $this->getSampleWebhookEndpointData() + ['secret' => 'whsec_abc123'];

        $this->mockClient
            ->shouldReceive('post')
            ->once()
            ->with('/v1/webhook_endpoints', $params)
            ->andReturn($created);

        $response = $this->webhookEndpoints->create($params);

        $this->assertEquals('whsec_abc123', $response['secret']);
    }

    public function testRetrieve()
    {
        $sample = $this->getSampleWebhookEndpointData();

        $this->mockClient
            ->shouldReceive('get')
            ->once()
            ->with('/v1/webhook_endpoints/we_123')
            ->andReturn($sample);

        $response = $this->webhookEndpoints->retrieve('we_123');

        $this->assertEquals($sample, $response);
    }

    public function testUpdate()
    {
        $params = ['url' => 'https://example.com/new', 'event_codes' => ['charge_intent.expired']];
        $updated = array_merge($this->getSampleWebhookEndpointData(), $params);

        $this->mockClient
            ->shouldReceive('update')
            ->once()
            ->with('/v1/webhook_endpoints/we_123', $params)
            ->andReturn($updated);

        $response = $this->webhookEndpoints->update('we_123', $params);

        $this->assertEquals($updated, $response);
    }

    public function testDeleteReturnsDeletionStub()
    {
        $deleted = ['id' => 'we_123', 'object' => 'webhook_endpoint', 'deleted' => true];

        $this->mockClient
            ->shouldReceive('delete')
            ->once()
            ->with('/v1/webhook_endpoints/we_123')
            ->andReturn($deleted);

        $response = $this->webhookEndpoints->delete('we_123');

        $this->assertTrue($response['deleted']);
    }

    public function testRotateSecretReturnsNewSecret()
    {
        $rotated = $this->getSampleWebhookEndpointData() + ['secret' => 'whsec_rotated456'];

        $this->mockClient
            ->shouldReceive('post')
            ->once()
            ->with('/v1/webhook_endpoints/we_123/rotate_secret')
            ->andReturn($rotated);

        $response = $this->webhookEndpoints->rotateSecret('we_123');

        $this->assertEquals('whsec_rotated456', $response['secret']);
    }
}
