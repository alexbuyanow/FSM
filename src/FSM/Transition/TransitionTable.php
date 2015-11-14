<?php

namespace FSM\Transition;

use FSM\State\StateInterface;

/**
 * Transitions table
 *
 * @package FSM\Transition
 */
class TransitionTable implements TransitionTableInterface, \Iterator
{

    /** @var TransitionInterface[] */
    private $transitions = [];

    /** @var int */
    private $index = 0;

    /** @var  TransitionFactoryInterface */
    private $transitionFactory;


    /**
     * @param TransitionFactoryInterface $transitionFactory
     * @param array                      $transitionsConfig
     * @throws Exception\InvalidTransitionConfig
     */
    public function __construct(
        TransitionFactoryInterface $transitionFactory,
        array $transitionsConfig
    )
    {
        if(empty($transitionsConfig))
        {
            throw new Exception\InvalidTransitionConfig('Transitions config is empty');
        }

        $this->transitionFactory            = $transitionFactory;

        array_walk(
            $transitionsConfig,
            function(array $singleTransitionConfig)
            {
                $this->addTransition($singleTransitionConfig);
            }
        );
    }

    /**
     * Finding transitions from state and (not necessary) by signal
     *
     * @param  StateInterface $stateFrom
     * @param  string         $signal
     * @return TransitionInterface[]
     */
    public function findTransitions(StateInterface $stateFrom, $signal)
    {
        return array_filter(
            $this->transitions,
            function(TransitionInterface $transition) use($stateFrom, $signal)
            {
                return $transition->getStateFrom()->isIdentical($stateFrom) && $transition->getSignal() == $signal;
            }
        );
    }

    /**
     * @{inheritDoc}
     *
     * @return TransitionInterface
     */
    public function current()
    {
        return $this->transitions[$this->index];
    }

    /**
     * @{inheritDoc}
     *
     * @return void
     */
    public function next()
    {
        ++$this->index;
    }

    /**
     * @{inheritDoc}
     *
     * @return int
     */
    public function key()
    {
        return $this->index;
    }

    /**
     * @{inheritDoc}
     *
     * @return bool
     */
    public function valid()
    {
        return array_key_exists($this->index, $this->transitions);
    }

    /**
     * @{inheritDoc}
     *
     * @return void
     */
    public function rewind()
    {
        $this->index = 0;
    }


    /**
     * Table filling
     *
     * @param array $transitionConfig
     * @return void
     */
    private function addTransition(array $transitionConfig)
    {
        $this->transitions[] = $this->transitionFactory->getTransition($transitionConfig);
    }

}
