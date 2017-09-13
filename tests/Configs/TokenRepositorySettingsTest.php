<?php


namespace Wearesho\Yii\Tests\Configs;


use Carbon\CarbonInterval;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Wearesho\Yii\Configs\TokenRepositorySettings;

class TokenRepositorySettingsTest extends TestCase
{
    public function testInstantiatingException()
    {
        $config = [];
        $this->expectException(InvalidConfigurationException::class);
        $instance = TokenRepositorySettings::instantiate($config);
    }

    public function testInstantiating()
    {
        $config = [
            TokenRepositorySettings::CONFIG_ROOT => [
                'expirePeriod' => $expirePeriodMinutes = 20,
                'deliveryLimit' => $deliveryLimit = 2,
                'verifyLimit' => $verifyLimit = 4,
            ],
        ];
        $instance = TokenRepositorySettings::instantiate($config);
        $this->assertEquals(
            CarbonInterval::minutes($expirePeriodMinutes),
            $instance->getExpirePeriod()
        );
        $this->assertEquals(
            $deliveryLimit,
            $instance->getDeliveryLimit()
        );
        $this->assertEquals(
            $verifyLimit,
            $instance->getVerifyLimit()
        );
    }
}