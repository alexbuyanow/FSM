<?php

namespace FSM\Machine;

use FSM\Container\ContainerInterface;
use FSM\State\StateInterface;

/**
 * Machine factory test
 */
class MachineFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testGetMachine()
    {
        $factory = new MachineFactory(
            'test_machine',
            $this->getConfigArray(),
            [],
            $this->getContainerMock()
        );

        $machine = $factory->getMachine();
        $this->assertInstanceOf('FSM\Machine\Machine', $machine);
    }

    public function testGetMachineConfigNotContainsStatesSection()
    {
        $config = $this->getConfigArray();
        unset($config[MachineFactory::CONFIG_KEY_STATES]);

        $this->setExpectedException(__NAMESPACE__ . '\Exception\InvalidConfigException');

        new MachineFactory(
            'test_machine',
            $config,
            [],
            $this->getContainerMock()
        );
    }

    public function testGetMachineConfigNotContainsTransitionsSection()
    {
        $config = $this->getConfigArray();
        unset($config[MachineFactory::CONFIG_KEY_TRANSITIONS]);

        $this->setExpectedException(__NAMESPACE__ . '\Exception\InvalidConfigException');

        new MachineFactory(
            'test_machine',
            $config,
            [],
            $this->getContainerMock()
        );
    }

    public function testGetMachineConfigNotContainsListenersSection()
    {
        $config = $this->getConfigArray();
        unset($config[MachineFactory::CONFIG_KEY_LISTENERS]);

        $this->setExpectedException(__NAMESPACE__ . '\Exception\InvalidConfigException');

        new MachineFactory(
            'test_machine',
            $config,
            [],
            $this->getContainerMock()
        );
    }

    /**
     * @return array
     */
    private function getConfigArray()
    {
        return [
            MachineFactory::CONFIG_KEY_STATES        => [
                'state1' => ['type' => StateInterface::TYPE_REGULAR],
                'state2' => ['type' => StateInterface::TYPE_REGULAR],
            ],
            MachineFactory::CONFIG_KEY_TRANSITIONS   => [
                [
                    'from'  => 'state1',
                    'to'    => 'state2',
                ]
            ],
            MachineFactory::CONFIG_KEY_LISTENERS     => [],
        ];
    }

    /**
     * @return ContainerInterface
     */
    private function getContainerMock()
    {
        return $this->getMock('FSM\Container\ContainerInterface');
    }
}
