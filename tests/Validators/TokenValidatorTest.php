<?php

namespace Wearesho\Yii\Tests\Validators;

use Carbon\CarbonInterval;

use Wearesho\Delivery;

use Horat1us\Yii\Exceptions\ModelException;
use Wearesho\Yii\Interfaces\TokenRepositoryInterface;
use Wearesho\Yii\Repositories\TokenRepository;

use Wearesho\Yii\Tests\AbstractTestCase;
use Wearesho\Yii\Tests\Mocks\TokenCheckModelMock;
use Wearesho\Yii\Tests\Mocks\TokenGeneratorMock;
use Wearesho\Yii\Tests\Mocks\TokenRecordMock;
use Wearesho\Yii\Tests\Mocks\TokenRepositoryConfigMock;

/**
 * Class TokenValidatorTest
 * @package Wearesho\Yii\Tests\Validators
 *
 * @internal
 */
class TokenValidatorTest extends AbstractTestCase
{
    /** @var  TokenRepository */
    protected $repository;

    /** @var  TokenCheckModelMock */
    protected $model;

    protected function setUp()
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
        ModelException::saveOrThrow($token);
    }

    public function testInvalidRecipient()
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

    public function testInvalidToken()
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
    }

    public function testValid()
    {
        $this->model->recipient = "380500000001";
        $this->model->token = "000001";
        $this->model->validate();

        $this->assertFalse($this->model->hasErrors());
    }
}
