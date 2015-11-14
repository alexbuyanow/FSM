<?php

namespace FSM\Transition;

use FSM\State\StateInterface;

/**
 * Transitions table interface
 *
 * @package FSM\Transition
 */
interface TransitionTableInterface
{

    /**
     * Finding transitions from state and (not necessary) by signal
     *
     * @param  StateInterface $stateFrom
     * @param  string|null    $signal
     * @return TransitionInterface[]
     */
    public function findTransitions(StateInterface $stateFrom, $signal);
}
