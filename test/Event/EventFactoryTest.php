<?php

namespace FSM\Event;

use FSM\ContextInterface;
use FSM\Machine\MachineInterface;
use FSM\Transition\TransitionInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class EventFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testGetEvent()
    {
        $factory        = new EventFactory($this->getEventDispatcherMock());
        $machineMock    = $this->getMachineMock();
        $contextMock    = $this->getContextMock();
        $transitionMock = $this->getTransitionMock();
        $event          = $factory->getEvent($machineMock, $contextMock, $transitionMock, 'test_signal');

        $this->assertInstanceOf('FSM\Event\EventInterface', $event);
        $this->assertInstanceOf('FSM\Machine\MachineInterface', $event->getMachine());
        $this->assertInstanceOf('FSM\ContextInterface', $event->getContext());
        $this->assertInstanceOf('FSM\Transition\TransitionInterface', $event->getTransition());
        $this->assertEquals('test_signal', $event->getSignal());
        $this->assertInternalType('array', $event->getParams());
    }

    public function testDispatchEvent()
    {
        /** @var EventInterface $eventMock */
        $eventMock      = $this->getMock('FSM\Event\Event', [], [], '', false);
        $dispatcherMock = $this->getEventDispatcherMock();
        $dispatcherMock
            ->expects($this->once())
            ->method('dispatch')
            ->willReturn($eventMock);

        $factory     = new EventFactory($dispatcherMock);

        $factory->dispatchEvent($eventMock, 'test_event');

    }

    /**
     * @return EventDispatcherInterface
     */
    private function getEventDispatcherMock()
    {
        $mock = $this->getMock(
            'Symfony\Component\EventDispatcher\EventDispatcherInterface',
            ['dispatch', 'addListener', 'addSubscriber', 'removeListener', 'getListeners', 'hasListeners', 'removeSubscriber']
        );

        return $mock;
    }

    /**
     * @return MachineInterface
     */
    private function getMachineMock()
    {
        $mock = $this->getMock(
            'FSM\Machine\MachineInterface'
        );

        return $mock;
    }

    /**
     * @return ContextInterface
     */
    private function getContextMock()
    {
        $mock = $this->getMock(
            'FSM\ContextInterface'
        );

        return $mock;
    }

    /**
     * @return TransitionInterface
     */
    private function getTransitionMock()
    {
        $mock = $this->getMock(
            'FSM\Transition\TransitionInterface'
        );

        return $mock;
    }
}
