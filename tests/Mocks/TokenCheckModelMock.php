<?php

declare(strict_types=1);

namespace Wearesho\Yii\Tests\Mocks;

use Wearesho\Yii\Entities\TokenableEntity;
use Wearesho\Yii\Validators\TokenValidator;

use yii\base\Model;

class TokenCheckModelMock extends Model
{
    public const TOKEN_TYPE = 'mock';

    public ?string $token = null;

    public ?string $recipient = null;

    public ?string $errorMessage = null;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['token', 'recipient',], 'required',],
            [['token', 'recipient'], 'string',],
            ['token', TokenValidator::class,
                'recipient' => fn(self $model): TokenableEntity => new TokenableEntity(
                    (string)$model->recipient,
                    '{token}',
                    static::TOKEN_TYPE,
                    []
                ),
                'message' => $this->errorMessage,
            ],
        ];
    }
}
