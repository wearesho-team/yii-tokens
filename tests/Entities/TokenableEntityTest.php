<?php

namespace Wearesho\Yii\Tests\Entities;

use Wearesho\Yii\Entities\TokenableEntity;
use Wearesho\Yii\Tests\AbstractTestCase;
use Yii;

/**
 * Class TokenableEntityTest
 * @package Wearesho\Yii\Tests\Entities
 *
 * @internal
 * @since 1.2.2
 */
class TokenableEntityTest extends AbstractTestCase
{
    public function testRecipient()
    {
        $recipient = Yii::$app->security->generateRandomString();
        $entity = new TokenableEntity($recipient);
        $this->assertEquals($recipient, $entity->getTokenRecipient());
    }

    public function testData()
    {
        $data = [
            mt_rand() => mt_rand(),
        ];
        $entity = new TokenableEntity('recipient', $data);
        $this->assertEquals($data, $entity->getTokenData());
    }
}
