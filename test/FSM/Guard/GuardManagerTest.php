<?php

namespace FSM\Guard;

use FSM\Container\ContainerInterface;

/**
 * Guard manager PHP Unit tests
 *
 * @package FSM\Guard
 */
class GuardManagerTest extends \PHPUnit_Framework_TestCase
{
    private $guard_name = 'some_guard';

    public function testGetGuard()
    {
        $guardMock = $this->getMock(GuardInterface::class);

        $containerMock = $this->getMock(
            ContainerInterface::class,
            ['get', 'has']
        );
        $containerMock
            ->expects($this->once())
            ->method('has')
            ->with($this->guard_name)
            ->will($this->returnValue(true));
        $containerMock
            ->expects($this->once())
            ->method('get')
            ->with($this->guard_name)
            ->will($this->returnValue($guardMock));

        /** @var ContainerInterface $containerMock */
        $manager = new GuardManager($containerMock);
        $guard      = $manager->getGuard($this->guard_name);

        $this->assertInstanceOf(GuardInterface::class, $guard);
    }

    public function testGetGuardCallable()
    {
        $guardMock = $this->getMock(GuardInterface::class);

        $containerMock = $this->getMock(
            ContainerInterface::class,
            ['get', 'has']
        );
        $containerMock
            ->expects($this->once())
            ->method('has')
            ->with($this->guard_name)
            ->will($this->returnValue(true));
        $containerMock
            ->expects($this->once())
            ->method('get')
            ->with($this->guard_name)
            ->will($this->returnValue($guardMock));

        /** @var ContainerInterface $containerMock */
        $manager    = new GuardManager($containerMock);

        $this->assertTrue(is_callable($manager->getGuardCallable($this->guard_name)));
    }

    public function testGetGuardNotFound()
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
        $manager = new GuardManager($containerMock);

        $this->setExpectedException(Exception\GuardNotFoundException::class);
        $manager->getGuard($this->guard_name);
    }

    public function testGetGuardIsNotGuardInterface()
    {
        $containerMock = $this->getMock(
            ContainerInterface::class,
            ['get', 'has']
        );
        $containerMock
            ->expects($this->once())
            ->method('has')
            ->with($this->guard_name)
            ->will($this->returnValue(true));
        $containerMock
            ->expects($this->once())
            ->method('get')
            ->with($this->guard_name)
            ->will($this->returnValue($this->anything()));

        /** @var ContainerInterface $containerMock */
        $manager = new GuardManager($containerMock);

        $this->setExpectedException(Exception\InvalidGuardException::class);
        $manager->getGuard($this->guard_name);
    }
}
