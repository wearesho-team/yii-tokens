<?php

declare(strict_types=1);

namespace Wearesho\Yii\Tests\Models;

use Carbon\Carbon;

use Wearesho\Yii\Models\Token;
use Wearesho\Yii\Queries\TokenQuery;
use Wearesho\Yii\Tests\AbstractTestCase;

use Wearesho\Yii\Tests\Mocks\TokenRecordMock;
use yii\db\ActiveRecord;

/**
 * Class TokenTest
 * @package Wearesho\Yii\Tests\Models
 *
 * @internal
 */
class TokenTest extends AbstractTestCase
{
    /** @var  Token */
    protected $model;

    protected function setUp(): void
    {
        parent::setUp();
        $this->model = new TokenRecordMock();
    }

    public function testDefaultTimestamp(): void
    {
        Carbon::setTestNow($now = Carbon::now());
        $this->model->trigger(ActiveRecord::EVENT_BEFORE_INSERT);
        $this->assertEquals(
            $this->model->created_at,
            $now->toDateTimeString(),
            "created_at should be filled from now on insert"
        );
        Carbon::setTestNow();
    }

    public function testRecipient(): void
    {
        $recipient = mt_rand();
        $this->model->setRecipient((string)$recipient);
        $this->assertEquals(
            $recipient,
            $this->model->getRecipient()
        );
    }

    public function testToken(): void
    {
        $token = mt_rand();
        $this->model->setToken((string)$token);
        $this->assertEquals(
            $token,
            $this->model->getToken()
        );
    }

    public function testData(): void
    {
        $data = [
            mt_rand() => mt_rand(),
        ];
        $this->model->setData($data);
        $this->assertEquals(
            $data,
            $this->model->getData()
        );
    }

    public function testDeliveryCount(): void
    {
        $this->assertEquals(
            0,
            $this->model->getDeliveryCount(),
            "Delivery count should be 0 by default"
        );
        $this->model->increaseDeliveryCount();
        $this->assertEquals(
            1,
            $this->model->getDeliveryCount()
        );
    }

    public function testVerifyCount(): void
    {
        $this->assertEquals(
            0,
            $this->model->getVerifyCount(),
            "Verify count should be 0 by default"
        );
        $this->model->increaseVerifyCount();
        $this->assertEquals(
            1,
            $this->model->getVerifyCount()
        );
    }

    public function testFind(): void
    {
        $this->assertInstanceOf(
            TokenQuery::class,
            $this->model->find()
        );
    }

    public function testValidation(): void
    {
        $this->assertFalse(
            $this->model->validate()
        );
        $this->assertTrue(
            $this->model->hasErrors('token')
        );
        $this->assertTrue(
            $this->model->hasErrors('recipient')
        );

        $this->model->setRecipient((string)mt_rand());
        $this->model->setToken((string)mt_rand());
        $this->assertTrue(
            $this->model->validate(),
            "Only token and recipient should by required attributes"
        );
    }

    public function testToStringConversion(): void
    {
        $token = 'test';

        $this->model->setToken($token);

        $this->assertEquals($token, $this->model->__toString());
    }
}
