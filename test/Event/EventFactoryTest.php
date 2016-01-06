<?php

namespace FSM\Event;

use FSM\ContextInterface;
use FSM\Machine\MachineInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class EventFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testGetEvent()
    {
        $factory     = new EventFactory($this->getEventDispatcherMock());
        $machineMock = $this->getMachineMock();
        $contextMock = $this->getContextMock();
        $event       = $factory->getEvent($machineMock, $contextMock);

        $this->assertInstanceOf('FSM\Event\EventInterface', $event);
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
}
