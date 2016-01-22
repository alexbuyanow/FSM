<?php
namespace FSM\Transition;

use FSM\Guard\GuardManagerInterface;
use FSM\State\StateFactory;
use FSM\State\StateFactoryInterface;
use FSM\State\StateInterface;

/**
 * Transition factory test
 *
 * @package FSM\Transition
 */
class TransitionFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testGetTransition()
    {
        $config = [
            TransitionFactory::CONFIG_KEY_STATE_FROM    => 'state_from',
            TransitionFactory::CONFIG_KEY_STATE_TO      => 'state_to',
            TransitionFactory::CONFIG_KEY_SIGNAL        => 'some_signal',
        ];

        $stateMock = $this->getMock(
            'FSM\State\StateInterface',
            ['getName', 'getType', 'isIdentical']
        );
        $stateFactoryMock = $this->getMock(
            'FSM\State\StateFactoryInterface',
            ['getState'],
            [$this->getStateConfig()]
        );
        $stateFactoryMock
            ->expects($this->exactly(2))
            ->method('getState')
            ->willReturn($stateMock);

        $guardFactoryMock = $this->getMock('FSM\Guard\GuardManagerInterface');

        /** @var StateFactoryInterface $stateFactoryMock */
        /** @var GuardManagerInterface $guardFactoryMock */
        $transitionFactory = new TransitionFactory($stateFactoryMock, $guardFactoryMock);
        $transition        = $transitionFactory->getTransition($config);

        $this->assertInstanceOf('FSM\Transition\TransitionInterface', $transition);
        $this->assertInstanceOf('FSM\State\StateInterface', $transition->getStateFrom());
        $this->assertInstanceOf('FSM\State\StateInterface', $transition->getStateTo());
        $this->assertEquals('some_signal', $transition->getSignal());
    }

    public function testGetTransitionHasNotFromState()
    {
        $config = [
            TransitionFactory::CONFIG_KEY_STATE_TO  => 'state_to',
            TransitionFactory::CONFIG_KEY_SIGNAL    => 'some_signal',
        ];

        $stateFactoryMock = $this->getMock(
            'FSM\State\StateFactoryInterface',
            ['getState'],
            [$this->getStateConfig()]
        );

        $guardFactoryMock = $this->getMock('FSM\Guard\GuardManagerInterface');

        /** @var StateFactoryInterface $stateFactoryMock */
        /** @var GuardManagerInterface $guardFactoryMock */
        $transitionFactory = new TransitionFactory($stateFactoryMock, $guardFactoryMock);

        $this->setExpectedException(__NAMESPACE__ . '\Exception\InvalidTransitionConfig');

        $transitionFactory->getTransition($config);
    }

    public function testGetTransitionHasNotToState()
    {
        $config = [
            TransitionFactory::CONFIG_KEY_STATE_FROM    => 'state_from',
            TransitionFactory::CONFIG_KEY_SIGNAL        => 'some_signal',
        ];

        $stateFactoryMock = $this->getMock(
            'FSM\State\StateFactoryInterface',
            ['getState'],
            [$this->getStateConfig()]
        );

        $guardFactoryMock = $this->getMock('FSM\Guard\GuardManagerInterface');

        /** @var StateFactoryInterface $stateFactoryMock */
        /** @var GuardManagerInterface $guardFactoryMock */
        $transitionFactory = new TransitionFactory($stateFactoryMock, $guardFactoryMock);

        $this->setExpectedException(__NAMESPACE__ . '\Exception\InvalidTransitionConfig');

        $transitionFactory->getTransition($config);
    }


    private function getStateConfig()
    {
        return ['state' => [StateFactory::CONFIG_KEY_TYPE => StateInterface::TYPE_REGULAR]];
    }
}
