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
    private $guardFactory;


    /**
     * @param StateFactoryInterface $stateFactory
     * @param GuardManagerInterface $guardFactory
     */
    public function __construct(StateFactoryInterface $stateFactory, GuardManagerInterface $guardFactory)
    {
        $this->stateFactory = $stateFactory;
        $this->guardFactory = $guardFactory;
    }

    /**
     * Transition getting
     *
     * @param array $config
     * @return Transition
     */
    public function getTransition(array $config)
    {
        if (!array_key_exists('from', $config)) {
            $message = 'Transition config has not required key "from"';
            throw new Exception\InvalidTransitionConfig($message);
        }
        if (!array_key_exists('to', $config)) {
            $message = 'Transition config has not required key "to"';
            throw new Exception\InvalidTransitionConfig($message);
        }

        $from   = $this->stateFactory->getState($config['from']);
        $to     = $this->stateFactory->getState($config['to']);
        $signal = isset($config['signal']) ? $config['signal'] : null;
        $guard  = isset($config['guard']) ? $this->guardFactory->getGuardCallable($config['guard']) : null;

        return new Transition($from, $to, $signal, $guard);
    }
}
