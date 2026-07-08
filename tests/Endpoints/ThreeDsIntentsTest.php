<?php

namespace Frame\Tests\Endpoints;

use Frame\Client;
use Frame\Endpoints\ThreeDS;
use Frame\Endpoints\ThreeDsIntents;
use Frame\Tests\TestCase;
use Mockery;

class ThreeDsIntentsTest extends TestCase
{
    private $threeDsIntents;
    private $mockClient;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockClient = Mockery::mock('alias:' . Client::class);
        $this->threeDsIntents = new ThreeDsIntents();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testCreate()
    {
        $sample = ['id' => '3ds_123', 'object' => 'three_ds_intent', 'status' => 'pending'];

        $this->mockClient
            ->shouldReceive('post')
            ->once()
            ->with('/v1/3ds/intents', [])
            ->andReturn($sample);

        $this->assertEquals($sample, $this->threeDsIntents->create([]));
    }

    public function testRetrieve()
    {
        $sample = ['id' => '3ds_123', 'object' => 'three_ds_intent'];

        $this->mockClient
            ->shouldReceive('get')
            ->once()
            ->with('/v1/3ds/intents/3ds_123')
            ->andReturn($sample);

        $this->assertEquals($sample, $this->threeDsIntents->retrieve('3ds_123'));
    }

    public function testResend()
    {
        $sample = ['id' => '3ds_123', 'object' => 'three_ds_intent'];

        $this->mockClient
            ->shouldReceive('post')
            ->once()
            ->with('/v1/3ds/intents/3ds_123/resend', [])
            ->andReturn($sample);

        $this->assertEquals($sample, $this->threeDsIntents->resend('3ds_123'));
    }

    public function testLegacyClassIsDeprecatedAliasOfCanonical()
    {
        $this->assertInstanceOf(ThreeDsIntents::class, new ThreeDS());
    }
}
