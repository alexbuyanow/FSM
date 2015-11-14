<?php

namespace FSM\Container;

/**
 * DI container interface
 *
 * @package FSM\Container
 */
interface ContainerInterface
{
    /**
     * Gets a parameter or an object
     *
     * @param string $name
     * @return mixed
     */
    public function get($name);
}
