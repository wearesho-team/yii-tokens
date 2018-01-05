<?php


namespace Wearesho\Yii\Tests\Repositories;

use Carbon\CarbonInterval;

use Wearesho\Yii\Exceptions\DeliveryLimitReachedException;
use Wearesho\Yii\Exceptions\InvalidRecipientException;
use Wearesho\Yii\Exceptions\InvalidTokenException;

use Wearesho\Yii\Interfaces\TokenInterface;
use Wearesho\Yii\Interfaces\TokenRecordInterface;
use Wearesho\Yii\Interfaces\TokenRepositoryConfigInterface;
use Wearesho\Yii\Models\Token;
use Wearesho\Yii\Repositories\TokenRepository;

use Wearesho\Yii\Tests\AbstractTestCase;
use Wearesho\Yii\Tests\Mocks\TokenableEntityMock;
use Wearesho\Yii\Tests\Mocks\TokenGeneratorMock;
use Wearesho\Yii\Tests\Mocks\TokenRecordMock;
use Wearesho\Yii\Tests\Mocks\TokenRepositoryConfigMock;
use Wearesho\Yii\Tests\Mocks\TokenSendServiceMock;

/**
 * Class TokenRepositoryTest
 * @package Wearesho\Yii\Tests\Repositories
 *
 * @internal
 */
class TokenRepositoryTest extends AbstractTestCase
{
    /** @var  TokenRepository */
    protected $repository;

    /** @var  TokenRepositoryConfigInterface */
    protected $settings;

    /** @var  TokenGeneratorMock */
    protected $generator;

    protected function setUp()
    {
        parent::setUp();

        $this->repository = new TokenRepository(
            new TokenRecordMock(),
            $this->settings = new TokenRepositoryConfigMock,
            $this->generator = new TokenGeneratorMock
        );

        $this->settings->setExpirePeriod(CarbonInterval::day());
        $this->settings->setDeliveryLimit(2);
        $this->settings->setVerifyLimit(3);
    }

    public function testPushNewRecipient()
    {
        $entity = new TokenableEntityMock();
        $entity->setTokenData($tokenData = [
            mt_rand() => mt_rand(),
        ]);
        $entity->setTokenRecipient($recipient = mt_rand());
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

    public function testPushExistentRecipient()
    {
        $entity = new TokenableEntityMock();
        $entity->setTokenData([
            mt_rand() => mt_rand(),
        ]);
        $entity->setTokenRecipient(mt_rand());
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

    public function testPullNothing()
    {
        $this->assertNull(
            $this->repository->pull(mt_rand())
        );
    }

    public function testPulling()
    {
        $entity = new TokenableEntityMock();
        $entity->setTokenRecipient($tokenRecipient = mt_rand());
        $entity->setTokenData($tokenData = [
            mt_rand() => mt_rand(),
        ]);

        $this->repository->push($entity);
        $token = $this->repository->pull($tokenRecipient);
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

    public function testVerifyMissingRecipient()
    {
        $this->expectException(InvalidRecipientException::class);
        $this->repository->verify(
            $recipient = mt_rand(),
            $token = mt_rand()
        );
    }

    public function testVerifyInvalidToken()
    {
        $entity = new TokenableEntityMock();
        $entity->setTokenData([mt_rand()]);
        $entity->setTokenRecipient($recipient = mt_rand());

        $token = $this->repository->push($entity);

        $this->expectException(InvalidTokenException::class);
        $this->repository->verify($recipient, "0" . $token->getToken());
    }

    public function testValidVerification()
    {
        $entity = new TokenableEntityMock();
        $entity->setTokenData([mt_rand()]);
        $entity->setTokenRecipient($recipient = mt_rand());

        $token = $this->repository->push($entity);
        $verifiedToken = $this->repository->verify($recipient, $token->getToken());

        $this->assertInstanceOf(
            TokenInterface::class,
            $verifiedToken
        );
        $this->assertEquals(
            $token->getVerifyCount(),
            $verifiedToken->getVerifyCount()
        );
        $this->assertFalse(
            TokenRecordMock::find()
                ->andWhere(['=', 'token', $token->getToken(),])
                ->andWhere(['=', 'recipient', $token->getRecipient()])
                ->exists()
        );
    }

    public function testSending()
    {
        $entity = new TokenableEntityMock();
        $entity->setTokenData([mt_rand()]);
        $entity->setTokenRecipient(mt_rand());

        $sendService = new TokenSendServiceMock();

        for ($i = 0; $i < $this->settings->getDeliveryLimit(); $i++) {
            $this->repository->send($entity, $sendService);
        }

        $this->expectException(DeliveryLimitReachedException::class);
        $this->repository->send($entity, $sendService);
    }
}
