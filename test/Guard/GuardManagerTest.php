<?php

namespace FSM\Guard;

use FSM\Container\ContainerInterface;

/**
 * Guard factory PHP Unit tests
 *
 * @package FSM\Guard
 */
class GuardManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testGetGuard()
    {
        $guardMock = $this->getMock('FSM\Guard\GuardInterface');

        $containerMock = $this->getMock(
            'FSM\Container\ContainerInterface',
            ['get']
        );
        $containerMock
            ->expects($this->once())
            ->method('get')
            ->with('some_guard')
            ->willReturn($guardMock);

        /** @var ContainerInterface $containerMock */
        $factory    = new GuardManager($containerMock);
        $guard      = $factory->getGuard('some_guard');

        $this->assertInstanceOf('FSM\Guard\GuardInterface', $guard);
    }

    public function testGetGuardIsNotInDI()
    {
        $containerMock = $this->getMock(
            'FSM\Container\ContainerInterface',
            ['get']
        );
        $containerMock
            ->expects($this->once())
            ->method('get')
            ->with('some_guard')
            ->willThrowException(new \InvalidArgumentException());

        /** @var ContainerInterface $containerMock */
        $factory = new GuardManager($containerMock);

        $this->setExpectedException(__NAMESPACE__ . '\Exception\GuardNotFoundException');
        $factory->getGuard('some_guard');
    }

    public function testGetGuardIsNotGuardInterface()
    {
        $containerMock = $this->getMock(
            'FSM\Container\ContainerInterface',
            ['get']
        );
        $containerMock
            ->expects($this->once())
            ->method('get')
            ->with('some_guard')
            ->willReturn(new \ArrayObject());

        /** @var ContainerInterface $containerMock */
        $factory = new GuardManager($containerMock);

        $this->setExpectedException(__NAMESPACE__ . '\Exception\InvalidGuardException');
        $factory->getGuard('some_guard');
    }
}
