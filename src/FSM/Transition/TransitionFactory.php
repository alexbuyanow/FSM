<?php

namespace FSM\Transition;

use FSM\Guard\GuardManagerInterface;
use FSM\State\StateFactoryInterface;

/**
 * Transition factory
 *
 * @package FSM\Transition
 */
class TransitionFactory implements TransitionFactoryInterface
{
    /** @var  StateFactoryInterface */
    private $stateFactory;

    /** @var  GuardManagerInterface */
    private $guardManager;


    /**
     * @param StateFactoryInterface $stateFactory
     * @param GuardManagerInterface $guardManager
     */
    public function __construct(StateFactoryInterface $stateFactory, GuardManagerInterface $guardManager)
    {
        $this->stateFactory = $stateFactory;
        $this->guardManager = $guardManager;
    }

    /**
     * Transition getting
     *
     * @param array $config
     * @return Transition
     */
    public function getTransition(array $config)
    {
        if (!array_key_exists(static::CONFIG_KEY_STATE_FROM, $config)) {
            $message = 'Transition config has not required key "from"';
            throw new Exception\InvalidTransitionConfig($message);
        }
        if (!array_key_exists(static::CONFIG_KEY_STATE_TO, $config)) {
            $message = 'Transition config has not required key "to"';
            throw new Exception\InvalidTransitionConfig($message);
        }

        $from   = $this->stateFactory->getState($config[static::CONFIG_KEY_STATE_FROM]);
        $to     = $this->stateFactory->getState($config[static::CONFIG_KEY_STATE_TO]);
        $signal = isset($config[static::CONFIG_KEY_SIGNAL]) ? $config[static::CONFIG_KEY_SIGNAL] : null;
        $guard  = isset($config[static::CONFIG_KEY_GUARD]) ? $this->guardManager->getGuardCallable($config[static::CONFIG_KEY_GUARD]) : null;

        return new Transition($from, $to, $signal, $guard);
    }
}
