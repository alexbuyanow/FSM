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
        'state_regular'     => [StateFactory::CONFIG_KEY_TYPE => StateInterface::TYPE_REGULAR],
        'state_untyped'     => [],
    ];

    /** @var  StateFactoryInterface */
    private $factory;


    public function testGetState()
    {
        $stateName = 'state_regular';
        $state     = $this->factory->getState($stateName);

        $this->assertInstanceOf(StateInterface::class, $state);
        $this->assertEquals($stateName, $state->getName());
        $this->assertEquals(StateInterface::TYPE_REGULAR, $state->getType());
    }

    public function testGetStateNotFound()
    {
        $this->setExpectedException(Exception\StateNotFound::class);

        $this->factory->getState('another_state');
    }

    public function testGetStateUntyped()
    {
        $this->setExpectedException(Exception\InvalidStateConfig::class);

        $this->factory->getState('state_untyped');
    }


    protected function setUp()
    {
        $this->factory = new StateFactory($this->config);
    }

    protected function tearDown()
    {
        unset($this->factory);
    }
}
