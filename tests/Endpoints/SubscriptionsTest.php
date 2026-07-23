<?php

namespace Frame\Tests\Endpoints;

use Frame\Client;
use Frame\Endpoints\Subscriptions;
use Frame\Models\Subscriptions\Subscription;
use Frame\Models\Subscriptions\SubscriptionCreateRequest;
use Frame\Models\Subscriptions\SubscriptionListResponse;
use Frame\Models\Subscriptions\SubscriptionScheduledChange;
use Frame\Models\Subscriptions\SubscriptionSearchRequest;
use Frame\Models\Subscriptions\SubscriptionStatus;
use Frame\Models\Subscriptions\SubscriptionUpdateRequest;
use Frame\Tests\TestCase;
use Mockery;

class SubscriptionsTest extends TestCase
{
    private $subscriptionsEndpoint;
    private $mockClient;

    protected function setUp(): void
    {
        parent::setUp();
        // Create a mock for the Client class
        $this->mockClient = Mockery::mock('alias:' . Client::class);
        $this->subscriptionsEndpoint = new Subscriptions();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testCreate()
    {
        $createRequest = new SubscriptionCreateRequest(product: 'prod_123', currency: 'usd', customer: 'cus_123', defaultPaymentMethod: 'method_123', description: null, metadata: []);
        $sampleSubscriptionData = $this->getSampleSubscriptionData();

        $this->mockClient
            ->shouldReceive('post')
            ->once()
            ->with('/v1/subscriptions', $createRequest->toArray())
            ->andReturn($sampleSubscriptionData);

        $subscription = $this->subscriptionsEndpoint->create($createRequest);

        $this->assertInstanceOf(Subscription::class, $subscription);
        $this->assertEquals($sampleSubscriptionData['id'], $subscription->id);
    }

    public function testUpdate()
    {
        $subscriptionId = 'sub_123';
        $updateRequest = new SubscriptionUpdateRequest(description: 'Updated subscription');

        $sampleSubscriptionData = $this->getSampleSubscriptionData();
        $sampleSubscriptionData['description'] = 'Updated subscription';

        $this->mockClient
            ->shouldReceive('update')
            ->once()
            ->with("/v1/subscriptions/{$subscriptionId}", $updateRequest->toArray())
            ->andReturn($sampleSubscriptionData);

        $subscription = $this->subscriptionsEndpoint->update($subscriptionId, $updateRequest);

        $this->assertInstanceOf(Subscription::class, $subscription);
        $this->assertEquals('Updated subscription', $subscription->description);
    }

    public function testRetrieve()
    {
        $subscriptionId = 'sub_123';
        $sampleSubscriptionData = $this->getSampleSubscriptionData();

        $this->mockClient
            ->shouldReceive('get')
            ->once()
            ->with("/v1/subscriptions/{$subscriptionId}")
            ->andReturn($sampleSubscriptionData);

        $subscription = $this->subscriptionsEndpoint->retrieve($subscriptionId);

        $this->assertInstanceOf(Subscription::class, $subscription);
        $this->assertEquals($sampleSubscriptionData['id'], $subscription->id);
    }

    public function testList()
    {
        $sampleListData = [
            'data' => [$this->getSampleSubscriptionData()],
            'meta' => ['total_count' => 1],
        ];

        $this->mockClient
            ->shouldReceive('get')
            ->once()
            ->with('/v1/subscriptions', ['per_page' => 10, 'page' => 1])
            ->andReturn($sampleListData);

        $response = $this->subscriptionsEndpoint->list();

        $this->assertInstanceOf(SubscriptionListResponse::class, $response);
        $this->assertCount(1, $response->subscriptions);
    }

    public function testSearch()
    {
        $searchRequest = new SubscriptionSearchRequest(status: SubscriptionStatus::ACTIVE);
        $sampleListData = [
            'data' => [$this->getSampleSubscriptionData()],
            'meta' => ['total_count' => 1],
        ];

        $this->mockClient
            ->shouldReceive('get')
            ->once()
            ->with('/v1/subscriptions/search', $searchRequest->toArray())
            ->andReturn($sampleListData);

        $response = $this->subscriptionsEndpoint->search($searchRequest);
        $this->assertInstanceOf(SubscriptionListResponse::class, $response);
    }

    public function testCancel()
    {
        $subscriptionId = 'sub_123';
        $sampleSubscriptionData = $this->getSampleSubscriptionData();

        $this->mockClient
            ->shouldReceive('post')
            ->once()
            ->with("/v1/subscriptions/{$subscriptionId}/cancel", [])
            ->andReturn($sampleSubscriptionData);

        $subscription = $this->subscriptionsEndpoint->cancel($subscriptionId);
        $this->assertInstanceOf(Subscription::class, $subscription);
    }

    public function testPause()
    {
        $subscriptionId = 'sub_123';
        $sampleSubscriptionData = $this->getSampleSubscriptionData();

        $this->mockClient
            ->shouldReceive('post')
            ->once()
            ->with("/v1/subscriptions/{$subscriptionId}/pause", [])
            ->andReturn($sampleSubscriptionData);

        $subscription = $this->subscriptionsEndpoint->pause($subscriptionId);
        $this->assertInstanceOf(Subscription::class, $subscription);
    }

    public function testResume()
    {
        $subscriptionId = 'sub_123';
        $sampleSubscriptionData = $this->getSampleSubscriptionData();

        $this->mockClient
            ->shouldReceive('post')
            ->once()
            ->with("/v1/subscriptions/{$subscriptionId}/resume", [])
            ->andReturn($sampleSubscriptionData);

        $subscription = $this->subscriptionsEndpoint->resume($subscriptionId);
        $this->assertInstanceOf(Subscription::class, $subscription);
    }

    public function testCancelScheduledChange()
    {
        $subscriptionId = 'sub_123';
        $sampleSubscriptionData = $this->getSampleSubscriptionData();

        $this->mockClient
            ->shouldReceive('delete')
            ->once()
            ->with("/v1/subscriptions/{$subscriptionId}/scheduled_change")
            ->andReturn($sampleSubscriptionData);

        $subscription = $this->subscriptionsEndpoint->cancelScheduledChange($subscriptionId);
        $this->assertInstanceOf(Subscription::class, $subscription);
        $this->assertNull($subscription->scheduledChange);
    }

    public function testSubscriptionHydratesScheduledChange()
    {
        $sampleSubscriptionData = $this->getSampleSubscriptionData();
        $sampleSubscriptionData['scheduled_change'] = $this->getSampleScheduledChangeData();

        $subscription = Subscription::fromArray($sampleSubscriptionData);

        $this->assertInstanceOf(SubscriptionScheduledChange::class, $subscription->scheduledChange);
        $this->assertEquals('ssc_123', $subscription->scheduledChange->id);
        $this->assertEquals('subscription_scheduled_change', $subscription->scheduledChange->object);
        $this->assertEquals('prod_123', $subscription->scheduledChange->product);
        $this->assertTrue($subscription->scheduledChange->intervalSwitch);
        $this->assertEquals(1640995200, $subscription->scheduledChange->effectiveDate);
        $this->assertEquals(1640995200, $subscription->scheduledChange->created);
    }
}
