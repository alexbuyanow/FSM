<?php

namespace FSM\Machine;

use FSM\ContextInterface;
use FSM\Event\Event;
use FSM\Event\EventFactoryInterface;
use FSM\Queue\QueueInterface;
use FSM\Queue\SimpleQueue;
use FSM\State\StateFactoryInterface;
use FSM\State\StateInterface;
use FSM\Transition\TransitionInterface;
use FSM\Transition\TransitionTableInterface;

/**
 * Machine
 *
 * @package FSM\Machine
 */
class Machine implements MachineInterface
{

    /** @var  string */
    private $name;

    /** @var  array */
    private $options;

    /** @var  TransitionTableInterface */
    private $transitionsTable;

    /** @var  StateFactoryInterface */
    private $statesFactory;

    /** @var  EventFactoryInterface */
    private $eventFactory;

    /** @var array  */
    private $performsRegistry = [];

    /** @var array */
    private $signalQueuesRegistry = [];


    /**
     * @param string                        $name
     * @param TransitionTableInterface      $transitionsTable
     * @param StateFactoryInterface         $statesFactory
     * @param EventFactoryInterface         $eventFactory
     * @param array                         $options
     */
    public function __construct(
        $name,
        TransitionTableInterface $transitionsTable,
        StateFactoryInterface $statesFactory,
        EventFactoryInterface $eventFactory,
        array $options
    )
    {
        $this->name                 = $name;
        $this->transitionsTable     = $transitionsTable;
        $this->statesFactory        = $statesFactory;
        $this->eventFactory         = $eventFactory;
        $this->options              = $options;
    }

    /**
     * Refreshes contexts state
     *
     * Refresh performs all queued transitions for current context
     * If machine for this context in refresh mode already, return doing nothing, for recursion prevention
     *
     * @param ContextInterface $context
     * @return void
     */
    public function refresh(ContextInterface $context)
    {
        $contextId = $this->getContextId($context);

        if(isset($this->performsRegistry[$contextId]))
        {
            return;
        }

        $this->performsRegistry[$contextId] = $contextId;

        $this->triggerEvent(Event::MACHINE_REFRESH_PRE, $context);

        $this->performDirectTransitions($context);
        $this->performSignalTransitions($context);

        $this->triggerEvent(Event::MACHINE_REFRESH_POST, $context);

        unset($this->performsRegistry[$contextId]);
    }

    /**
     * Sends signal to machine for context
     *
     * @param ContextInterface $context
     * @param string           $signal
     * @param array            $params
     * @return void
     */
    public function signal(ContextInterface $context, $signal, array $params = [])
    {
        $this->refresh($context);
        $this->enqueueSignal($context, $signal, $params);
        $this->refresh($context);
    }

    /**
     * Check if signal allowed for current context state
     *
     * @param ContextInterface $context
     * @param string           $signal
     * @return boolean
     */
    public function isSignalAllowed(ContextInterface $context, $signal)
    {
        return !is_null($this->findAllowedTransition($context, $signal));
    }


    /**
     * Gets machine UID
     *
     * @return string
     */
    private function getName()
    {
        return $this->name;
    }

    /**
     * Performs direct (without possible signal) transitions
     *
     * @param ContextInterface $context
     * @return void
     */
    private function performDirectTransitions(ContextInterface $context)
    {
        while(($transition = $this->findAllowedTransition($context, null)))
        {
            $this->performTransition($context, $transition, []);
        }
    }

    /**
     * Performs by signal transitions
     *
     * @param ContextInterface $context
     * @return void
     */
    private function performSignalTransitions(ContextInterface $context)
    {
        while(($signalData = $this->dequeueSignal($context)))
        {
            $this->performSignal($context, $signalData['signal'], $signalData['params']);
            $this->performDirectTransitions($context);
        }
    }

    /**
     * Performs single monatomic transition
     *
     * @param ContextInterface    $context
     * @param TransitionInterface $transition
     * @param array               $params
     * @return void
     */
    private function performTransition(ContextInterface $context, TransitionInterface $transition, array $params)
    {
        $this->triggerEvent(Event::TRANSITION_PRE, $context, $transition, null, $params);
        $this->triggerEvent(Event::STATE_EXIT, $context, $transition, null, $params);

        $this->setContextState($context, $transition->getStateTo());

        $this->triggerEvent(Event::STATE_ENTRY, $context, $transition, null, $params);
        $this->triggerEvent(Event::TRANSITION_POST, $context, $transition, null, $params);
    }

    /**
     * Gets all finding transitions from this state by signal
     *
     * If signal is null, gets all direct transitions from this state
     *
     * @param StateInterface $stateFrom
     * @param string         $signal
     * @return \FSM\Transition\TransitionInterface[]
     */
    private function getTransitions(StateInterface $stateFrom, $signal)
    {
        return $this->transitionsTable->findTransitions($stateFrom, $signal);
    }

