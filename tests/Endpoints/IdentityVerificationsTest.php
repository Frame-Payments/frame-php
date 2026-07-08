<?php

namespace Frame\Tests\Endpoints;

use Frame\Client;
use Frame\Endpoints\IdentityVerifications;
use Frame\Models\Customers\Address;
use Frame\Models\IdentityVerifications\CustomerIdentity;
use Frame\Models\IdentityVerifications\IdentityCreateRequest;
use Frame\Tests\TestCase;
use Mockery;

class IdentityVerificationsTest extends TestCase
{
    private $identityVerificationEndpoint;
    private $mockClient;

    protected function setUp(): void
    {
        parent::setUp();
        // Create a mock for the Client class
        $this->mockClient = Mockery::mock('alias:' . Client::class);
        $this->identityVerificationEndpoint = new IdentityVerifications();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testCreate()
    {
        $customerAddress = Address::fromArray($this->getSampleAddressData());
        $createRequest = new IdentityCreateRequest(firstName: 'John', lastName: 'Doe', dateOfBirth: 'XX-XX-XXXX', email: 'johndoe@frame.com', phoneNumber: '1111111111', ssn: 'XXX-XX-XXXX', address: $customerAddress);
        $sampleIdentityData = $this->getSampleCustomerIdentityData();

        $this->mockClient
            ->shouldReceive('post')
            ->once()
            ->with('/v1/customer_identity_verifications', $createRequest->toArray())
            ->andReturn($sampleIdentityData);

        $customerIdentity = $this->identityVerificationEndpoint->create($createRequest);

        $this->assertInstanceOf(CustomerIdentity::class, $customerIdentity);
        $this->assertEquals($sampleIdentityData['id'], $customerIdentity->id);
    }

    public function testRetrieve()
    {
        $identityId = 'cusIdentity_123';
        $sampleIdentityData = $this->getSampleCustomerIdentityData();

        $this->mockClient
            ->shouldReceive('get')
            ->once()
            ->with("/v1/customer_identity_verifications/{$identityId}")
            ->andReturn($sampleIdentityData);

        $customerIdentity = $this->identityVerificationEndpoint->retrieve($identityId);

        $this->assertInstanceOf(CustomerIdentity::class, $customerIdentity);
        $this->assertEquals($sampleIdentityData['id'], $customerIdentity->id);
    }

    public function testCreateForCustomer()
    {
        $customerId = 'cus_123';
        $sampleIdentityData = $this->getSampleCustomerIdentityData();

        $this->mockClient
            ->shouldReceive('post')
            ->once()
            ->with("/v1/customer_identity_verifications/{$customerId}")
            ->andReturn($sampleIdentityData);

        $customerIdentity = $this->identityVerificationEndpoint->createForCustomer($customerId);

        $this->assertInstanceOf(CustomerIdentity::class, $customerIdentity);
        $this->assertEquals($sampleIdentityData['id'], $customerIdentity->id);
    }

    public function testLegacyClassIsDeprecatedAliasOfCanonical()
    {
        $this->assertInstanceOf(
            \Frame\Endpoints\CustomerIdentityVerifications::class,
            new IdentityVerifications(),
        );
    }

    /**
     * The deprecated subclass must RE-DECLARE each operation (as a parent::
     * forwarder), not merely inherit it — the surface-manifest generator only
     * reflects methods declared on the class, and the deprecated + canonical
     * classes map to the same manifest key. If the forwarders were deleted in
     * favor of pure inheritance, the deprecated class would overwrite the
     * canonical manifest entry with an empty method list. This asserts the
     * invariant so that regression can't land silently.
     *
     * @dataProvider forwardedMethods
     */
    public function testDeprecatedSubclassDeclaresItsOwnMethods(string $method)
    {
        $declaring = (new \ReflectionMethod(IdentityVerifications::class, $method))
            ->getDeclaringClass()->getName();
        $this->assertEquals(IdentityVerifications::class, $declaring);
    }

    public static function forwardedMethods(): array
    {
        return [['create'], ['createForCustomer'], ['retrieve'], ['uploadDocuments']];
    }
}
