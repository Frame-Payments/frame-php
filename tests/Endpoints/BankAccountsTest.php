<?php

namespace Frame\Tests\Endpoints;

use Frame\Client;
use Frame\Endpoints\BankAccounts;
use Frame\Models\BankAccounts\BankAccount;
use Frame\Models\BankAccounts\BankAccountCreateRequest;
use Frame\Tests\TestCase;
use Mockery;

class BankAccountsTest extends TestCase
{
    private $bankAccountsEndpoint;
    private $mockClient;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockClient = Mockery::mock('alias:' . Client::class);
        $this->bankAccountsEndpoint = new BankAccounts();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    private function getSampleBankAccountData(): array
    {
        return [
            'id' => 'ba_1234567890abcdef',
            'object' => 'bank_account',
            'routing_number' => '110000000',
            'account_number_last_4' => '6789',
            'account_type' => 'checking',
            'bank_name' => 'Test Bank',
            'processor_name' => 'plaid',
            'status' => 'active',
            'customer_id' => 'cus_1234567890abcdef',
            'account_id' => null,
            'created' => 1640995200,
            'updated' => 1640995200,
            'livemode' => false,
        ];
    }

    public function testCreate()
    {
        $createRequest = new BankAccountCreateRequest(
            processor: 'plaid',
            processorToken: 'processor-sandbox-token',
            customerId: 'cus_1234567890abcdef',
        );
        $sampleData = $this->getSampleBankAccountData();

        $this->mockClient
            ->shouldReceive('post')
            ->once()
            ->with('/v1/bank_accounts', $createRequest->toArray())
            ->andReturn($sampleData);

        $bankAccount = $this->bankAccountsEndpoint->create($createRequest);

        $this->assertInstanceOf(BankAccount::class, $bankAccount);
        $this->assertEquals($sampleData['id'], $bankAccount->id);
        $this->assertEquals('6789', $bankAccount->accountNumberLast4);
    }

    public function testRetrieve()
    {
        $sampleData = $this->getSampleBankAccountData();

        $this->mockClient
            ->shouldReceive('get')
            ->once()
            ->with('/v1/bank_accounts/ba_1234567890abcdef')
            ->andReturn($sampleData);

        $bankAccount = $this->bankAccountsEndpoint->retrieve('ba_1234567890abcdef');

        $this->assertInstanceOf(BankAccount::class, $bankAccount);
        $this->assertEquals($sampleData['id'], $bankAccount->id);
        $this->assertEquals('active', $bankAccount->status);
    }
}
