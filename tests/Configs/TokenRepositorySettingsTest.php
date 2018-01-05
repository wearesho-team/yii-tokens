<?php

namespace Wearesho\Yii\Tests\Configs;

use Carbon\CarbonInterval;

use PHPUnit\Framework\TestCase;

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

use Wearesho\Yii\Configs\TokenRepositoryConfig;

/**
 * Class TokenRepositorySettingsTest
 * @package Wearesho\Yii\Tests\Configs
 *
 * @internal
 */
class TokenRepositorySettingsTest extends TestCase
{
    /** @var TokenRepositoryConfig */
    protected $config;

    /**
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    protected function setUp()
    {
        parent::setUp();
        $this->config = \Yii::$container->get(TokenRepositoryConfig::class);
    }

    public function testExpire()
    {
        putenv("{$this->config->expirePeriodKey}=0");

        $this->config->defaultExpirePeriod = $expirePeriod = mt_rand(1, 10);
        $this->assertEquals($expirePeriod, $this->config->getExpirePeriod()->minutes);

        $this->config->defaultExpirePeriod = 0;
        putenv("{$this->config->expirePeriodKey}={$expirePeriod}");
        $this->assertEquals($expirePeriod, $this->config->getExpirePeriod()->minutes);
    }

    public function testDeliveryLimit()
    {
        putenv("{$this->config->deliveryLimitKey}=0");

        $this->config->defaultDeliveryLimit = $deliveryLimit = mt_rand(1, 10);
        $this->assertEquals($deliveryLimit, $this->config->getDeliveryLimit());

        $this->config->defaultDeliveryLimit = 0;
        putenv("{$this->config->deliveryLimitKey}={$deliveryLimit}");
        $this->assertEquals($deliveryLimit, $this->config->getDeliveryLimit());
    }

    public function testVerifyLimit()
    {
        putenv("{$this->config->verifyLimitKey}=0");

        $this->config->defaultVerifyLimit = $verifyLimit = mt_rand(1, 10);
        $this->assertEquals($verifyLimit, $this->config->getVerifyLimit());

        $this->config->defaultVerifyLimit = 0;
        putenv("{$this->config->verifyLimitKey}={$verifyLimit}");
        $this->assertEquals($verifyLimit, $this->config->getVerifyLimit());
    }

}
