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
    /** @var  string */
    private $name;

    /** @var  array */
    private $options;

    /** @var StateFactory */
    private $statesFactory;

    /** @var TransitionTable */
    private $transitionsTable;

    /** @var  EventDispatcherInterface */
    private $eventDispatcher;

    /** @var  EventFactoryInterface */
    private $eventFactory;

    /** @var  ContainerInterface */
    private $container;

    /** @var  GuardManagerInterface */
    private $guardsFactory;


    /**
     * @param string             $name
     * @param array              $config
     * @param array              $options
     * @param ContainerInterface $container
     */
    public function __construct($name, array $config, array $options, ContainerInterface $container)
    {
        $this->name                         = $name;
        $this->options                      = $options;
        $this->container                    = $container;
        $this->statesFactory                = $this->getStatesFactory($config);
        $this->guardsFactory                = $this->getGuardsFactory();
        $this->transitionsTable             = $this->getTransitionsTable($this->getTransitionsFactory($this->statesFactory, $this->guardsFactory), $config);
        $this->eventDispatcher              = $this->getEventDispatcher();
        $this->eventFactory                 = $this->getEventFactory($this->eventDispatcher);

        $this->initListeners($this->eventDispatcher, $this->getListenerFactory($this->container), $config);

    }

    /**
     * Gets machine
     *
     * @return MachineInterface
     */
    public function getMachine()
    {
        return new Machine(
            $this->name,
            $this->transitionsTable,
            $this->statesFactory,
            $this->eventFactory,
            $this->options
        );
    }


    /**
     * Gets config value/section by key
     *
     * @param string $key
     * @param array  $config
     * @return array
     */
    private function getSubConfig($key, array $config)
    {
        if(!array_key_exists($key, $config))
        {
            $message = sprintf('Not found section "%s" in config for machine "%s"', $key, $this->name);
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
        $config = $this->getSubConfig(self::CONFIG_KEY_STATES, $config);
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
        $config = $this->getSubConfig(self::CONFIG_KEY_TRANSITIONS, $config);
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
     * @return GuardManagerInterface
     */
    private function getGuardsFactory()
    {
        return new GuardManager($this->container);
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
     * Gets listener factory
     *
     * @param ContainerInterface $container
     * @return ListenerManagerInterface
     */
    private function getListenerFactory(ContainerInterface $container)
    {
        return new ListenerManager($container);
    }

    private function initListeners(EventDispatcherInterface $eventDispatcher, ListenerManagerInterface $listenerFactory, array $config)
    {
        $config = $this->getSubConfig(self::CONFIG_KEY_LISTENERS, $config);

        array_walk(
            $config,
            function(array $listenerConfig) use($eventDispatcher, $listenerFactory)
            {
                $listenerEvent      = $listenerConfig['event'];
                $listenerName       = $listenerConfig['listener'];

                $eventDispatcher->addListener(
                    $listenerEvent,
                    $listenerFactory->getListenerCallable($listenerName)
                );
            }
        );
    }
}
