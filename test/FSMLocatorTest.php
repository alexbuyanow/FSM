<?php

namespace FSM;

use FSM\Container\ContainerInterface;
use FSM\Machine\MachineFactoryInterface;
use FSM\State\StateFactory;
use FSM\State\StateInterface;
use FSM\Transition\TransitionFactory;

/**
 * Machine factory locator test
 */
class FSMLocatorTest extends \PHPUnit_Framework_TestCase
{
    public function testGetMachineFactory()
    {
        $locator = new FSMLocator($this->getConfig(), $this->getContainerMock());

        $this->assertInstanceOf(
            MachineFactoryInterface::class,
            $locator->getMachineFactory($this->getContextMock())
        );
    }

    /**
     * @return ContextInterface
     */
    private function getContextMock()
    {
        $mock = $this->getMock(
            ContextInterface::class,
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
            ContainerInterface::class,
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
            FSMLocator::CONFIG_KEY_MACHINES => [
                'ContextClass' => [
                    MachineFactoryInterface::CONFIG_KEY_STATES      => [
                        'from'  => [StateFactory::CONFIG_KEY_TYPE => StateInterface::TYPE_REGULAR],
                        'to'    => [StateFactory::CONFIG_KEY_TYPE => StateInterface::TYPE_REGULAR],
                    ],
                    MachineFactoryInterface::CONFIG_KEY_TRANSITIONS => [
                        [
                            TransitionFactory::CONFIG_KEY_STATE_FROM  => 'from',
                            TransitionFactory::CONFIG_KEY_STATE_TO    => 'to',
                        ],
                    ],
                    MachineFactoryInterface::CONFIG_KEY_LISTENERS   =>  [],
                ],
            ],
        ];
    }
}
