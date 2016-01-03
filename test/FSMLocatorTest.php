<?php

namespace FSM;

use FSM\Container\ContainerInterface;
use FSM\Machine\MachineFactoryInterface;
use FSM\State\StateInterface;

/**
 * Machine factory locator test
 */
class FSMLocatorTest extends \PHPUnit_Framework_TestCase
{
    public function testGetMachineFactory()
    {
        $resolverMock = $this->getMock(
            'FSM\Resolver\ResolverInterface',
            ['isContained', 'getConfig', 'getConfigName']
        );

        $locator = new FSMLocator($this->getConfig(), $this->getContainerMock());

        $this->assertInstanceOf(
            'FSM\Machine\MachineFactory',
            $locator->getMachineFactory($this->getContextMock())
        );
    }

    /**
     * @return ContextInterface
     */
    private function getContextMock()
    {
        $mock = $this->getMock(
            'FSM\ContextInterface',
            ['getContextUid', 'getContextState', 'setContextState'],
            [],
            'ContextClass'
        );

        $mock
            ->expects($this->any())
            ->method('getContextUid')
            ->willReturn('TestUID');

        return $mock;
    }

    /**
     * @return ContainerInterface
     */
    private function getContainerMock()
    {
        $mock = $this->getMock(
            'FSM\Container\ContainerInterface',
            ['get']
        );

        return $mock;
    }

    /**
     * @return array
     */
    private function getConfig()
    {
        return [
            'machines' => [
                'ContextClass' => [
                    MachineFactoryInterface::CONFIG_KEY_STATES =>           [
                        'from'  => ['type' => StateInterface::TYPE_REGULAR],
                        'to'    => ['type' => StateInterface::TYPE_REGULAR],
                    ],
                    MachineFactoryInterface::CONFIG_KEY_TRANSITIONS =>      [
                        [
                            'from'  => 'from',
                            'to'    => 'to',
                        ],
                    ],
                    MachineFactoryInterface::CONFIG_KEY_LISTENERS =>        [],
                ],
            ],
        ];
    }
}
