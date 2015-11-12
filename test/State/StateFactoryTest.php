<?php

namespace FSM\State;

/**
 * State factory PHPUnit tests
 *
 * @package FSM\State
 */
class StateFactoryTest extends \PHPUnit_Framework_TestCase
{
    private $config = [
        'state_regular'         => ['type' => StateInterface::TYPE_REGULAR],
        'state_untyped'         => [],
    ];

    /** @var  StateFactoryInterface */
    private $factory;


    public function testGetState()
    {
        $stateName = 'state_regular';
        $state     = $this->factory->getState($stateName);

        $this->assertInstanceOf(__NAMESPACE__ . '\StateInterface', $state);
        $this->assertEquals($stateName, $state->getName());
        $this->assertEquals(StateInterface::TYPE_REGULAR, $state->getType());
    }

    public function testGetStateNotFound()
    {
        $this->setExpectedException(__NAMESPACE__ . '\Exception\StateNotFound');

        $this->factory->getState('another_state');
    }

    public function testGetStateUntyped()
    {
        $this->setExpectedException(__NAMESPACE__ . '\Exception\InvalidStateConfig');

        $this->factory->getState('state_untyped');
    }


    protected function setUp()
    {
        $this->factory      = new StateFactory($this->config);
    }

    protected function tearDown()
    {
        unset($this->factory);
    }
}