    /**
     * Finds allowed current context state transition by signal (or direct transition, if signal is null)
     *
     * @param ContextInterface $context
     * @param string           $signal
     * @return TransitionInterface
     * @throws Exception\StrictException
     */
    private function findAllowedTransition(ContextInterface $context, $signal)
    {
        $contextState = $this->getContextState($context);
        $transitions = $this->getTransitions($contextState, $signal);

        $transitions = array_filter(
            $transitions,
            function(TransitionInterface $transition) use($context)
            {
                return $this->isTransitionAllowed($context, $transition);
            }
        );

        $countTransitions = count($transitions);

        if($countTransitions == 0)
        {
            return null;
        }

        if(($this->options['strict'] & self::STRICT_SIMULTANEOUS_SIGNAL_TRANSITIONS) && count($transitions) > 1)
        {
            $signalText = sprintf('by signal "%s"', $signal);
            $message = sprintf(
                'There are more than one transitions from state "%s" %s for machine "%s"',
                $contextState->getName(),
                is_null($signal) ? 'directly' : $signalText,
                $this->getName()
            );
            throw new Exception\StrictException($message);
        }

        $transition = reset($transitions);
        return $transition;

    }

    /**
     * Checks is transition allowed (by guards etc)
     *
     * @param ContextInterface    $context
     * @param TransitionInterface $transition
     * @return bool
     */
    private function isTransitionAllowed(ContextInterface $context, TransitionInterface $transition)
    {
        if(!$transition->hasGuard())
        {
            return true;
        }

        return call_user_func($transition->getGuard(), $context);
    }

    /**
     * Enqueues signal
     *
     * @param ContextInterface $context
     * @param string           $signal
     * @param array            $params
     * @return void
     */
    private function enqueueSignal(ContextInterface $context, $signal, array $params = [])
    {
        $this->getSignalQueue($context)->enqueue(['signal' => $signal, 'params' => $params]);
    }

    /**
     * Dequeues signal
     *
     * @param ContextInterface $context
     * @return array|null
     */
    private function dequeueSignal(ContextInterface $context)
    {
        $queue = $this->getSignalQueue($context);

        if($queue->count() == 0)
        {
            return null;
        }

        $signalData = $queue->dequeue();
        return $signalData;
    }

    /**
     * Performs current signal
     *
     * @param ContextInterface $context
     * @param string           $signal
     * @param array            $params
     * @return void
     * @throws Exception\StrictException
     */
    private function performSignal(ContextInterface $context, $signal, array $params = [])
    {
        $transition = $this->findAllowedTransition($context, $signal);

        if(empty($transition))
        {
            if($this->options['strict'] & self::STRICT_NONEXISTENT_SIGNAL)
            {
                $message = sprintf(
                    'Transition from state "%s" by signal "%s" not found for machine "%s"',
                    $this->getContextState($context)->getName(),
                    $signal,
                    $this->getName()
                );
                throw new Exception\StrictException($message);
            }
            return;
        }

        $this->triggerEvent(Event::SIGNAL_PRE, $context, null, $signal, $params);
        $this->performTransition($context, $transition, $params);
        $this->triggerEvent(Event::SIGNAL_POST, $context, null, $signal, $params);
    }

    /**
     * Internal machine context UID getter
     *
     * @param ContextInterface $context
     * @return string
     */
    private function getContextId(ContextInterface $context)
    {
        return $context->getContextUid();
    }

    /**
     * Internal machine context state getter
     *
     * @param ContextInterface $context
     * @return StateInterface
     */
    private function getContextState(ContextInterface $context)
    {
        return $this->statesFactory->getState($context->getContextState());
    }

    /**
     * Internal machine context state setter
     *
     * @param ContextInterface $context
     * @param StateInterface   $state
     * @return void
     */
    private function setContextState(ContextInterface $context, StateInterface $state)
    {
        $context->setContextState($state->getName());
    }

    /**
     * Gets (or creates if null) signals queue for context
     *
     * @param ContextInterface $context
     * @return QueueInterface
     */
    private function getSignalQueue(ContextInterface $context)
    {
        $contextId = $this->getContextId($context);

        if(!isset($this->signalQueuesRegistry[$contextId]))
        {
            $this->signalQueuesRegistry[$contextId] = new SimpleQueue();
        }

        return $this->signalQueuesRegistry[$contextId];
    }

    /**
     * Triggers machine event
     *
     * @param string                    $name
     * @param ContextInterface          $context
     * @param TransitionInterface|null  $transition
     * @param string|null               $signal
     * @param array                     $params
     * @return void
     */
    private function triggerEvent($name, ContextInterface $context, TransitionInterface $transition = null, $signal = null, array $params = [])
    {
        $event = $this->eventFactory->getEvent($this, $context, $transition, $signal, $params);
        $this->eventFactory->dispatchEvent($event, $name);
    }

}
