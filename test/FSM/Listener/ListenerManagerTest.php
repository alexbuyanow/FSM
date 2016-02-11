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
        $listenerMock = $this->getMock(ListenerInterface::class);

        $containerMock = $this->getMock(
            ContainerInterface::class,
            ['get', 'has']
        );
        $containerMock
            ->expects($this->once())
            ->method('get')
            ->with('listener')
            ->willReturn($listenerMock);

        /** @var ContainerInterface $containerMock */
        $manager    = new ListenerManager($containerMock);
        $listener   = $manager->getListener('listener');

        $this->assertInstanceOf(ListenerInterface::class, $listener);
    }

    public function testGetListenerCallable()
    {
        $listenerMock = $this->getMock(ListenerInterface::class);

        $containerMock = $this->getMock(
            ContainerInterface::class,
            ['get', 'has']
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
            ContainerInterface::class,
            ['get', 'has']
        );
        $containerMock
            ->expects($this->once())
            ->method('get')
            ->with('listener')
            ->willThrowException(new \InvalidArgumentException());

        /** @var ContainerInterface $containerMock */
        $manager    = new ListenerManager($containerMock);

        $this->setExpectedException(Exception\ListenerNotFoundException::class);
        $manager->getListener('listener');
    }

    public function testGetListenerNull()
    {
        $containerMock = $this->getMock(
            ContainerInterface::class,
            ['get', 'has']
        );
        $containerMock
            ->expects($this->once())
            ->method('get')
            ->with('listener')
            ->willReturn(null);

        /** @var ContainerInterface $containerMock */
        $manager    = new ListenerManager($containerMock);

        $this->setExpectedException(Exception\ListenerNotFoundException::class);
        $manager->getListener('listener');
    }

    public function testGetListenerNotListener()
    {
        $containerMock = $this->getMock(
            ContainerInterface::class,
            ['get', 'has']
        );
        $containerMock
            ->expects($this->once())
            ->method('get')
            ->with('listener')
            ->willReturn(new \ArrayObject());

        /** @var ContainerInterface $containerMock */
        $manager    = new ListenerManager($containerMock);

        $this->setExpectedException(Exception\InvalidListenerException::class);
        $manager->getListener('listener');
    }
}
