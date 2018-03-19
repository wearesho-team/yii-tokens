<?php


namespace Wearesho\Yii\Models;

use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

use Carbon\Carbon;

use paulzi\jsonBehavior\JsonBehavior;
use paulzi\jsonBehavior\JsonField;
use paulzi\jsonBehavior\JsonValidator;

use Wearesho\Yii\Interfaces\TokenQueryInterface;
use Wearesho\Yii\Interfaces\TokenRecordInterface;
use Wearesho\Yii\Queries\TokenQuery;

/**
 * Class Token
 * @package Wearesho\Yii\Models
 *
 * @property-read int $id
 *
 * @property string $token
 * @property string $recipient
 * @property array $data
 *
 * @property int $delivery_count
 * @property int $verify_count
 *
 * @property string $created_at
 */
abstract class Token extends ActiveRecord implements TokenRecordInterface
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'token';
    }

    /**
     * @return TokenQueryInterface
     */
    public static function find(): TokenQueryInterface
    {
        return new TokenQuery(get_called_class());
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'updatedAtAttribute' => null,
                'value' => function () {
                    return Carbon::now()->toDateTimeString();
                },
            ],
            'type' => [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_INIT => ['type',],
                    ActiveRecord::EVENT_BEFORE_VALIDATE => ['type',],
                ],
                /** @see Token::getType() */
                'value' => [$this, 'getType']
            ]
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['token', 'recipient',], 'required'],
            [['token', 'recipient',], 'safe'],
            [['verify_count', 'delivery_count'], 'integer', 'min' => 0,],
            ['verify_count', 'default', 'value' => 0,],
            ['delivery_count', 'default', 'value' => 0,],
            ['data', 'safe',],
        ];
    }

    /**
     * @return string
     */
    abstract public static function getType(): string;

    /**
     * @return string
     */
    public function getRecipient(): string
    {
        return $this->recipient;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Count of sent attempts
     *
     * @return int
     */
    public function getDeliveryCount(): int
    {
        return $this->delivery_count ?? 0;
    }

    /**
     * Count of verify attempts
     *
     * @return int
     */
    public function getVerifyCount(): int
    {
        return $this->verify_count ?? 0;
    }

    /**
     * @param string $token
     * @return TokenRecordInterface
     */
    public function setToken(string $token): TokenRecordInterface
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @param string $recipient
     * @return TokenRecordInterface
     */
    public function setRecipient(string $recipient): TokenRecordInterface
    {
        $this->recipient = $recipient;
        return $this;
    }

    /**
     * @param array $data
     * @return TokenRecordInterface
     */
    public function setData(array $data): TokenRecordInterface
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return TokenRecordInterface
     */
    public function increaseVerifyCount(): TokenRecordInterface
    {
        $this->verify_count++;
        return $this;
    }

    /**
     * @return TokenRecordInterface
     */
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
