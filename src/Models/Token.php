<?php

declare(strict_types=1);

namespace Wearesho\Yii\Models;

use yii\behaviors\AttributeBehavior;
use Wearesho\Yii\Traits\TokenText;
use Horat1us\Yii\CarbonBehavior;
use yii\db;

use Wearesho\Yii\Interfaces\TokenRecordInterface;
use Wearesho\Yii\Interfaces\TokenQueryInterface;
use Wearesho\Yii\Queries\TokenQuery;

/**
 * @property-read int $id
 *
 * @property string $type
 * @property string $token
 * @property string $recipient
 * @property array $data
 *
 * @property int $delivery_count
 * @property int $verify_count
 *
 * @property string $created_at
 */
final class Token extends db\ActiveRecord implements TokenRecordInterface
{
    use TokenText;

    public static function tableName(): string
    {
        return 'token';
    }

    public static function find(): TokenQueryInterface
    {
        return new TokenQuery(get_called_class());
    }

    public function behaviors(): array
    {
        return [
            'timestamp' => [
                'class' => CarbonBehavior::class,
                'updatedAtAttribute' => null,
            ],
        ];
    }

    public function rules(): array
    {
        return [
            [['token', 'recipient', 'type',], 'required'],
            [['token', 'recipient',], 'safe'],
            [['verify_count', 'delivery_count'], 'integer', 'min' => 0,],
            ['verify_count', 'default', 'value' => 0,],
            ['delivery_count', 'default', 'value' => 0,],
            ['data', 'safe',],
            ['type', 'string', 'min' => 1,],
        ];
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getRecipient(): string
    {
        return $this->recipient;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Count of sent attempts
     */
    public function getDeliveryCount(): int
    {
        return $this->delivery_count ?? 0;
    }

    /**
     * Count of verify attempts
     */
    public function getVerifyCount(): int
    {
        return $this->verify_count ?? 0;
    }

    public function setToken(string $token): TokenRecordInterface
    {
        $this->token = $token;
        return $this;
    }

    public function setRecipient(string $recipient): TokenRecordInterface
    {
        $this->recipient = $recipient;
        return $this;
    }

    public function setData(array $data): TokenRecordInterface
    {
        $this->data = $data;
        return $this;
    }

    public function increaseVerifyCount(): TokenRecordInterface
    {
        $this->verify_count++;
        return $this;
    }

    public function increaseDeliveryCount(): TokenRecordInterface
    {
        $this->delivery_count++;
        return $this;
    }

    public function __toString(): string
    {
        return $this->token;
    }
}
