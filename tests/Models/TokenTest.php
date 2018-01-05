<?php

namespace Wearesho\Yii\Tests\Models;

use Carbon\Carbon;

use paulzi\jsonBehavior\JsonField;

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

    protected function setUp()
    {
        parent::setUp();
        $this->model = new TokenRecordMock();
    }

    public function testDefaultTimestamp()
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

    public function testDefaultJson()
    {
        $this->assertInstanceOf(
            JsonField::class,
            $this->model->data,
            "Data should be init as " . JsonField::class
        );
    }

    public function testRecipient()
    {
        $recipient = mt_rand();
        $this->model->setRecipient($recipient);
        $this->assertEquals(
            $recipient,
            $this->model->getRecipient()
        );
    }

    public function testToken()
    {
        $token = mt_rand();
        $this->model->setToken($token);
        $this->assertEquals(
            $token,
            $this->model->getToken()
        );
    }

    public function testData()
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

    public function testDeliveryCount()
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

    public function testVerifyCount()
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

    public function testFind()
    {
        $this->assertInstanceOf(
            TokenQuery::class,
            $this->model->find()
        );
    }

    public function testValidation()
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

        $this->model->setRecipient(mt_rand());
        $this->model->setToken(mt_rand());
        $this->assertTrue(
            $this->model->validate(),
            "Only token and recipient should by required attributes"
        );
    }
}
