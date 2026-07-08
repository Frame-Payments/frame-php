<?php

namespace Frame\Tests\Endpoints;

use Frame\Client;
use Frame\Endpoints\MerchantBalance;
use Frame\Tests\TestCase;
use Mockery;

class MerchantBalanceTest extends TestCase
{
    private $merchantBalance;
    private $mockClient;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockClient = Mockery::mock('alias:' . Client::class);
        $this->merchantBalance = new MerchantBalance();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testRetrieve()
    {
        $sample = [
            'merchant_id' => 'mer_123',
            'currency' => 'USD',
            'available_for_payout' => 40000.0,
            'status' => 'AVAILABLE',
        ];

        $this->mockClient
            ->shouldReceive('get')
            ->once()
            ->with('/v1/merchant_balance')
            ->andReturn($sample);

        $balance = $this->merchantBalance->retrieve();

        $this->assertEquals('USD', $balance['currency']);
        $this->assertEquals('AVAILABLE', $balance['status']);
    }
}
