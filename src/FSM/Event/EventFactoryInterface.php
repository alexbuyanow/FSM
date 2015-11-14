<?php

namespace FSM\Event;

use FSM\ContextInterface;
use FSM\Machine\MachineInterface;
use FSM\Transition\TransitionInterface;

/**
 * Event factory
 *
 * @package FSM\Event
 */
interface EventFactoryInterface
{
    /**
     * Gets event
     *
     * @param MachineInterface          $machine
     * @param ContextInterface          $context
     * @param TransitionInterface|null  $transition
     * @param string|null               $signal
     * @param array                     $params
     * @return EventInterface
     */
    public function getEvent(MachineInterface $machine, ContextInterface $context, TransitionInterface $transition = null, $signal = null, array $params = []);

    /**
     * @param EventInterface    $event
     * @param string            $name
     * @return void
     */
    public function dispatchEvent(EventInterface $event, $name);
}
