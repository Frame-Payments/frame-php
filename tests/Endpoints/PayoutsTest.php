<?php

namespace Frame\Tests\Endpoints;

use Frame\Client;
use Frame\Endpoints\Payouts;
use Frame\Tests\TestCase;
use Mockery;

class PayoutsTest extends TestCase
{
    private Payouts $payoutsEndpoint;
    private $mockClient;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockClient = Mockery::mock('alias:' . Client::class);
        $this->payoutsEndpoint = new Payouts();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testCreate()
    {
        $params = [
            'amount' => 1000,
            'currency' => 'usd',
            'account' => 'acct_test123',
            'payment_method' => 'pm_test123',
        ];
        $samplePayoutData = $this->getSamplePayoutData();

        $this->mockClient
            ->shouldReceive('post')
            ->once()
            ->with('/v1/payouts', $params)
            ->andReturn($samplePayoutData);

        $payout = $this->payoutsEndpoint->create($params);

        $this->assertIsArray($payout);
        $this->assertEquals($samplePayoutData['id'], $payout['id']);
        $this->assertEquals(1000, $payout['amount']);
        $this->assertEquals('usd', $payout['currency']);
    }

    public function testCreatePassesParamsThrough()
    {
        $params = ['amount' => 5000, 'currency' => 'usd'];

        $this->mockClient
            ->shouldReceive('post')
            ->once()
            ->with('/v1/payouts', $params)
            ->andReturn(['id' => 'po_test456', 'amount' => 5000]);

        $payout = $this->payoutsEndpoint->create($params);

        $this->assertIsArray($payout);
        $this->assertEquals('po_test456', $payout['id']);
    }

    private function getSamplePayoutData(): array
    {
        return [
            'id' => 'po_test123',
            'object' => 'payout',
            'amount' => 1000,
            'currency' => 'usd',
            'status' => 'pending',
            'account' => 'acct_test123',
            'payment_method' => 'pm_test123',
        ];
    }
}
