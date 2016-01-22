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
    private $configKeyOptions = 'options';
    private $configKeyResolver = 'resolver';
    private $configKeyMachines = 'machines';

    /** @var array */
    private $options = [];

    /** @var  ResolverInterface */
    private $resolver;

    /** @var  array */
    private $machinesConfig;

    /** @var MachineFactoryInterface[] */
    private $factories = [];

    /** @var  ContainerInterface */
    private $container;


    /**
     * @param array $config
     * @param ContainerInterface $container
     */
    public function __construct(array $config, ContainerInterface $container)
    {
        $config = array_merge($this->getDefaultConfig(), $config);
        $this->options = $this->getOptions($config);
        $this->container = $container;
        $this->machinesConfig = $this->getMachinesConfig($config);
        $this->resolver = $this->getResolver();
    }

    /**
     * Gets concrete machine sub factory
     *
     * @param ContextInterface $context
     * @return MachineFactoryInterface
     * @throws Exception\UnknownContextException
     */
    public function getMachineFactory(ContextInterface $context)
    {
        $machineName = $this->resolver->getConfigName($context);

        if (!array_key_exists($machineName, $this->factories)) {
            if (!$this->resolver->isContained($context)) {
                $message = sprintf('Machine for context %s is not describe in config', get_class($context));
                throw new Exception\UnknownContextException($message);
            }

            $this->factories[$machineName] = new MachineFactory($machineName, $this->resolver->getConfig($context), $this->options, $this->container);
        }

        return $this->factories[$machineName];
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
        if (!array_key_exists($this->configKeyOptions, $config)) {
            $message = 'FSM options section is not defined in config';
            throw new Exception\InvalidConfigException($message);
        }

        if (!is_array($config[$this->configKeyOptions]) || empty($config[$this->configKeyOptions])) {
            $message = 'FSM options section is incorrect defined or empty in config';
            throw new Exception\InvalidConfigException($message);
        }

        return $config[$this->configKeyOptions];
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
        if (!array_key_exists($this->configKeyMachines, $config)) {
            $message = 'Machines config section is not defined in config';
            throw new Exception\InvalidConfigException($message);
        }

        if (!is_array($config[$this->configKeyMachines]) || empty($config[$this->configKeyMachines])) {
            $message = 'Machines config section is incorrect defined or empty in config';
            throw new Exception\InvalidConfigException($message);
        }

        return $config[$this->configKeyMachines];
    }

    /**
     * Gets config resolver
     *
     * @return ResolverInterface
     * @throws Exception\InvalidResolverException
     */
    private function getResolver()
    {
        if(!(array_key_exists($this->configKeyResolver, $this->options) && $this->options[$this->configKeyResolver]))
        {
            $message = 'Resolver class name is not defined in options';
            throw new Exception\InvalidResolverException($message);
        }

        $resolverClassName = $this->options[$this->configKeyResolver];

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
