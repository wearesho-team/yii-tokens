<?php


namespace Wearesho\Yii\Configs;


use Carbon\CarbonInterval;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;

use Wearesho\Yii\Interfaces\TokenRepositoryConfigInterface;

/**
 * Class TokenRepositorySettings
 * @package Wearesho\Yii\Configs
 */
class TokenRepositoryConfig implements TokenRepositoryConfigInterface, ConfigurationInterface
{
    const CONFIG_ROOT = "TokenRepository";

    /** @var CarbonInterval */
    protected $expirePeriod;

    /** @var int */
    protected $verifyLimit;

    /** @var  int */
    protected $deliveryLimit;

    /**
     * TokenRepositorySettings constructor.
     */
    private function __construct()
    {
    }

    /**
     * @return CarbonInterval
     */
    public function getExpirePeriod(): CarbonInterval
    {
        return $this->expirePeriod;
    }

    /**
     * @return int
     */
    public function getVerifyLimit(): int
    {
        return $this->verifyLimit;
    }

    /**
     * @return int
     */
    public function getDeliveryLimit(): int
    {
        return $this->deliveryLimit;
    }

    /**
     * @param array[] $configs
     * @return static
     */
    public static function instantiate(...$configs)
    {
        $instance = new TokenRepositoryConfig;

        $processor = new Processor();

        $configs = array_map(function ($config) use ($instance) {
            return $config[$instance->getConfigTreeBuilderRoot()] ?? [];
        }, $configs);

        $config = $processor->processConfiguration($instance, $configs);

        $config['expirePeriod'] = CarbonInterval::minutes($config['expirePeriod']);

        foreach ($config as $key => $value) {
            $instance->{$key} = $value;
        }

        return $instance;
    }

    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root($this->getConfigTreeBuilderRoot());
        $rootNode->children()
            ->integerNode("expirePeriod")->isRequired()->end()
            ->integerNode("verifyLimit")->isRequired()->end()
            ->scalarNode("deliveryLimit")->isRequired()->end()
            ->end();
        return $treeBuilder;
    }

    /**
     * @return string
     */
    protected function getConfigTreeBuilderRoot(): string
    {
        return static::CONFIG_ROOT;
    }
}