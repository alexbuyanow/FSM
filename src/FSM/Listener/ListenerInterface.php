<?php

namespace FSM\Listener;

use FSM\Event\EventInterface;

/**
 * Listener interface
 *
 * @package FSM\Listener
 */
interface ListenerInterface
{
    /**
     * Event handler
     *
     * @param EventInterface $event
     * @return void
     */
    public function listen(EventInterface $event);
}
