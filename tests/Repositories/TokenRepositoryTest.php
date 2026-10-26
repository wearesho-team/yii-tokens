<?php

declare(strict_types=1);

namespace Wearesho\Yii\Tests\Repositories;

use Carbon\CarbonInterval;

use Wearesho\Delivery;

use Wearesho\Yii\Entities\TokenableEntity;
use Wearesho\Yii\Exceptions\DeliveryLimitReachedException;
use Wearesho\Yii\Exceptions\InvalidRecipientException;
use Wearesho\Yii\Exceptions\InvalidTokenException;

use Wearesho\Yii\Interfaces\TokenInterface;
use Wearesho\Yii\Interfaces\TokenRecordInterface;
use Wearesho\Yii\Interfaces\TokenRepositoryConfigInterface;
use Wearesho\Yii\Models\Token;
use Wearesho\Yii\Repositories\TokenRepository;

use Wearesho\Yii\Tests\AbstractTestCase;
use Wearesho\Yii\Tests\Mocks\TokenGeneratorMock;
use Wearesho\Yii\Tests\Mocks\TokenRepositoryConfigMock;

class TokenRepositoryTest extends AbstractTestCase
{
    protected TokenRepository $repository;

    protected TokenRepositoryConfigInterface $settings;

    protected TokenGeneratorMock $generator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new TokenRepository(
            $this->settings = new TokenRepositoryConfigMock,
            $this->generator = new TokenGeneratorMock,
            new Delivery\StubService()
        );

        $this->settings->setExpirePeriod(CarbonInterval::day());
        $this->settings->setDeliveryLimit(2);
        $this->settings->setVerifyLimit(3);
    }

    public function testPushNewRecipient(): void
    {
        $entity = $this->createEntity($recipient = (string)mt_rand(), $tokenData = [
            mt_rand() => mt_rand(),
        ]);
        $this->generator->setExceptedToken($expectedToken = "123123");

        $token = $this->repository->push($entity);
        $this->assertEquals(
            $tokenData,
            $token->getData()
        );
        $this->assertEquals(
            $recipient,
            $token->getRecipient()
        );
        $this->assertEquals(
            $expectedToken,
            $token->getToken()
        );
    }

    public function testPushExistentRecipient(): void
    {
        $entity = $this->createEntity((string)mt_rand(), [
            mt_rand() => mt_rand(),
        ]);
        $this->generator->setExceptedToken("567890");

        $firstToken = $this->repository->push($entity);
        $this->assertEquals(
            0,
            $firstToken->getDeliveryCount()
        );

        $this->generator->setExceptedToken("111111");
        $secondToken = $this->repository->push($entity);

        $this->assertEquals(
            $firstToken->getToken(),
            $secondToken->getToken()
        );
        $this->assertEquals(
            $firstToken->getData(),
            $secondToken->getData()
        );
        $this->assertEquals(
            $firstToken->getRecipient(),
            $secondToken->getRecipient()
        );
        $this->assertEquals(
            $firstToken->getDeliveryCount(),
            $secondToken->getDeliveryCount()
        );
    }

    public function testPullNothing(): void
    {
        $this->assertNull(
            $this->repository->pull($this->createEntity((string)mt_rand()))
        );
    }

    public function testPulling(): void
    {
        $entity = $this->createEntity($tokenRecipient = (string)mt_rand(), $tokenData = [
            mt_rand() => mt_rand(),
        ]);

        $this->repository->push($entity);
        $token = $this->repository->pull($entity);
        $this->assertInstanceOf(
            TokenRecordInterface::class,
            $token
        );
        $this->assertEquals(
            $token->getDeliveryCount(),
            0
        );
        $this->assertEquals(
            $token->getRecipient(),
            $tokenRecipient
        );
        $this->assertEquals(
            $token->getData(),
            $tokenData
        );
    }

    public function testVerifyMissingRecipient(): void
    {
        $this->expectException(InvalidRecipientException::class);
        $this->repository->verify(
            $this->createEntity((string)mt_rand()),
            $token = (string)mt_rand()
        );
    }

    public function testVerifyInvalidToken(): void
    {
        $entity = $this->createEntity((string)mt_rand(), [mt_rand()]);

        $token = $this->repository->push($entity);

        $this->expectException(InvalidTokenException::class);
        $this->repository->verify($entity, "0" . $token->getToken());
    }

    public function testValidVerification(): void
    {
        $entity = $this->createEntity((string)mt_rand(), [mt_rand()]);

        $token = $this->repository->push($entity);
        $verifiedToken = $this->repository->verify($entity, $token->getToken());

        $this->assertInstanceOf(
            TokenInterface::class,
            $verifiedToken
        );
        $this->assertEquals(
            $token->getVerifyCount() + 1,
            $verifiedToken->getVerifyCount()
        );
        $this->assertFalse(
            Token::find()
                ->andWhere(['=', 'token', $token->getToken(),])
                ->andWhere(['=', 'recipient', $token->getRecipient()])
                ->exists()
        );
    }

    public function testSending(): void
    {
        $entity = $this->createEntity((string)mt_rand(), [mt_rand()]);

        for ($i = 0; $i < $this->settings->getDeliveryLimit(); $i++) {
            $this->repository->send($entity);
        }

        $this->expectException(DeliveryLimitReachedException::class);
        $this->repository->send($entity);
    }

    private function createEntity(string $recipient, array $data = []): TokenableEntity
    {
        return new TokenableEntity($recipient, 'Your code: {token}', 'mock', $data);
    }
}
