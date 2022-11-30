<?php

declare(strict_types=1);

namespace Wearesho\Yii\Tests\Queries;

use Carbon\Carbon;
use Carbon\CarbonInterval;

use Horat1us\Yii\Validation;
use Wearesho\Yii\Models\Token;
use Wearesho\Yii\Queries\TokenQuery;

use Wearesho\Yii\Tests\AbstractTestCase;
use Wearesho\Yii\Tests\Mocks\TokenRecordMock;

class TokenQueryTest extends AbstractTestCase
{
    protected TokenQuery $query;

    protected function setUp(): void
    {
        parent::setUp();
        $this->query = new TokenQuery(TokenRecordMock::class);

        Carbon::setTestNow(Carbon::create(2014, 1, 1, 1));
        $token = new TokenRecordMock([
            'id' => 1,
            'recipient' => "380500000001",
            'token' => "000001",
        ]);
        Validation\Exception::saveOrThrow($token);
        Carbon::setTestNow();
    }

    public function testModelClass(): void
    {
        $this->assertEquals(
            TokenRecordMock::class,
            $this->query->modelClass,
            "Query should be equal to passed to constructor class"
        );
    }

    public function testWhereRecipient(): void
    {
        $tokenInstance = $this->query
            ->whereRecipient($firstUserRecipient = '380500000001')
            ->andWhere(['=', 'id', $fieldUserId = 1])
            ->one();

        $this->assertInstanceOf(
            Token::class,
            $tokenInstance,
            "Query should use whereRecipient argument to find"
        );

        $tokenInstance = $this->query
            ->whereRecipient($invalidRecipient = '380500000000')
            ->andWhere(['=', 'id', 1])
            ->one();

        $this->assertNull(
            $tokenInstance
        );
    }

    public function testNotExpired(): void
    {
        // Setting now to hour bigger than first token created_at
        Carbon::setTestNow(Carbon::create(2014, 1, 1, 2));

        $tokenInstance = $this->query
            ->notExpired(CarbonInterval::hour(1))
            ->andWhere(['=', 'id', 1])
            ->one();
        $this->assertInstanceOf(
            Token::class,
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
