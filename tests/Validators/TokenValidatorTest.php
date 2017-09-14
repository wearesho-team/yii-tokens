<?php

namespace Wearesho\Yii\Tests\Validators;

use Carbon\CarbonInterval;

use Wearesho\Yii\Interfaces\TokenRepositoryInterface;
use Wearesho\Yii\Repositories\TokenRepository;

use Wearesho\Yii\Tests\AbstractTestCase;
use Wearesho\Yii\Tests\Fixtures\RegistrationTokenFixture;
use Wearesho\Yii\Tests\Mocks\TokenCheckModelMock;
use Wearesho\Yii\Tests\Mocks\TokenGeneratorMock;
use Wearesho\Yii\Tests\Mocks\TokenRepositoryConfigMock;

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
            $config = new TokenRepositoryConfigMock,
            new TokenGeneratorMock
        );
        $config->setExpirePeriod(CarbonInterval::years(5));

        \Yii::$container->set(
            TokenRepositoryInterface::class,
            function () {
                return $this->repository;
            }
        );

        $this->model = new TokenCheckModelMock;


        $fixture = new RegistrationTokenFixture;
        $fixture->load();
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