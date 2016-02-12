<?php

namespace FSM\Transition;

/**
 * Interface Transition factory interface
 *
 * @package FSM\Transition
 */
interface TransitionFactoryInterface
{
    const CONFIG_KEY_STATE_FROM = 'from';
    const CONFIG_KEY_STATE_TO   = 'to';
    const CONFIG_KEY_SIGNAL     = 'signal';
    const CONFIG_KEY_GUARD      = 'guard';

    /**
     * Transition getting
     *
     * @param array $config
     * @return Transition
     */
    public function getTransition(array $config);
}
