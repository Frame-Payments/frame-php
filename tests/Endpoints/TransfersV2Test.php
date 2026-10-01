<?php

namespace Frame\Tests\Endpoints;

use Frame\Client;
use Frame\Endpoints\TransfersV2;
use Frame\Tests\TestCase;
use Mockery;

class TransfersV2Test extends TestCase
{
    private TransfersV2 $endpoint;
    private $mockClient;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockClient = Mockery::mock('alias:' . Client::class);
        $this->endpoint = new TransfersV2();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    private function sampleTransfer(): array
    {
        return [
            'id' => 'tr_v2_123',
            'object' => 'transfer',
            'type' => 'payment',
            'status' => 'pending',
            'amount' => ['value' => 2000, 'currency' => 'usd'],
            'client_secret' => 'tr_v2_123_secret_abc',
        ];
    }

    public function testList()
    {
        $sampleListData = [
            'data' => [$this->sampleTransfer()],
            'meta' => ['total_count' => 1],
        ];

        $this->mockClient
            ->shouldReceive('get')
            ->once()
            ->with('/v2/transfers', ['per_page' => 10, 'page' => 1])
            ->andReturn($sampleListData);

        $response = $this->endpoint->list();

        $this->assertIsArray($response);
        $this->assertCount(1, $response['data']);
        $this->assertEquals('tr_v2_123', $response['data'][0]['id']);
    }

    public function testRetrieve()
    {
        $transferId = 'tr_v2_123';

        $this->mockClient
            ->shouldReceive('get')
            ->once()
            ->with("/v2/transfers/{$transferId}")
            ->andReturn($this->sampleTransfer());

        $response = $this->endpoint->retrieve($transferId);

        $this->assertIsArray($response);
        $this->assertEquals('tr_v2_123', $response['id']);
    }

    public function testCreateSendsIdempotencyKeyHeader()
    {
        $params = [
            'amount' => ['value' => 2000, 'currency' => 'usd'],
            'source' => ['payment_method_id' => 'pm_123'],
        ];

        $this->mockClient
            ->shouldReceive('post')
            ->once()
            ->with(
                '/v2/transfers',
                $params,
                Mockery::on(function (array $headers): bool {
                    return isset($headers['Idempotency-Key'])
                        && is_string($headers['Idempotency-Key'])
                        && $headers['Idempotency-Key'] !== '';
                })
            )
            ->andReturn($this->sampleTransfer());

        $response = $this->endpoint->create($params);

        $this->assertIsArray($response);
        $this->assertEquals('tr_v2_123', $response['id']);
    }

    public function testCreateUsesProvidedIdempotencyKey()
    {
        $params = [
            'amount' => ['value' => 2000, 'currency' => 'usd'],
            'source' => ['payment_method_id' => 'pm_123'],
        ];

        $this->mockClient
            ->shouldReceive('post')
            ->once()
            ->with('/v2/transfers', $params, ['Idempotency-Key' => 'my-key-123'])
            ->andReturn($this->sampleTransfer());

        $response = $this->endpoint->create($params, 'my-key-123');

        $this->assertIsArray($response);
        $this->assertEquals('tr_v2_123', $response['id']);
    }

    public function testUpdate()
    {
        $params = ['description' => 'updated'];
        $updated = $this->sampleTransfer();
        $updated['description'] = 'updated';

        $this->mockClient
            ->shouldReceive('update')
            ->once()
            ->with('/v2/transfers/tr_v2_123', $params)
            ->andReturn($updated);

        $response = $this->endpoint->update('tr_v2_123', $params);

        $this->assertEquals('updated', $response['description']);
    }

    public function testConfirm()
    {
        $confirmed = $this->sampleTransfer();
        $confirmed['status'] = 'completed';

        $this->mockClient
            ->shouldReceive('post')
            ->once()
            ->with(
                '/v2/transfers/tr_v2_123/confirm',
                [],
                \Mockery::on(function ($headers) {
                    return isset($headers['Idempotency-Key'])
                        && is_string($headers['Idempotency-Key'])
                        && $headers['Idempotency-Key'] !== '';
                })
            )
            ->andReturn($confirmed);

        $response = $this->endpoint->confirm('tr_v2_123');

        $this->assertEquals('completed', $response['status']);
    }

    public function testConfirmWithClientSecretOmitsIdempotencyKey()
    {
        $confirmed = $this->sampleTransfer();
        $params = ['client_secret' => 'tr_v2_123_secret_abc'];

        $this->mockClient
            ->shouldReceive('post')
            ->once()
            ->with('/v2/transfers/tr_v2_123/confirm', $params, [])
            ->andReturn($confirmed);

        $response = $this->endpoint->confirm('tr_v2_123', $params);

        $this->assertEquals('tr_v2_123', $response['id']);
    }

    public function testCapture()
    {
        $params = ['amount' => ['value' => 500, 'currency' => 'usd']];
        $captured = $this->sampleTransfer();
        $captured['status'] = 'completed';

        $this->mockClient
            ->shouldReceive('post')
            ->once()
            ->with(
                '/v2/transfers/tr_v2_123/capture',
                $params,
                \Mockery::on(function ($headers) {
                    return isset($headers['Idempotency-Key'])
                        && is_string($headers['Idempotency-Key'])
                        && $headers['Idempotency-Key'] !== '';
                })
            )
            ->andReturn($captured);

        $response = $this->endpoint->capture('tr_v2_123', $params);

        $this->assertEquals('completed', $response['status']);
    }

    public function testVoid()
    {
        $voided = $this->sampleTransfer();
        $voided['status'] = 'canceled';

        $this->mockClient
            ->shouldReceive('post')
            ->once()
            ->with(
                '/v2/transfers/tr_v2_123/void',
                [],
                \Mockery::on(function ($headers) {
                    return isset($headers['Idempotency-Key'])
                        && is_string($headers['Idempotency-Key'])
                        && $headers['Idempotency-Key'] !== '';
                })
            )
            ->andReturn($voided);

        $response = $this->endpoint->void('tr_v2_123');

        $this->assertEquals('canceled', $response['status']);
    }

    public function testRefund()
    {
        $params = ['amount' => ['value' => 250, 'currency' => 'usd']];
        $refunded = $this->sampleTransfer();
        $refunded['status'] = 'reversed';

        $this->mockClient
            ->shouldReceive('post')
            ->once()
            ->with(
                '/v2/transfers/tr_v2_123/refund',
                $params,
                \Mockery::on(function ($headers) {
                    return isset($headers['Idempotency-Key'])
                        && is_string($headers['Idempotency-Key'])
                        && $headers['Idempotency-Key'] !== '';
                })
            )
            ->andReturn($refunded);

        $response = $this->endpoint->refund('tr_v2_123', $params);

        $this->assertEquals('reversed', $response['status']);
    }
}
