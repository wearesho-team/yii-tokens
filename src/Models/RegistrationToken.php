<?php


namespace Wearesho\Yii\Models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

use Carbon\Carbon;

use paulzi\jsonBehavior\JsonBehavior;
use paulzi\jsonBehavior\JsonField;
use paulzi\jsonBehavior\JsonValidator;

use Wearesho\Yii\Interfaces\TokenQueryInterface;
use Wearesho\Yii\Interfaces\TokenRecordInterface;
use Wearesho\Yii\Queries\RegistrationTokenQuery;

/**
 * Class RegistrationToken
 * @package Wearesho\Yii\Models
 *
 * @property-read int $id
 *
 * @property string $token
 * @property string $recipient
 * @property JsonField $data
 *
 * @property int $delivery_count
 * @property int $verify_count
 *
 * @property string $created_at
 */
class RegistrationToken extends ActiveRecord implements TokenRecordInterface
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'jsonData' => [
                'class' => JsonBehavior::class,
                'attributes' => ['data'],
            ],
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
                'value' => function () {
                    return Carbon::now()->toDateTimeString();
                },
            ],
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
            ['data', JsonValidator::class,]
        ];
    }

    /**
     * @return TokenQueryInterface
     */
    public static function find(): TokenQueryInterface
    {
        return new RegistrationTokenQuery();
    }

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
        return $this->data->toArray();
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
        $this->data->set($data);
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
}