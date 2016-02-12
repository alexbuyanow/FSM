<?php

namespace FSM\Transition;

use FSM\State\StateInterface;

/**
 * Transition
 *
 * @package FSM\Transition
 */
class Transition implements TransitionInterface
{
    /** @var  StateInterface */
    private $stateFrom;

    /** @var  StateInterface */
    private $stateTo;

    /** @var string */
    private $signal;

    /** @var callable */
    private $guard;


    /**
     * @param StateInterface $stateFrom
     * @param StateInterface $stateTo
     * @param string|null    $signal
     * @param callable|null  $guard
     */
    public function __construct(StateInterface $stateFrom, StateInterface $stateTo, $signal = null, $guard = null)
    {
        $this->stateFrom = $stateFrom;
        $this->stateTo   = $stateTo;
        $this->signal    = $signal;
        $this->guard     = $guard;
    }

    /**
     * State from getter
     *
     * @return StateInterface
     */
    public function getStateFrom()
    {
        return $this->stateFrom;
    }

    /**
     * State to getter
     *
     * @return StateInterface
     */
    public function getStateTo()
    {
        return $this->stateTo;
    }

    /**
     * Signal name getter
     *
     * @return string|null
     */
    public function getSignal()
    {
        return $this->signal;
    }

    /**
     * Is transition direct (without signal)
     *
     * @return boolean
     */
    public function isDirect()
    {
        return !is_null($this->signal);
    }

    /**
     * Is transition guarded
     *
     * @return boolean
     */
    public function hasGuard()
    {
        return !is_null($this->guard);
    }

    /**
     * Transition guard getter
     *
     * @return callable|null
     */
    public function getGuard()
    {
        return $this->guard;
    }

}
