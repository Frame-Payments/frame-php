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
     * Helper method to get a sample invoice data array
     */
    protected function getSampleInvoiceData(): array
    {
        return [
            'id' => 'inv_123',
            'customer' => 'cust_123',
            'total' => 100,
            'currency' => 'usd',
            'status' => 'outstanding',
            'collection_method' => 'auto_charge',
            'net_terms' => 30,
            'invoice_number' => '11111',
            'description' => 'new invoice',
            'memo' => 'memo',
            'metadata' => [],
            'livemode' => false,
            'created' => 1640995200,
            'updated' => 1640995200,
            'object' => 'invoice',
            'line_items' => $this->lineItems,
        ];
    }

    /**
     * Helper method to get a sample invoice line item data array
     */
    protected function getSampleInvoiceLineItemData(): array
    {
        return [
            'id' => 'lineItem_123',
            'description' => 'current invoice line item',
            'quantity' => 0,
            'unit_amount_cents' => 0,
            'unit_amount_currency' => 'usd',
            'created' => 1640995200,
            'updated' => 1640995200,
            'object' => 'invoice_line_item',
        ];
    }

    /**
     * Helper method to get a sample address data array
     */
    protected function getSampleAddressData(): array
    {
        return [
            'city' => 'Los Angeles',
            'country' => 'USA',
            'state' => 'CA',
            'postal_code' => '11111',
            'line_1' => '1 Angel Way',
            'line_2' => null,
        ];
    }

    /**
     * Helper method to get a sample customer identity data array
     */
    protected function getSampleCustomerIdentityData(): array
    {
        return [
            'id' => 'cusIdentity_123',
            'status' => 'incomplete',
            'verification_url' => null,
            'pending' => null,
            'verified' => null,
            'failed' => null,
            'created' => 1640995200,
            'updated' => 1640995200,
            'object' => 'identity_verification',
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
     * Helper method to get a sample dispute evidence data array
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
            'uncategorized_text' => null,
        ];
    }
}
