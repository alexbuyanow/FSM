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
     * State object getting
     *
     * @param string $name
     * @return StateInterface
     */
    public function getState($name);

}
