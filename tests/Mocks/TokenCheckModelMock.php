<?php

declare(strict_types=1);

namespace Wearesho\Yii\Tests\Mocks;

use Wearesho\Yii\Validators\TokenValidator;

use yii\base\Model;

class TokenCheckModelMock extends Model
{
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
                'recipientAttribute' => 'recipient',
                'message' => $this->errorMessage,
            ],
        ];
    }
}
