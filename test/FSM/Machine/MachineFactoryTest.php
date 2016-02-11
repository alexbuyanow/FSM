<?php

namespace FSM\Machine;

use FSM\Container\ContainerInterface;
use FSM\State\StateFactory;
use FSM\State\StateInterface;
use FSM\Transition\TransitionFactory;

/**
 * Machine factory test
 */
class MachineFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testGetMachine()
    {
        $factory = new MachineFactory([]);
        $machine = $factory->getMachine(
            'test_machine',
            $this->getConfigArray(),
            $this->getContainerMock()
        );

        $this->assertInstanceOf(MachineInterface::class, $machine);
    }

    public function testGetMachineConfigNotContainsStatesSection()
    {
        $this->expectException(Exception\InvalidConfigException::class);

        $config = $this->getConfigArray();
        unset($config[MachineFactory::CONFIG_KEY_STATES]);

        $factory = new MachineFactory([]);
        $factory->getMachine(
            'test_machine',
            $config,
            $this->getContainerMock()
        );
    }

    public function testGetMachineConfigNotContainsTransitionsSection()
    {
        $this->expectException(Exception\InvalidConfigException::class);

        $config = $this->getConfigArray();
        unset($config[MachineFactory::CONFIG_KEY_TRANSITIONS]);

        $factory = new MachineFactory([]);
        $factory->getMachine(
            'test_machine',
            $config,
            $this->getContainerMock()
        );
    }

    public function testGetMachineConfigNotContainsListenersSection()
    {
        $this->expectException(Exception\InvalidConfigException::class);

        $config = $this->getConfigArray();
        unset($config[MachineFactory::CONFIG_KEY_LISTENERS]);

        $factory = new MachineFactory([]);
        $factory->getMachine(
            'test_machine',
            $config,
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
                'state1' => [StateFactory::CONFIG_KEY_TYPE => StateInterface::TYPE_REGULAR],
                'state2' => [StateFactory::CONFIG_KEY_TYPE => StateInterface::TYPE_REGULAR],
            ],
            MachineFactory::CONFIG_KEY_TRANSITIONS   => [
                [
                    TransitionFactory::CONFIG_KEY_STATE_FROM  => 'state1',
                    TransitionFactory::CONFIG_KEY_STATE_TO    => 'state2',
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
        return $this->getMock(ContainerInterface::class);
    }
}
