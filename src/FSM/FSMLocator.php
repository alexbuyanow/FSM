<?php

namespace FSM;

use FSM\Container\ContainerInterface;
use FSM\Machine\MachineFactoryInterface;
use FSM\Machine\MachineFactory;
use FSM\Resolver\ResolverInterface;

/**
 * General FSM factories locator
 *
 * @package FSM
 */
class FSMLocator
{
    const CONFIG_KEY_OPTIONS   = 'options';
    const CONFIG_KEY_MACHINES  = 'machines';

    const OPTIONS_KEY_RESOLVER = 'resolver';
    const OPTIONS_KEY_STRICT   = 'strict';


    /** @var array */
    private $options = [];

    /** @var  ResolverInterface */
    private $resolver;

    /** @var  array */
    private $machinesConfig;

    /** @var MachineFactoryInterface */
    private $factory;

    /** @var  ContainerInterface */
    private $container;


    /**
     * @param array $config
     * @param ContainerInterface $container
     */
    public function __construct(array $config, ContainerInterface $container)
    {
        $config               = array_merge($this->getDefaultConfig(), $config);
        $this->options        = $this->getOptions($config);
        $this->container      = $container;
        $this->machinesConfig = $this->getMachinesConfig($config);
        $this->resolver       = $this->getResolver();
    }

    /**
     * Gets machine factory
     *
     * @return MachineFactoryInterface
     */
    public function getMachineFactory()
    {
        if(is_null($this->factory))
        {
            $this->factory = new MachineFactory($this->options);
        }

        return $this->factory;
    }

    /**
     * Gets concrete machine
     *
     * @param ContextInterface $context
     * @throws Exception\UnknownContextException
     * @return Machine\Machine
     */
    public function getMachine(ContextInterface $context)
    {
        if(!$this->resolver->isContained($context))
        {
            $message = sprintf('Machine for context %s is not describe in config', get_class($context));
            throw new Exception\UnknownContextException($message);
        }

        return $this->getMachineFactory()->getMachine(
            $this->resolver->getConfigName($context),
            $this->resolver->getConfig($context),
            $this->container
        );
    }


    /**
     * Gets default config
     *
     * @return array
     */
    private function getDefaultConfig()
    {
        return include __DIR__ . '/../../config/default.php';
    }

    /**
     * Gets Options section from config
     *
     * @param array $config
     * @return array
     * @throws Exception\InvalidConfigException
     */
    private function getOptions(array $config)
    {
        if(!array_key_exists(static::CONFIG_KEY_OPTIONS, $config))
        {
            $message = 'FSM options section is not defined in config';
            throw new Exception\InvalidConfigException($message);
        }

        if(!is_array($config[static::CONFIG_KEY_OPTIONS]) || empty($config[static::CONFIG_KEY_OPTIONS]))
        {
            $message = 'FSM options section is incorrect defined or empty in config';
            throw new Exception\InvalidConfigException($message);
        }

        return $config[static::CONFIG_KEY_OPTIONS];
    }

    /**
     * Gets Machines section from config
     *
     * @param array $config
     * @return array
     * @throws Exception\InvalidConfigException
     */
    private function getMachinesConfig(array $config)
    {
        if(!array_key_exists(static::CONFIG_KEY_MACHINES, $config))
        {
            $message = 'Machines config section is not defined in config';
            throw new Exception\InvalidConfigException($message);
        }

        if(!is_array($config[static::CONFIG_KEY_MACHINES]) || empty($config[static::CONFIG_KEY_MACHINES]))
        {
            $message = 'Machines config section is incorrect defined or empty in config';
            throw new Exception\InvalidConfigException($message);
        }

        return $config[static::CONFIG_KEY_MACHINES];
    }

    /**
     * Gets config resolver
     *
     * @return ResolverInterface
     * @throws Exception\InvalidResolverException
     */
    private function getResolver()
    {
        if(!(array_key_exists(static::OPTIONS_KEY_RESOLVER, $this->options) && $this->options[static::OPTIONS_KEY_RESOLVER]))
        {
            $message = 'Resolver class name is not defined in options';
            throw new Exception\InvalidResolverException($message);
        }

        $resolverClassName = $this->options[static::OPTIONS_KEY_RESOLVER];

        if(!class_exists($resolverClassName))
        {
            $message = sprintf('Resolver class %s is not exists', $resolverClassName);
            throw new Exception\InvalidResolverException($message);
        }

        $resolver = new $resolverClassName($this->machinesConfig);

        if(!($resolver instanceof ResolverInterface))
        {
            $message = sprintf('Resolver class %s is not implements ResolverInterface', $resolverClassName);
            throw new Exception\InvalidResolverException($message);
        }

        return $resolver;
    }
}
