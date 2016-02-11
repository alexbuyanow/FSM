<?php

namespace FSM\Container;

use PHPUnit_Framework_TestCase;
use Pimple\Container;

/**
 * Pimple container test
 */
class PimpleContainerTest extends PHPUnit_Framework_TestCase
{
    private $existsEntryName    = 'exists_entry';
    private $notExistsEntryName = 'not_exists_entry';

    public function testHas()
    {
        $mock = $this->getPimpleMock();
        $mock
            ->expects($this->exactly(2))
            ->method('offsetExists')
            ->withConsecutive(
                $this->equalTo($this->existsEntryName),
                $this->equalTo($this->anything())
            )
            ->will($this->returnCallback([$this, 'pimpleMockHasCallback']));

        /** @var Container $mock */
        $container = new PimpleContainer($mock);

        $this->assertTrue($container->has($this->existsEntryName));
        $this->assertFalse($container->has($this->notExistsEntryName));
    }

    public function testGet()
    {
        $mock = $this->getPimpleMock();
        $mock
            ->expects($this->once())
            ->method('offsetExists')
            ->with($this->equalTo($this->existsEntryName))
            ->will($this->returnValue(true));
        $mock
            ->expects($this->once())
            ->method('offsetGet')
            ->with($this->equalTo($this->existsEntryName))
            ->will($this->returnValue(new \ArrayObject()));

        /** @var Container $mock */
        $container = new PimpleContainer($mock);

        $this->assertInstanceOf(\ArrayObject::class, $container->get($this->existsEntryName));
    }

    public function testGetWithNotExistsEntry()
    {
        $mock = $this->getPimpleMock();
        $mock
            ->expects($this->once())
            ->method('offsetExists')
            ->with($this->equalTo($this->notExistsEntryName))
            ->will($this->returnValue(false));

        /** @var Container $mock */
        $container = new PimpleContainer($mock);

        $this->expectException(Exception\NotFoundException::class);
        $container->get($this->notExistsEntryName);
    }

    /**
     * Returns Pimple container mock
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getPimpleMock()
    {
        $mock = $this->getMock(Container::class, ['offsetGet', 'offsetExists']);

        return $mock;
    }

    /**
     * Callback for has value variations
     *
     * @param string $value
     * @return boolean
     */
    public function pimpleMockHasCallBack($value)
    {
        return $value == $this->existsEntryName;
    }
}
