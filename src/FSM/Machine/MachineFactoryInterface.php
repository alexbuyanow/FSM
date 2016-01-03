<?php

namespace FSM\Machine;

/**
 * Concrete machine factory interface
 *
 * @package FSM\Machine
 */
interface MachineFactoryInterface
{
    /**
     * Machine config sections keys
     */
    const CONFIG_KEY_STATES         = 'states';
    const CONFIG_KEY_TRANSITIONS    = 'transitions';
    const CONFIG_KEY_GUARDS         = 'guards';
    const CONFIG_KEY_LISTENERS      = 'listeners';


    /**
     * Gets machine
     *
     * @return MachineInterface
     */
    public function getMachine();
}
