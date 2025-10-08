<?php

namespace Frame\Tests;

use Mockery;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

abstract class TestCase extends PHPUnitTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Helper method to get a sample customer data array
     */
    protected function getSampleCustomerData(): array
    {
        return [
            'id' => 'cus_test123',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+1234567890',
            'status' => 'active',
            'description' => 'Test customer',
            'date_of_birth' => '1990-01-01',
            'livemode' => false,
            'created' => 1640995200,
            'updated' => 1640995200,
            'object' => 'customer',
            'billing_address' => [
                'line1' => '123 Main St',
                'city' => 'New York',
                'state' => 'NY',
                'postal_code' => '10001',
                'country' => 'US',
            ],
            'shipping_address' => [
                'line1' => '123 Main St',
                'city' => 'New York',
                'state' => 'NY',
                'postal_code' => '10001',
                'country' => 'US',
            ],
            'payment_methods' => [],
            'metadata' => [],
        ];
    }

    /**
     * Helper method to get a sample charge intent data array
     */
    protected function getSampleChargeIntentData(): array
    {
        return [
            'id' => 'ci_test123',
            'currency' => 'usd',
            'amount' => 2000,
            'status' => 'incomplete',
            'description' => 'Test charge intent',
            'client_secret' => 'ci_test123_secret',
            'livemode' => false,
            'created' => 1640995200,
            'updated' => 1640995200,
            'object' => 'charge_intent',
            'customer' => null,
            'payment_method' => null,
            'shipping' => null,
        ];
    }

    /**
     * Helper method to get a sample dispute data array
     */
    protected function getSampleDisputeData(): array
    {
        return [
            'id' => 'dis_test123',
            'amount' => 2000,
            'charge' => 'char_123',
            'currency' => 'usd',
            'evidence' => $this->getSampleDisputeEvidenceData(),
            'charge_intent' => 'char_intent123',
            'reason' => 'fraudulent',
            'status' => 'under_review',
            'livemode' => false,
            'created' => 1640995200,
            'updated' => 1640995200,
            'object' => 'dispute',
        ];
    }

    /**
     * Helper method to get a sample dispute data array
     */
    protected function getSampleDisputeEvidenceData(): array
    {
        return [
            'access_activity_log' => null,
            'billing_address' => null,
            'cancellation_policy' => null,
            'cancellation_policy_disclosure' => null,
            'cancellation_rebuttal' => null,
            'customer_email_address' => null,
            'customer_name' => null,
            'customer_purchase_ip' => null,
            'duplicate_charge_explanation' => null,
            'duplicate_charge_id' => null,
            'product_description' => null,
            'refund_policy_disclosure' => null,
            'shipping_tracking_number' => null,
            'uncategorized_text' => null
        ];
    }
}
