<?php

namespace FSM\Event;

use FSM\ContextInterface;
use FSM\Machine\MachineInterface;
use FSM\Transition\TransitionInterface;

/**
 * Machine event interface
 *
 * @package FSM\Event
 */
interface EventInterface
{
    const MACHINE_REFRESH_PRE       = 'fsm.pre.refresh.machine';
    const MACHINE_REFRESH_POST      = 'fsm.post.refresh.machine';
    const SIGNAL_PRE                = 'fsm.pre.signal.';
    const SIGNAL_POST               = 'fsm.post.signal.';
    const TRANSITION_PRE            = 'fsm.pre.transition.';
    const TRANSITION_POST           = 'fsm.post.transition.';
    const STATE_EXIT                = 'fsm.exit.state.';
    const STATE_ENTRY               = 'fsm.entry.state.';

    /**
     * Gets machine throwing event
     *
     * @return MachineInterface
     */
    public function getMachine();

    /**
     * Gets machine context
     *
     * @return ContextInterface
     */
    public function getContext();

    /**
     * Gets transition
     *
     * @return TransitionInterface|null
     */
    public function getTransition();

    /**
     * Gets signal
     *
     * @return string|null
     */
    public function getSignal();

    /**
     * Gets additional parameters
     *
     * @return array
     */
    public function getParams();
}
