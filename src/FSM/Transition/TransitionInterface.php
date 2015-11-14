<?php

namespace FSM\Transition;

use FSM\State\StateInterface;

/**
 * Transition interface
 *
 * @package FSM\Transition
 */
interface TransitionInterface
{
    /**
     * State from getter
     *
     * @return StateInterface
     */
    public function getStateFrom();

    /**
     * State to getter
     *
     * @return StateInterface
     */
    public function getStateTo();

    /**
     * Is transition direct (without signal)
     *
     * @return boolean
     */
    public function isDirect();

    /**
     * Signal name getter
     *
     * @return string|null
     */
    public function getSignal();

    /**
     * Is transition guarded
     *
     * @return boolean
     */
    public function hasGuard();

    /**
     * Transition guard getter
     *
     * @return string|null
     */
    public function getGuard();

}
