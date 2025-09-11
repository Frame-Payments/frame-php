<?php

namespace Frame\Tests\Endpoints;

use Frame\Client;
use Frame\Endpoints\ChargeIntents;
use Frame\Models\ChargeIntents\ChargeIntent;
use Frame\Models\ChargeIntents\ChargeIntentCreateRequest;
use Frame\Models\ChargeIntents\ChargeIntentListResponse;
use Frame\Models\ChargeIntents\ChargeIntentUpdateRequest;
use Frame\Tests\TestCase;
use Mockery;

class ChargeIntentsTest extends TestCase
{
    private $chargeIntentsEndpoint;
    private $mockClient;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockClient = Mockery::mock('alias:' . Client::class);
        $this->chargeIntentsEndpoint = new ChargeIntents();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testCreate()
    {
        $createRequest = new ChargeIntentCreateRequest(amount: 2000, currency: 'usd');
        $sampleChargeIntentData = $this->getSampleChargeIntentData();

        $this->mockClient
            ->shouldReceive('create')
            ->once()
            ->with('/v1/charge_intents', $createRequest)
            ->andReturn($sampleChargeIntentData);

        $chargeIntent = $this->chargeIntentsEndpoint->create($createRequest);

        $this->assertInstanceOf(ChargeIntent::class, $chargeIntent);
        $this->assertEquals($sampleChargeIntentData['id'], $chargeIntent->id);
    }

    public function testUpdate()
    {
        $intentId = 'ci_123';
        $updateRequest = new ChargeIntentUpdateRequest(amount: 3000);
        $sampleChargeIntentData = $this->getSampleChargeIntentData();
        $sampleChargeIntentData['amount'] = 3000;

        $this->mockClient
            ->shouldReceive('update')
            ->once()
            ->with("/v1/charge_intents/{$intentId}", $updateRequest)
            ->andReturn($sampleChargeIntentData);

        $chargeIntent = $this->chargeIntentsEndpoint->update($intentId, $updateRequest);

        $this->assertInstanceOf(ChargeIntent::class, $chargeIntent);
        $this->assertEquals(3000, $chargeIntent->amount);
    }

    public function testRetrieve()
    {
        $intentId = 'ci_123';
        $sampleChargeIntentData = $this->getSampleChargeIntentData();

        $this->mockClient
            ->shouldReceive('get')
            ->once()
            ->with("/v1/charge_intents/{$intentId}")
            ->andReturn($sampleChargeIntentData);

        $chargeIntent = $this->chargeIntentsEndpoint->retrieve($intentId);

        $this->assertInstanceOf(ChargeIntent::class, $chargeIntent);
        $this->assertEquals($sampleChargeIntentData['id'], $chargeIntent->id);
    }

    public function testList()
    {
        $sampleListData = [
            'data' => [$this->getSampleChargeIntentData()],
            'meta' => ['total_count' => 1]
        ];

        $this->mockClient
            ->shouldReceive('get')
            ->once()
            ->with('/v1/charge_intents', ['per_page' => 10, 'page' => 1])
            ->andReturn($sampleListData);

        $response = $this->chargeIntentsEndpoint->list();

        $this->assertInstanceOf(ChargeIntentListResponse::class, $response);
        $this->assertCount(1, $response->chargeIntents);
    }

    public function testConfirm()
    {
        $intentId = 'ci_123';
        $params = ['payment_method' => 'pm_abc'];
        $sampleChargeIntentData = $this->getSampleChargeIntentData();

        $this->mockClient
            ->shouldReceive('create')
            ->once()
            ->with("/v1/charge_intents/{$intentId}/confirm", $params)
            ->andReturn($sampleChargeIntentData);

        $chargeIntent = $this->chargeIntentsEndpoint->confirm($intentId, $params);
        $this->assertInstanceOf(ChargeIntent::class, $chargeIntent);
    }

    public function testCapture()
    {
        $intentId = 'ci_123';
        $params = ['amount' => 1500];
        $sampleChargeIntentData = $this->getSampleChargeIntentData();

        $this->mockClient
            ->shouldReceive('create')
            ->once()
            ->with("/v1/charge_intents/{$intentId}/capture", $params)
            ->andReturn($sampleChargeIntentData);

        $chargeIntent = $this->chargeIntentsEndpoint->capture($intentId, $params);
        $this->assertInstanceOf(ChargeIntent::class, $chargeIntent);
    }

    public function testCancel()
    {
        $intentId = 'ci_123';
        $params = ['cancellation_reason' => 'requested_by_customer'];
        $sampleChargeIntentData = $this->getSampleChargeIntentData();

        $this->mockClient
            ->shouldReceive('create')
            ->once()
            ->with("/v1/charge_intents/{$intentId}/cancel", $params)
            ->andReturn($sampleChargeIntentData);

        $chargeIntent = $this->chargeIntentsEndpoint->cancel($intentId, $params);
        $this->assertInstanceOf(ChargeIntent::class, $chargeIntent);
    }
}