<?php


namespace Wearesho\Yii\Tests\Queries;


use Carbon\Carbon;
use Carbon\CarbonInterval;

use Wearesho\Yii\Models\RegistrationToken;
use Wearesho\Yii\Queries\RegistrationTokenQuery;

use Wearesho\Yii\Tests\AbstractTestCase;
use Wearesho\Yii\Tests\Fixtures\RegistrationTokenFixture;

/**
 * Class RegistrationTokenQueryTest
 * @package Wearesho\Yii\Tests\Queries
 */
class RegistrationTokenQueryTest extends AbstractTestCase
{
    /** @var  RegistrationTokenQuery */
    protected $query;

    /** @var  RegistrationTokenFixture */
    protected $fixture;

    protected function setUp()
    {
        parent::setUp();
        $this->query = new RegistrationTokenQuery();
        $this->fixture = new RegistrationTokenFixture();
        $this->fixture->load();
    }

    public function testModelClass()
    {
        $this->assertEquals(
            RegistrationToken::class,
            $this->query->modelClass,
            "Query should be default related to " . RegistrationToken::class
        );
    }

    public function testWhereRecipient()
    {
        $tokenInstance = $this->query
            ->whereRecipient($firstUserRecipient = 380500000001)
            ->andWhere(['=', 'id', $fieldUserId = 1])
            ->one();

        $this->assertInstanceOf(
            RegistrationToken::class,
            $tokenInstance,
            "Query should use whereRecipient argument to find"
        );

        $tokenInstance = $this->query
            ->whereRecipient($invalidRecipient = 380500000000)
            ->andWhere(['=', 'id', 1])
            ->one();

        $this->assertNull(
            $tokenInstance
        );
    }

    public function testNotExpired()
    {
        // Setting now to hour bigger than first token created_at
        Carbon::setTestNow(Carbon::create(2014, 1, 1, 2));

        $tokenInstance = $this->query
            ->notExpired(CarbonInterval::hour(1))
            ->andWHere(['=', 'id', 1])
            ->one();
        $this->assertInstanceOf(
            RegistrationToken::class,
            $tokenInstance
        );

        $tokenInstance = $this->query
            ->notExpired(CarbonInterval::minutes(30))
            ->andWhere(['=', 'id', 1])
            ->one();
        $this->assertNull(
            $tokenInstance
        );

        Carbon::setTestNow();
    }
}