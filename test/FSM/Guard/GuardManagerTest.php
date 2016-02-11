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
    public function testGetGuard()
    {
        $guardMock = $this->getMock(GuardInterface::class);

        $containerMock = $this->getMock(
            ContainerInterface::class,
            ['get', 'has']
        );
        $containerMock
            ->expects($this->once())
            ->method('get')
            ->with('some_guard')
            ->willReturn($guardMock);

        /** @var ContainerInterface $containerMock */
        $manager = new GuardManager($containerMock);
        $guard      = $manager->getGuard('some_guard');

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
            ->method('get')
            ->with('some_guard')
            ->willReturn($guardMock);

        /** @var ContainerInterface $containerMock */
        $manager    = new GuardManager($containerMock);

        $this->assertTrue(is_callable($manager->getGuardCallable('some_guard')));
    }

    public function testGetGuardIsNotInDI()
    {
        $containerMock = $this->getMock(
            ContainerInterface::class,
            ['get', 'has']
        );
        $containerMock
            ->expects($this->once())
            ->method('get')
            ->with('some_guard')
            ->willThrowException(new \InvalidArgumentException());

        /** @var ContainerInterface $containerMock */
        $manager = new GuardManager($containerMock);

        $this->setExpectedException(Exception\GuardNotFoundException::class);
        $manager->getGuard('some_guard');
    }

    public function testGetGuardIsNotGuardInterface()
    {
        $containerMock = $this->getMock(
            ContainerInterface::class,
            ['get', 'has']
        );
        $containerMock
            ->expects($this->once())
            ->method('get')
            ->with('some_guard')
            ->willReturn(new \ArrayObject());

        /** @var ContainerInterface $containerMock */
        $manager = new GuardManager($containerMock);

        $this->setExpectedException(Exception\InvalidGuardException::class);
        $manager->getGuard('some_guard');
    }
}
