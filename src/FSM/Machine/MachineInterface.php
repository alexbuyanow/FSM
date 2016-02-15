<?php

namespace FSM\Machine;

use FSM\ContextInterface;

/**
 * Machine interface
 *
 * @package FSM\Machine
 */
interface MachineInterface
{
    /**
     * Strict options for machine behavior
     */
    const STRICT_NONEXISTENT_SIGNAL              = 0x01; //Alert if undefined signal
    const STRICT_SIMULTANEOUS_SIGNAL_TRANSITIONS = 0x02; //Alert if several possible signal transitions
    const STRICT_SIMULTANEOUS_DIRECT_TRANSITIONS = 0x04; //Alert if several possible direct transitions
    const STRICT_NONE                            = 0x00;
    const STRICT_ALL                             = 0xFF;


    /**
     * Refreshs contexts state
     *
     * @param ContextInterface $context
     * @return void
     */
    public function refresh(ContextInterface $context);

    /**
     * Sends signal to machine for context
     *
     * @param ContextInterface $context
     * @param string           $signal
     * @param array            $params
     * @return void
     */
    public function signal(ContextInterface $context, $signal, array $params = []);

    /**
     * Check if signal allowed for current context state
     *
     * @param ContextInterface $context
     * @param string           $signal
     * @return boolean
     */
    public function isSignalAllowed(ContextInterface $context, $signal);
}
