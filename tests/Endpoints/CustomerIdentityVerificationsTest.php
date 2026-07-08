<?php

namespace Frame\Tests\Endpoints;

use Frame\Client;
use Frame\Endpoints\CustomerIdentityVerifications;
use Frame\Models\Customers\Address;
use Frame\Models\IdentityVerifications\CustomerIdentity;
use Frame\Models\IdentityVerifications\IdentityCreateRequest;
use Frame\Tests\TestCase;
use Mockery;

/**
 * Direct coverage of the canonical CustomerIdentityVerifications class. The
 * legacy IdentityVerifications subclass is covered separately in
 * IdentityVerificationsTest; this asserts the canonical class's routes on its
 * own so they can't regress independently of the deprecated alias.
 */
class CustomerIdentityVerificationsTest extends TestCase
{
    private $endpoint;
    private $mockClient;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockClient = Mockery::mock('alias:' . Client::class);
        $this->endpoint = new CustomerIdentityVerifications();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testCreate()
    {
        $address = Address::fromArray($this->getSampleAddressData());
        $createRequest = new IdentityCreateRequest(firstName: 'John', lastName: 'Doe', dateOfBirth: 'XX-XX-XXXX', email: 'johndoe@frame.com', phoneNumber: '1111111111', ssn: 'XXX-XX-XXXX', address: $address);
        $sample = $this->getSampleCustomerIdentityData();

        $this->mockClient
            ->shouldReceive('post')
            ->once()
            ->with('/v1/customer_identity_verifications', $createRequest->toArray())
            ->andReturn($sample);

        $result = $this->endpoint->create($createRequest);
        $this->assertInstanceOf(CustomerIdentity::class, $result);
        $this->assertEquals($sample['id'], $result->id);
    }

    public function testCreateForCustomer()
    {
        $customerId = 'cus_123';
        $sample = $this->getSampleCustomerIdentityData();

        $this->mockClient
            ->shouldReceive('post')
            ->once()
            ->with("/v1/customer_identity_verifications/{$customerId}")
            ->andReturn($sample);

        $result = $this->endpoint->createForCustomer($customerId);
        $this->assertInstanceOf(CustomerIdentity::class, $result);
        $this->assertEquals($sample['id'], $result->id);
    }

    public function testRetrieve()
    {
        $id = 'cusIdentity_123';
        $sample = $this->getSampleCustomerIdentityData();

        $this->mockClient
            ->shouldReceive('get')
            ->once()
            ->with("/v1/customer_identity_verifications/{$id}")
            ->andReturn($sample);

        $result = $this->endpoint->retrieve($id);
        $this->assertInstanceOf(CustomerIdentity::class, $result);
        $this->assertEquals($sample['id'], $result->id);
    }
}
