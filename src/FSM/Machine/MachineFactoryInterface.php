<?php

namespace FSM\Machine;

use FSM\Container\ContainerInterface;

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
     * @param string             $name
     * @param array              $config
     * @param ContainerInterface $container
     * @return Machine
     */
    public function getMachine($name, array $config, ContainerInterface $container);
}
