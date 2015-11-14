<?php

namespace FSM\Listener;

use FSM\Container\ContainerInterface;

/**
 * Listeners manager tests
 *
 * @package FSM\Listener
 */
class ListenerManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testGetListener()
    {
        $listenerMock = $this->getMock('FSM\Listener\ListenerInterface');

        $containerMock = $this->getMock(
            'FSM\Container\ContainerInterface',
            ['get']
        );
        $containerMock
            ->expects($this->once())
            ->method('get')
            ->with('listener')
            ->willReturn($listenerMock);

        /** @var ContainerInterface $containerMock */
        $manager    = new ListenerManager($containerMock);
        $listener   = $manager->getListener('listener');

        $this->assertInstanceOf('FSM\Listener\ListenerInterface', $listener);
    }

    public function testGetListenerCallable()
    {
        $listenerMock = $this->getMock('FSM\Listener\ListenerInterface');

        $containerMock = $this->getMock(
            'FSM\Container\ContainerInterface',
            ['get']
        );
        $containerMock
            ->expects($this->once())
            ->method('get')
            ->with('listener')
            ->willReturn($listenerMock);

        /** @var ContainerInterface $containerMock */
        $manager    = new ListenerManager($containerMock);

        $this->assertTrue(is_callable($manager->getListenerCallable('listener')));
    }

    public function testGetListenerNotFound()
    {
        $containerMock = $this->getMock(
            'FSM\Container\ContainerInterface',
            ['get']
        );
        $containerMock
            ->expects($this->once())
            ->method('get')
            ->with('listener')
            ->willThrowException(new \InvalidArgumentException());

        /** @var ContainerInterface $containerMock */
        $manager    = new ListenerManager($containerMock);

        $this->setExpectedException(__NAMESPACE__ . '\Exception\ListenerNotFoundException');
        $manager->getListener('listener');
    }

    public function testGetListenerNull()
    {
        $containerMock = $this->getMock(
            'FSM\Container\ContainerInterface',
            ['get']
        );
        $containerMock
            ->expects($this->once())
            ->method('get')
            ->with('listener')
            ->willReturn(null);

        /** @var ContainerInterface $containerMock */
        $manager    = new ListenerManager($containerMock);

        $this->setExpectedException(__NAMESPACE__ . '\Exception\ListenerNotFoundException');
        $manager->getListener('listener');
    }

    public function testGetListenerNotListener()
    {
        $containerMock = $this->getMock(
            'FSM\Container\ContainerInterface',
            ['get']
        );
        $containerMock
            ->expects($this->once())
            ->method('get')
            ->with('listener')
            ->willReturn(new \ArrayObject());

        /** @var ContainerInterface $containerMock */
        $manager    = new ListenerManager($containerMock);

        $this->setExpectedException(__NAMESPACE__ . '\Exception\InvalidListenerException');
        $manager->getListener('listener');
    }
}
