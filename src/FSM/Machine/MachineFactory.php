<?php

namespace FSM\Machine;

use FSM\Container\ContainerInterface;
use FSM\Event\EventFactory;
use FSM\Event\EventFactoryInterface;
use FSM\Guard\GuardManager;
use FSM\Guard\GuardManagerInterface;
use FSM\Listener\ListenerManager;
use FSM\Listener\ListenerManagerInterface;
use FSM\State\StateFactory;
use FSM\State\StateFactoryInterface;
use FSM\Transition\TransitionFactory;
use FSM\Transition\TransitionFactoryInterface;
use FSM\Transition\TransitionTable;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Concrete machine factory
 *
 * @package FSM\Machine
 */
class MachineFactory implements MachineFactoryInterface
{
    /** @var  array */
    private $options;

    /** @var  array */
    private $machines = [];


    /**
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->options = $options;
    }

    /**
     * Gets machine
     *
     * @param string             $name
     * @param array              $config
     * @param ContainerInterface $container
     * @return Machine
     */
    public function getMachine($name, array $config, ContainerInterface $container)
    {
        if(!array_key_exists($name, $this->machines))
        {
            $statesFactory      = $this->getStatesFactory($this->getSubConfig($name, static::CONFIG_KEY_STATES, $config));
            $guardsFactory      = $this->getGuardsFactory($container);
            $transitionFactory  = $this->getTransitionsFactory($statesFactory, $guardsFactory);
            $transitionTable    = $this->getTransitionsTable($transitionFactory, $this->getSubConfig($name, static::CONFIG_KEY_TRANSITIONS, $config));
            $eventDispatcher    = $this->getEventDispatcher();
            $eventFactory       = $this->getEventFactory($eventDispatcher);
            $listenerManager    = $this->getListenerManager($container);

            $this->initListeners($eventDispatcher, $listenerManager, $this->getSubConfig($name, static::CONFIG_KEY_LISTENERS, $config));

            $this->machines[$name] = new Machine(
                $name,
                $transitionTable,
                $statesFactory,
                $eventFactory,
                $this->options
            );
        }

        return $this->machines[$name];
    }


    /**
     * Gets config value/section by key
     *
     * @param string $machineName
     * @param string $key
     * @param array  $config
     * @return array
     */
    private function getSubConfig($machineName, $key, array $config)
    {
        if(!array_key_exists($key, $config))
        {
            $message = sprintf('Not found section "%s" in config for machine "%s"', $key, $machineName);
            throw new Exception\InvalidConfigException($message);
        }

        return $config[$key];
    }

    /**
     * Gets states factory by config
     *
     * @param array $config
     * @return StateFactoryInterface
     */
    private function getStatesFactory(array $config)
    {
        return new StateFactory($config);
    }

    /**
     * Gets transitions factory by config
     *
     * @param StateFactoryInterface $stateFactory
     * @param GuardManagerInterface $guardsFactory
     * @return TransitionFactoryInterface
     */
    private function getTransitionsFactory(StateFactoryInterface $stateFactory, GuardManagerInterface $guardsFactory)
    {
        return new TransitionFactory($stateFactory, $guardsFactory);
    }

    /**
     * Gets transitions table
     *
     * @param TransitionFactoryInterface $transitionsFactory
     * @param array                      $config
     * @return TransitionTable
     */
    private function getTransitionsTable(TransitionFactoryInterface $transitionsFactory, array $config)
    {
        return new TransitionTable($transitionsFactory, $config);
    }

    /**
     * Gets event dispatcher
     *
     * @return EventDispatcherInterface
     */
    private function getEventDispatcher()
    {
        return new EventDispatcher();
    }

    /**
     * Get guards factory
     *
     * @param ContainerInterface $container
     * @return GuardManager
     */
    private function getGuardsFactory(ContainerInterface $container)
    {
        return new GuardManager($container);
    }

    /**
     * Gets events factory
     *
     * @param EventDispatcherInterface $eventDispatcher
     * @return EventFactoryInterface
     */
    private function getEventFactory(EventDispatcherInterface $eventDispatcher)
    {
        return new EventFactory($eventDispatcher);
    }

    /**
     * Gets listener manager
     *
     * @param ContainerInterface $container
     * @return ListenerManagerInterface
     */
    private function getListenerManager(ContainerInterface $container)
    {
        return new ListenerManager($container);
    }

    private function initListeners(EventDispatcherInterface $eventDispatcher, ListenerManagerInterface $listenerManager, array $config)
    {
        array_walk(
            $config,
            function(array $listenerConfig) use($eventDispatcher, $listenerManager)
            {
                $listenerEvent      = $listenerConfig[ListenerManager::CONFIG_KEY_EVENT];
                $listenerName       = $listenerConfig[ListenerManager::CONFIG_KEY_LISTENER];

                $eventDispatcher->addListener(
                    $listenerEvent,
                    $listenerManager->getListenerCallable($listenerName)
                );
            }
        );
    }
}
