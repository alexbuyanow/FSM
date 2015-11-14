<?php

namespace FSM\Event;

use FSM\ContextInterface;
use FSM\Machine\MachineInterface;
use FSM\Transition\TransitionInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Event Factory
 *
 * @package FSM\Event
 */
class EventFactory implements EventFactoryInterface
{
    /** @var  EventDispatcherInterface */
    private $eventDispatcher;


    /**
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

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
    public function getEvent(MachineInterface $machine, ContextInterface $context, TransitionInterface $transition = null, $signal = null, array $params = [])
    {
        return new Event($machine, $context, $transition, $signal, $params);
    }

    /**
     * @param EventInterface    $event
     * @param string            $name
     * @return void
     */
    public function dispatchEvent(EventInterface $event, $name)
    {
        $this->eventDispatcher->dispatch($this->getName($name), $event);
    }


    /**
     * Gets name for dispatching
     *
     * @param string $name
     * @return string
     */
    private function getName($name)
    {
        return $name;
    }
}
