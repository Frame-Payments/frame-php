<?php

namespace Frame\Tests\Endpoints;

use Frame\Client;
use Frame\Endpoints\BeneficialOwners;
use Frame\Tests\TestCase;
use Mockery;

class BeneficialOwnersTest extends TestCase
{
    private BeneficialOwners $endpoint;
    private $mockClient;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockClient = Mockery::mock('alias:' . Client::class);
        $this->endpoint = new BeneficialOwners();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    private function sampleOwner(): array
    {
        return [
            'id' => 'bo_1',
            'object' => 'beneficial_owner',
            'account_id' => 'acct_123',
            'first_name' => 'Janet',
            'last_name' => 'Jones',
            'email' => 'janet@example.com',
            'roles' => ['owner', 'controller'],
            'status' => 'completed',
        ];
    }

    public function testList()
    {
        $this->mockClient
            ->shouldReceive('get')
            ->once()
            ->with('/v1/accounts/acct_123/beneficial_owners')
            ->andReturn(['data' => [$this->sampleOwner()]]);

        $response = $this->endpoint->list('acct_123');

        $this->assertCount(1, $response['data']);
        $this->assertEquals('bo_1', $response['data'][0]['id']);
    }

    public function testCreate()
    {
        $params = [
            'first_name' => 'Janet',
            'last_name' => 'Jones',
            'email' => 'janet@example.com',
            'roles' => ['owner', 'controller'],
        ];

        $this->mockClient
            ->shouldReceive('post')
            ->once()
            ->with('/v1/accounts/acct_123/beneficial_owners', $params)
            ->andReturn($this->sampleOwner());

        $response = $this->endpoint->create('acct_123', $params);

        $this->assertEquals('bo_1', $response['id']);
    }

    public function testRetrieve()
    {
        $this->mockClient
            ->shouldReceive('get')
            ->once()
            ->with('/v1/accounts/acct_123/beneficial_owners/bo_1')
            ->andReturn($this->sampleOwner());

        $response = $this->endpoint->retrieve('acct_123', 'bo_1');

        $this->assertEquals('bo_1', $response['id']);
    }

    public function testUpdate()
    {
        $params = ['percent_ownership' => 40];

        $this->mockClient
            ->shouldReceive('update')
            ->once()
            ->with('/v1/accounts/acct_123/beneficial_owners/bo_1', $params)
            ->andReturn(array_merge($this->sampleOwner(), $params));

        $response = $this->endpoint->update('acct_123', 'bo_1', $params);

        $this->assertEquals(40, $response['percent_ownership']);
    }

    public function testDelete()
    {
        $this->mockClient
            ->shouldReceive('delete')
            ->once()
            ->with('/v1/accounts/acct_123/beneficial_owners/bo_1')
            ->andReturn(null);

        $response = $this->endpoint->delete('acct_123', 'bo_1');

        $this->assertEquals([], $response);
    }

    public function testConfirmRoster()
    {
        $account = ['id' => 'acct_123', 'object' => 'account', 'status' => 'active'];

        $this->mockClient
            ->shouldReceive('post')
            ->once()
            ->with('/v1/accounts/acct_123/beneficial_owners/confirm')
            ->andReturn($account);

        $response = $this->endpoint->confirmRoster('acct_123');

        $this->assertEquals('account', $response['object']);
    }

    public function testResendInvite()
    {
        $this->mockClient
            ->shouldReceive('post')
            ->once()
            ->with('/v1/accounts/acct_123/beneficial_owners/bo_1/resend_invite')
            ->andReturn(array_merge($this->sampleOwner(), ['status' => 'invite_sent']));

        $response = $this->endpoint->resendInvite('acct_123', 'bo_1');

        $this->assertEquals('invite_sent', $response['status']);
    }
}
