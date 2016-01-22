<?php

namespace FSM\State;

/**
 * State factory interface
 *
 * @package FSM\State
 */
interface StateFactoryInterface
{
    /**
     * Keys in single state config
     */
    const CONFIG_KEY_TYPE = 'type';

    /**
     * State object getting
     *
     * @param string $name
     * @return StateInterface
     */
    public function getState($name);

}
