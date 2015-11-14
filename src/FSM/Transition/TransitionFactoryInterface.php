<?php

namespace FSM\Transition;

/**
 * Interface Transition factory interface
 *
 * @package FSM\Transition
 */
interface TransitionFactoryInterface
{
    /**
     * Transition getting
     *
     * @param array $config
     * @return Transition
     */
    public function getTransition(array $config);
}
