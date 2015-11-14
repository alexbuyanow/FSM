<?php

namespace FSM\Listener;

/**
 * Listener manager interface
 *
 * @package FSM\Guard
 */
interface ListenerManagerInterface
{
    /**
     * Gets named listener object
     *
     * @param string $name
     * @return ListenerInterface
     */
    public function getListener($name);

    /**
     * Gets callable for named listener object
     *
     * @param string $name
     * @return callable
     */
    public function getListenerCallable($name);
}
