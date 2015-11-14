<?php

namespace FSM\Guard;

/**
 * Guard factory interface
 *
 * @package FSM\Guard
 */
interface GuardManagerInterface
{
    /**
     * Gets named guard object
     *
     * @param string $name
     * @return GuardInterface
     */
    public function getGuard($name);

    /**
     * Gets callable for named guard object
     *
     * @param string $name
     * @return callable
     */
    public function getGuardCallable($name);
}
