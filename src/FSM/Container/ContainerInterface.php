<?php

namespace FSM\Container;

/**
 * DI container interface
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

    /**
     * Check if object sets in container
     *
     * @param string $name
     * @return boolean
     */
    public function has($name);
}
