<?php

namespace FSM\Event;

use FSM\ContextInterface;
use FSM\Machine\MachineInterface;
use FSM\Transition\TransitionInterface;
use Symfony\Component\EventDispatcher\Event as PrototypeEvent;

/**
 * Machine Event
 *
 * @package FSM\Event
 */
class Event extends PrototypeEvent implements EventInterface
{
    /** @var  MachineInterface */
    private $machine;

    /** @var  ContextInterface */
    private $context;

    /** @var  TransitionInterface */
    private $transition;

    /** @var  string */
    private $signal;

    /** @var array */
    private $params = [];


    /**
     * @param MachineInterface $machine
     * @param ContextInterface $context
     * @param TransitionInterface|null $transition
     * @param string|null $signal
     * @param array $params
     */
    public function __construct(
        MachineInterface $machine,
        ContextInterface $context,
        TransitionInterface $transition = null,
        $signal = null,
        array $params = []
    )
    {
        $this->machine    = $machine;
        $this->context    = $context;
        $this->transition = $transition;
        $this->signal     = $signal;
        $this->params     = $params;
    }

    /**
     * Gets machine throwing event
     *
     * @return MachineInterface
     */
    public function getMachine()
    {
        return $this->machine;
    }

    /**
     * Gets machine context
     *
     * @return ContextInterface
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Gets transition
     *
     * @return TransitionInterface|null
     */
    public function getTransition()
    {
        return $this->transition;
    }

    /**
     * Gets signal
     *
     * @return string|null
     */
    public function getSignal()
    {
        return $this->signal;
    }

    /**
     * Gets additional parameters
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }


}
