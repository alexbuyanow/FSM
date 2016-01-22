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

        $this->assertInstanceOf(EventInterface::class, $event);
        $this->assertInstanceOf(MachineInterface::class, $event->getMachine());
        $this->assertInstanceOf(ContextInterface::class, $event->getContext());
        $this->assertInstanceOf(TransitionInterface::class, $event->getTransition());
        $this->assertEquals('test_signal', $event->getSignal());
        $this->assertInternalType('array', $event->getParams());
    }

    public function testDispatchEvent()
    {
        /** @var EventInterface $eventMock */
        $eventMock      = $this->getMock(Event::class, [], [], '', false);
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
            EventDispatcherInterface::class,
            ['dispatch', 'addListener', 'addSubscriber', 'removeListener', 'getListeners', 'hasListeners', 'removeSubscriber']
        );

        return $mock;
    }

    /**
     * @return MachineInterface
     */
    private function getMachineMock()
    {
        $mock = $this->getMock(MachineInterface::class);

        return $mock;
    }

    /**
     * @return ContextInterface
     */
    private function getContextMock()
    {
        $mock = $this->getMock(ContextInterface::class);

        return $mock;
    }

    /**
     * @return TransitionInterface
     */
    private function getTransitionMock()
    {
        $mock = $this->getMock(TransitionInterface::class);

        return $mock;
    }
}
