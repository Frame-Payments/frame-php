<?php

namespace Frame\Tests\Endpoints;

use Frame\Client;
use Frame\Endpoints\PhoneVerifications;
use Frame\Tests\TestCase;
use Mockery;

class PhoneVerificationsTest extends TestCase
{
    private PhoneVerifications $endpoint;
    private $mockClient;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockClient = Mockery::mock('alias:' . Client::class);
        $this->endpoint = new PhoneVerifications();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    private function sampleVerification(): array
    {
        return [
            'id' => 'pv_1',
            'object' => 'phone_verification',
            'account_id' => 'acct_123',
            'status' => 'pending',
        ];
    }

    public function testCreate()
    {
        $params = ['phone_number' => '+15551234567'];

        $this->mockClient
            ->shouldReceive('post')
            ->once()
            ->with('/v1/accounts/acct_123/phone_verifications', $params)
            ->andReturn($this->sampleVerification());

        $response = $this->endpoint->create('acct_123', $params);

        $this->assertEquals('pv_1', $response['id']);
    }

    public function testConfirm()
    {
        $params = ['code' => '123456'];

        $this->mockClient
            ->shouldReceive('post')
            ->once()
            ->with('/v1/accounts/acct_123/phone_verifications/pv_1/confirm', $params)
            ->andReturn(array_merge($this->sampleVerification(), ['status' => 'verified']));

        $response = $this->endpoint->confirm('acct_123', 'pv_1', $params);

        $this->assertEquals('verified', $response['status']);
    }
}
