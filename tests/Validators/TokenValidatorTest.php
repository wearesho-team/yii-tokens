<?php

declare(strict_types=1);

namespace Wearesho\Yii\Tests\Validators;

use Carbon\CarbonInterval;

use Wearesho\Delivery;

use Horat1us\Yii\Validation;
use Wearesho\Yii\Interfaces\TokenRepositoryInterface;
use Wearesho\Yii\Repositories\TokenRepository;

use Wearesho\Yii\Tests\AbstractTestCase;
use Wearesho\Yii\Tests\Mocks\TokenCheckModelMock;
use Wearesho\Yii\Tests\Mocks\TokenGeneratorMock;
use Wearesho\Yii\Tests\Mocks\TokenRecordMock;
use Wearesho\Yii\Tests\Mocks\TokenRepositoryConfigMock;
use Wearesho\Yii\Validators\TokenValidator;

class TokenValidatorTest extends AbstractTestCase
{
    protected TokenRepository $repository;

    protected TokenCheckModelMock $model;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new TokenRepository(
            new TokenRecordMock(),
            $config = new TokenRepositoryConfigMock,
            new TokenGeneratorMock,
            new Delivery\ServiceMock()
        );
        $config->setExpirePeriod(CarbonInterval::years(5));

        \Yii::$container->set(
            TokenRepositoryInterface::class,
            function () {
                return $this->repository;
            }
        );

        $this->model = new TokenCheckModelMock;

        $token = new TokenRecordMock([
            'id' => 1,
            'recipient' => "380500000001",
            'token' => "000001",
        ]);
        Validation\Exception::saveOrThrow($token);
    }

    public function testInvalidRecipient(): void
    {
        $this->model->recipient = "380500000000";
        $this->model->token = "000001";
        $this->model->validate();

        $this->assertTrue(
            $this->model->hasErrors('token')
        );
        $this->assertFalse(
            $this->model->hasErrors('recipient')
        );
    }

    public function testInvalidToken(): void
    {
        $this->model->recipient = "380500000001";
        $this->model->token = "111111";
        $this->model->validate();

        $this->assertTrue(
            $this->model->hasErrors('token')
        );
        $this->assertFalse(
            $this->model->hasErrors('recipient')
        );
        $this->assertEquals('Token is invalid.', $this->model->errors['token'][0]);
    }

    public function testValid(): void
    {
        $this->model->recipient = "380500000001";
        $this->model->token = "000001";
        $this->model->validate();

        $this->assertFalse($this->model->hasErrors());
    }

    public function testCustomMessage(): void
    {
        $this->model->errorMessage = 'invalid token';
        $this->model->recipient = "380500000001";
        $this->model->token = "111111";
        $this->model->validate();

        $this->assertEquals('invalid token', $this->model->errors['token'][0]);
    }

    public function testCustomLimitReached(): void
    {
        $validator = new TokenValidator($this->createMock(TokenRepository::class));
        $this->assertEquals('Limit of messages is reached', $validator->limitReachedMessage);
        $validator = new TokenValidator($this->createMock(TokenRepository::class), [
            'limitReachedMessage' => 'custom message',
        ]);

        $this->assertEquals('custom message', $validator->limitReachedMessage);
    }
}
