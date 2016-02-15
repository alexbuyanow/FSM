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
    private $listener_name = 'listener';

    public function testGetListener()
    {
        $listenerMock = $this->getMock(ListenerInterface::class);

        $containerMock = $this->getMock(
            ContainerInterface::class,
            ['get', 'has']
        );
        $containerMock
            ->expects($this->once())
            ->method('has')
            ->with($this->listener_name)
            ->will($this->returnValue(true));
        $containerMock
            ->expects($this->once())
            ->method('get')
            ->with($this->listener_name)
            ->will($this->returnValue($listenerMock));

        /** @var ContainerInterface $containerMock */
        $manager    = new ListenerManager($containerMock);
        $listener   = $manager->getListener($this->listener_name);

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
            ->method('has')
            ->with($this->listener_name)
            ->will($this->returnValue(true));
        $containerMock
            ->expects($this->once())
            ->method('get')
            ->with($this->listener_name)
            ->will($this->returnValue($listenerMock));

        /** @var ContainerInterface $containerMock */
        $manager    = new ListenerManager($containerMock);

        $this->assertTrue(is_callable($manager->getListenerCallable($this->listener_name)));
    }

    public function testGetListenerNotFound()
    {
        $containerMock = $this->getMock(
            ContainerInterface::class,
            ['get', 'has']
        );
        $containerMock
            ->expects($this->once())
            ->method('has')
            ->with($this->anything())
            ->will($this->returnValue(false));

        /** @var ContainerInterface $containerMock */
        $manager    = new ListenerManager($containerMock);

        $this->setExpectedException(Exception\ListenerNotFoundException::class);
        $manager->getListener($this->listener_name);
    }

    public function testGetListenerIsNotImplementListenerInterface()
    {
        $containerMock = $this->getMock(
            ContainerInterface::class,
            ['get', 'has']
        );
        $containerMock
            ->expects($this->once())
            ->method('has')
            ->with($this->listener_name)
            ->will($this->returnValue(true));
        $containerMock
            ->expects($this->once())
            ->method('get')
            ->with($this->listener_name)
            ->will($this->returnValue($this->anything()));

        /** @var ContainerInterface $containerMock */
        $manager    = new ListenerManager($containerMock);

        $this->setExpectedException(Exception\InvalidListenerException::class);
        $manager->getListener($this->listener_name);
    }
}
